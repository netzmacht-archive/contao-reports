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
 * Namespace
 */
namespace Netzmacht\Reports\DataContainer;
use Contao\TemplateLoader;

/**
 * Class ReportsDataContainer 
 *
 * @copyright  2013 netzmacht creative David Molineus 
 * @author     David Molineus http://netzmacht.de 
 * @package    Devtools
 */
class ReportsDataContainer extends \Backend
{

	/**
	 * get all backend modules which have a table section combined with its group name so finding it again will be easier
	 * (group.module)
	 *
	 * @param $objDc
	 * @return array
	 */
	public function getModules($objDc)
	{
		$arrGroups = array();

		foreach($GLOBALS['BE_MOD'] as $strGroupName => $arrGroupModules)
		{
			foreach($arrGroupModules as $strModuleName => $arrModule)
			{
				if(isset($arrModule['tables']))
				{
					$arrGroups[$strGroupName][$strGroupName . '.' . $strModuleName] = $strModuleName;
				}
			}
		}

		return $arrGroups;
	}


	/**
	 * get all table field names
	 * @return array
	 */
	public function getTableFields()
	{
		$arrReturn = array();
		$objReport = $this->getCurrentReport();

		if($objReport->tableName)
		{
			$this->loadLanguageFile($objReport->tableName);
			$this->loadDataContainer($objReport->tableName);

			foreach($GLOBALS['TL_DCA'][$objReport->tableName]['fields'] as $strField => $arrField)
			{
				if(isset($arrField['label'][0]))
				{
					$arrReturn[$strField] = $arrField['label'][0];
				}
				else {
					$arrReturn[$strField] = $strField;
				}
			}
		}

		return $arrReturn;
	}


	/**
	 * get all tables which are allowed of a module
	 * @param $objDc
	 * @return array
	 */
	public function getTables($objDc)
	{
		$arrTables = array();

		$objRecord = $this->Database
			->prepare('SELECT module FROM ' . $objDc->table . ' WHERE id=?')
			->execute($objDc->id);

		list($strGroup, $strModule) = explode('.', $objRecord->module);


		if($objRecord->module && isset($strGroup))
		{
			$arrTables = $GLOBALS['BE_MOD'][$strGroup][$strModule]['tables'];
		}

		return $arrTables;
	}


	/**
	 * get all templates with prefix report
	 *
	 * @param $objDc
	 * @return array
	 */
	public function getTemplates($objDc)
	{
		return TemplateLoader::getPrefixedFiles('report_');
	}


	/**
	 * @return mixed
	 */
	protected function getCurrentReport()
	{
		return $this->Database
			->prepare('SELECT * FROM tl_reports WHERE id=?')
			->execute(\Input::get('id'));
	}
}
