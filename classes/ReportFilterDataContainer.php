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

class ReportFilterDataContainer extends \Backend
{

	public function getFilterNames($objDc)
	{
		return array_keys($GLOBALS['REPORTS']['filters']);
	}

	public function getFieldNames($objDc)
	{
		$arrFields = array();

		$objReport = \ReportsModel::findByPK($objDc->activeRecord->pid);

		$this->loadDataContainer($objReport->tableName);
		$this->loadLanguageFile($objReport->tableName);

		foreach($GLOBALS['TL_DCA'][$objReport->tableName]['fields'] as $strField => $arrField)
		{
			if(isset($arrField['label'][0]))
			{
				$arrFields[$strField] = $arrField['label'][0];
			}
			else
			{
				$arrFields[$strField] = $strField;
			}

		}

		return $arrFields;
	}

	public function listFilter($arrRow)
	{
		return sprintf('%s <span class="#b3b3b3">(%s)</span>', $arrRow['name'], $GLOBALS['TL_LANG']['tl_report_filter'][$arrRow['type']]);
	}
}