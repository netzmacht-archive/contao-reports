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
namespace Netzmacht\Reports\Config;

/**
 * Class ReportsConfig
 * @package Netzmacht\Reports\Config
 */
class ReportsConfig
{
	/**
	 * @var array
	 */
	protected static $arrTables = array();

	/**
	 * @var bool
	 */
	protected static $blnInitialized = false;


	/**
	 * initalize report config so that only 1 database request is needed
	 *
	 */
	public static function initialize()
	{
		// only run once
		if(self::$blnInitialized)
		{
			return;
		}

		self::$blnInitialized = true;

		$objReports = \ReportsModel::findAll();

		if($objReports === null)
		{
			return;
		}

		while($objReports->next())
		{
			list($strGroup, $strModule) = explode('.', $objReports->module);

			$GLOBALS['BE_MOD'][$strGroup][$strModule]['report'] = array('Netzmacht\Reports\Module\ReportsModule', 'generate');

			self::$arrTables[] = $objReports->tableName;
		}
	}


	/**
	 * @param $strTable
	 * @hook loadDataContainer
	 */
	public static function onLoadDataContainer($strTable)
	{
		self::initialize();

		$strCurrentTable = \Input::get('table');
		if($strCurrentTable == '')
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

			$strCurrentTable = $arrModule['tables'][0];
		}

		// only apply config for current table
		if($strCurrentTable == $strTable && in_array($strTable, self::$arrTables))
		{
			// inject report operation
			array_insert($GLOBALS['TL_DCA'][$strTable]['list']['global_operations'], 0, array (
				'report' => array
				(
					'href' => 'key=report',
					'class' => 'header_report',
					'attributes' => 'onclick="Backend.getScrollOffset()"',
					'label' => &$GLOBALS['TL_LANG']['MSC']['report'],
				)
			));

			// provide headline for key=report
			$GLOBALS['TL_LANG'][$strTable]['report'] = $GLOBALS['TL_LANG']['MSC']['reportCreateHeadline'];
		}
	}

}