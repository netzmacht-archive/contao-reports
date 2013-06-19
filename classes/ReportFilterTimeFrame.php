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
class ReportFilterTimeFrame extends ReportFilter
{


	/**
	 * @param \Contao\HasteForm $objForm
	 * @return string
	 */
	public function getSQL(\Contao\HasteForm $objForm)
	{
		$strSQL = sprintf(
			'(%s > ? AND %s < ?)',
			$this->objModel->startField,
			$this->objModel->endField
		);

		return $strSQL;
	}

	/**
	 * @param $objForm
	 * @return array
	 */
	public function getSQLValues(\Contao\HasteForm $objForm)
	{
		$objStartDate = new \Date($objForm->fetch($this->getFieldName('start')), $GLOBALS['TL_CONFIG']['dateFormat']);
		$objEndDate = new \Date($objForm->fetch($this->getFieldName('end')), $GLOBALS['TL_CONFIG']['dateFormat']);

		return array(
			$objStartDate->timestamp,
			$objEndDate->timestamp
		);
	}


	/**
	 * @inherit
	 * @return array
	 */
	public function getFieldDefinitions()
	{
		// get default start date
		switch($this->objModel->defaultStart)
		{
			case 'today':
				$intDefaultStart = time();
				break;

			case 'weekStart':
				$intDefaultStart = strtotime('Last Sunday', time());
				break;

			case 'monthStart':
				$intDefaultStart = strtotime(date('Y-m-01'));
				break;

			case 'yearStart':
				$intDefaultStart = strtotime(date('Y-01-01'));
				break;
		}

		// get default end date
		switch($this->objModel->defaultEnd)
		{
			case 'today':
				$intDefaultEnd = time();
				break;

			case 'weekEnd':
				$intDefaultEnd = strtotime('Next Saturday', time());
				break;

			case 'monthEnd':
				$intDefaultEnd = strtotime(date('Y-m-t'));
				break;

			case 'yearEnd':
				$intDefaultEnd = strtotime(date('Y-12-31'));
				break;
		}

		$arrFields = array
		(
			$this->getFieldName('start') => array
			(
				'name' => $this->getFieldName('start'),
				'label' => &$GLOBALS['TL_LANG']['MSC']['reportStart'],
				'inputType' => 'text',
				'default' => $intDefaultStart,
				'eval' => array('rgxp' => 'date', 'datepicker'=>true, 'class' => 'tl_text')
			),

			$this->getFieldName('end') => array
			(
				'name' => $this->getFieldName('end'),
				'label' => &$GLOBALS['TL_LANG']['MSC']['reportEnd'],
				'inputType' => 'text',
				'default' => $intDefaultEnd,
				'eval' => array('rgxp' => 'date', 'datepicker'=>true, 'class' => 'tl_text')
			),

		);

		return $arrFields;
	}

}