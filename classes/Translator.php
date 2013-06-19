<?php 

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package   translator 
 * @author    David Molineus http://netzmacht.de 
 * @license   LGPL 
 * @copyright 2013 netzmacht creative David Molineus 
 */


/**
 * Namespace
 */
namespace Netzmacht\Reports;

/**
 * Class Translator 
 *
 * @copyright  2013 netzmacht creative David Molineus 
 * @author     David Molineus http://netzmacht.de 
 * @package    Devtools
 */
class Translator extends \Controller
{

	/**
	 * @var string
	 */
	protected static $strTable;

	/**
	 * @var Translator
	 */
	protected static $objInstance;


	/**
	 * singleton get instance
	 * @return Translator
	 */
	public static function getInstance()
	{
		if(self::$objInstance === null)
		{
			self::$objInstance = new Translator();
		}

		return self::$objInstance;
	}


	/**
	 * set table global
	 * @param $strTable
	 */
	public static function setTable($strTable)
	{
		self::$strTable = $strTable;
	}

	/**
	 * @param $strField field name
	 * @param null $strTable table name, global table will be used if null
	 * @return string
	 */
	public static function getFieldLabel($strField, $strTable=null)
	{
		$strTable = ($strTable === null) ? self::$strTable : $strTable;

		self::initializeTable($strTable);

		if(isset($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['label'][0]))
		{
			return $GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['label'][0];
		}
		else
		{
			return $strField;
		}
	}


	/**
	 * @param $strField
	 * @param $varValue
	 * @param null $strTable
	 * @return mixed|string
	 */
	public static function parseFieldValue($strField, $varValue, $strTable=null)
	{
		$strTable = ($strTable === null) ? self::$strTable : $strTable;

		$objTranslator = self::getInstance();
		$objTranslator->import('Database');

		self::initializeTable($strTable);

		$varValue = deserialize($varValue);

		// get value @see DC_TABLE::show
		if (isset($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['foreignKey']))
		{
			$temp = array();
			$chunks = explode('.', $GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['foreignKey'], 2);

			foreach ((array) $varValue as $v)
			{
				$objKey = $objTranslator->Database->prepare("SELECT " . $chunks[1] . " AS value FROM " . $chunks[0] . " WHERE id=?")
					->limit(1)
					->execute($v);

				if ($objKey->numRows)
				{
					$temp[] = $objKey->value;
				}
			}

			$varValue = implode(', ', $temp);
		}
		elseif (is_array($varValue))
		{
			foreach ($varValue as $kk=>$vv)
			{
				if (is_array($vv))
				{
					$vals = array_values($vv);
					$varValue[$kk] = $vals[0].' ('.$vals[1].')';
				}
			}

			$varValue = implode(', ', $varValue);
		}
		elseif ($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['eval']['rgxp'] == 'date')
		{
			$varValue = $varValue ? $objTranslator->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $varValue) : '-';
		}
		elseif ($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['eval']['rgxp'] == 'time')
		{
			$varValue = $varValue ? $objTranslator->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $varValue) : '-';
		}
		elseif ($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['eval']['rgxp'] == 'datim'
			|| in_array($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['flag'],
				array(5, 6, 7, 8, 9, 10)
			)
			|| $strField == 'tstamp'
		)
		{
			$varValue = $varValue ? $objTranslator->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $varValue) : '-';
		}
		elseif ($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['inputType'] == 'checkbox' && !$GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['eval']['multiple'])
		{
			$varValue = ($varValue != '') ? $GLOBALS['TL_LANG']['MSC']['yes'] : $GLOBALS['TL_LANG']['MSC']['no'];
		}
		elseif ($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['inputType'] == 'textarea' && ($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['eval']['allowHtml'] || $GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['eval']['preserveTags']))
		{
			$varValue = specialchars($varValue);
		}
		elseif (is_array($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['reference']))
		{
			$varValue = isset($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['reference'][$varValue]) ? ((is_array($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['reference'][$varValue])) ? $GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['reference'][$varValue][0] : $GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['reference'][$varValue]) : $varValue;
		}
		elseif ($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['eval']['isAssociative'] || array_is_assoc($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['options']))
		{
			$varValue = $GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['options'][$varValue];
		}

		return $varValue;
	}


	/**
	 * @param $strTable
	 */
	protected static function initializeTable($strTable)
	{
		$objTranslator = self::getInstance();
		$objTranslator->loadLanguageFile($strTable);
		$objTranslator->loadDataContainer($strTable);
	}

}
