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
 * Class ReportFilterInterface
 * @package Netzmacht\Reports\Filter
 */
interface ReportFilterInterface
{
	/**
	 * @param \Contao\HasteForm $objForm
	 * @return mixed false if no sql is given
	 */
	public function getSQL(\Contao\HasteForm $objForm);


	/**
	 * get value for the sql
	 * @param \Contao\HasteForm $objForm
	 * @return mixed
	 */
	public function getSQLValues(\Contao\HasteForm $objForm);


	/**
	 * creates an array of fields which are needed for this filter
	 * @return array
	 */
	public function getFieldDefinitions();

}