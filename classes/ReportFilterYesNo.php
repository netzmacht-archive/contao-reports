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
namespace Netzmacht\Reports\Filter;


/**
 * Class ReportFilterTimeFrame
 * @package Netzmacht\Reports\Filter
 */
class ReportFilterYesNo extends ReportFilter
{


	/**
	 * @param \Contao\HasteForm $objForm
	 * @return string
	 */
	public function getSQL(\Contao\HasteForm $objForm)
	{
		if(strlen($objForm->fetch($this->getFieldName('state'))) > 0)
		{
			return $this->objModel->field . ' = ?';
		}

		return false;
	}


	/**
	 * @param $objForm
	 * @return array
	 */
	public function getSQLValues(\Contao\HasteForm $objForm)
	{
		$arrValues = array();

		if(strlen($objForm->fetch($this->getFieldName('state'))) > 0)
		{
			$arrValues[] = $objForm->fetch($this->getFieldName('state')) == 'yes' ? '1' : 0;
		}

		return $arrValues;
	}


	/**
	 * @inherit
	 * @return array
	 */
	public function getFieldDefinitions()
	{
		$strTable = $this->objModel->getRelated('pid')->tableName;

		$arrFields = array
		(
			$this->getFieldName('state') => array
			(
				'name' => $this->getFieldName('state'),
				'label' => &$GLOBALS['TL_DCA'][$strTable]['fields'][$this->objModel->field]['label'],
				'inputType' => 'select',
				'options' => array(
					'yes' => &$GLOBALS['TL_LANG']['MSC']['yes'],
					'no' => &$GLOBALS['TL_LANG']['MSC']['no']
				),
				'default' => $this->objModel->defaultChecked,
				'eval' => array('multiple' => false, 'includeBlankOption' => true),
			),

		);

		return $arrFields;
	}

}