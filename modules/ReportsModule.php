<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package   reports
 * @author    David Molineus http://netzmacht.de
 * @license   LGPL
 * @copyright 2013 netzmacht creative David Molineus
 */


/**
 * ReportsModule is used for generating the reports and generating the report forms
 */
namespace Netzmacht\Reports\Module;
use Netzmacht\Reports\Form;
use Netzmacht\Reports\Translator;

/**
 * Class ReportsModule
 * @package Netzmacht\Reports\Module
 */
class ReportsModule extends \BackendModule
{

	/**
	 * @var mixed
	 */
	protected $strTable;

	/**
	 * @var mixed
	 */
	protected $intId;

	/**
	 * @var null
	 */
	protected $arrFilters = null;

	/**
	 * @var null
	 */
	protected $arrFieldDefinitions = null;



	/**
	 * @param null $objDc
	 */
	public function __construct($objDc = null)
	{
		parent::__construct($objDc);

		$this->intId = \Input::get('rid');
		$this->strTable = \Input::get('table');

		if($this->strTable == '')
		{
			$strModule = \Input::get('do');

			foreach($GLOBALS['BE_MOD'] as $arrGroups)
			{
				foreach($arrGroups as $strModuleName => $arrModule)
				{
					if($strModuleName == $strModule)
					{
						break 2;
					}
				}
			}

			$this->strTable = $arrModule['tables'][0];
		}
	}


	/**
	 * compile method will switch between report chosser, report form and report list
	 */
	protected function compile()
	{
		// back button
		$this->Template->href = $this->getReferer(true);
		$this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']);
		$this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];


		// id is given, so generate single report
		if($this->intId)
		{
			$objReport = \ReportsModel::findByPK($this->intId);
			$this->generateReport($objReport);
			return;
		}

		$objReports = \ReportsModel::findByTableName($this->strTable);

		// invalid call
		if($objReports === null)
		{
			$this->log('Invalid report call'. 'ReportsModule::compile', TL_ERROR);
			$this->redirect($this->addToUrl('act=error'));
		}

		// only one report exists, use it by default
		elseif($objReports->count() == 1)
		{
			$this->intId = $objReports->id;
			$this->generateReport($objReports);
		}

		// create chooser list
		else
		{
			$this->generateReportChooser($objReports);
		}

	}


	/**
	 * generate the report or report form
	 *
	 * @param $objReport
	 */
	protected function generateReport($objReport)
	{
		$this->loadFilters();
		$objForm = $this->prepareForm($objReport);

		// form is submitted, generate report
		if($objForm->validate())
		{
			$arrSQL = array();
			$arrValues = array();

			// fetch sql statements of the filter
			foreach($this->arrFilters as $objFilter)
			{
				$strSQL = $objFilter->getSQL($objForm);

				if($strSQL)
				{
					$arrSQL[] = $strSQL;
					$arrValues = array_merge($arrValues, $objFilter->getSQLValues($objForm));
				}
			}

			$strOrder = '';
			$arrSortingFields = deserialize($objReport->sortingFields);

			if(is_array($arrSortingFields) && !empty($arrSortingFields) && $arrSortingFields[0]['name'] != '')
			{
				$strOrder = 'ORDER BY ';
				$arrOrder = array();

				foreach($arrSortingFields as $arrSortingField)
				{
					if($arrSortingField['name'] != '')
					{
						$arrOrder[] = $arrSortingField['name'] . ' ' . $arrSortingField['direction'];
					}
				}

				$strOrder .= implode(', ', $arrOrder);
			}


			// prepare satement
			$objStatement = $this->Database->prepare(sprintf(
				'SELECT %s FROM %s WHERE %s %s',
				implode(', ', $objForm->fetch('fields')),
				$this->strTable,
				implode(' AND ', $arrSQL),
				$strOrder
			));

			// pass values to the statement
			$objResult = call_user_func_array(array($objStatement, 'execute'), $arrValues);

			$this->Template->setName($objReport->template);
			$this->Template->events = $objResult;
			$this->Template->fields = \Input::post('fields');
			$this->Template->labels = $this->arrFieldDefinitions['fields']['options'];
			$this->Template->table = $this->strTable;
			$this->Template->headline = sprintf($GLOBALS['TL_LANG']['MSC']['reportListHeadline'][0], $objReport->title);

			$this->Template->output();
			die();
		}

		// generate form for the report
		else
		{
			$this->Template->setName('be_report');
			$this->Template->headline = sprintf($GLOBALS['TL_LANG']['MSC']['reportCreateHeadline'][0], $objReport->title);
			$this->Template->form = $objForm;
			$this->Template->description = $objReport->description;
		}
	}


	/**
	 * @param $objReports
	 */
	protected function generateReportChooser($objReports)
	{
		$this->Template->setName('be_report_chooser');
		$this->Template->headline = $GLOBALS['TL_LANG']['MSC']['reportChooseHeadline'][0];
		$this->Template->reports = $objReports;
		$this->Template->description = $GLOBALS['TL_LANG']['MSC']['reportChooserDescription'];
	}


	/**
	 * prepare haste form by getting all field definitions, loadFilters has to be called before
	 * @param $objReport
	 * @return HasteForm
	 */
	protected function prepareForm($objReport)
	{
		$arrFieldSets = array();

		// set first field as fieldset
		if(count($this->arrFieldDefinitions) > 0)
		{
			reset($this->arrFieldDefinitions);
			$arrFieldSets[] = key($this->arrFieldDefinitions);
		}

		// get all selected fields
		$arrRawFields = deserialize($objReport->fields);
		$arrFields = array();
		$arrFieldsDefault = array();

		foreach($arrRawFields as $arrField)
		{
			$arrFields[$arrField['name']] = Translator::getFieldLabel($arrField['name'], $this->strTable);;

			if($arrField['default'])
			{
				$arrFieldsDefault[] = $arrField['name'];
			}
		}

		// add fields selector
		$arrFieldSets[] = 'fields';
		$this->arrFieldDefinitions['fields'] = array
		(
			'inputType' => 'checkbox',
			'label' => array('Felder', 'Felder'),
			'eval' => array('multiple' => true),
			'options' => $arrFields,
			'default' => $arrFieldsDefault,
		);

		$objForm = new Form('report', $this->arrFieldDefinitions);
		$objForm->submit = $GLOBALS['TL_LANG']['MSC']['createReport'];

		// apply field sets
		foreach($arrFieldSets as $strFieldSet)
		{
			$objForm->addFieldSet($strFieldSet);
		}

		return $objForm;
	}


	/**
	 * load filters and create field definitions array
	 * @return void
	 */
	public function loadFilters()
	{
		if($this->arrFilters !== null)
		{
			return;
		}

		$objFilters = \ReportFilterModel::findByPid($this->intId, array('order' => 'sorting'));

		$this->arrFilters = array();
		$this->arrFieldDefinitions = array();

		// get field definitions needed for the filters
		if($objFilters === null)
		{
			return;
		}

		while($objFilters->next())
		{
			$strClass = $GLOBALS['REPORTS']['filters'][$objFilters->type];
			$objFilter = new $strClass($objFilters->current());

			$this->arrFieldDefinitions = array_merge($this->arrFieldDefinitions, $objFilter->getFieldDefinitions());
			$this->arrFilters[] = $objFilter;
		}
	}

}
