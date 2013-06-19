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
abstract class ReportFilter implements ReportFilterInterface
{

	/**
	 * @var \Model|null
	 */
	protected $objModel;


	/**
	 * @param $varIdOrModel
	 * @throws \Exception if invalid id is given
	 */
	public function __construct($varIdOrModel)
	{
		if(is_int($varIdOrModel))
		{
			$this->objModel = \ReportFilterModel::findByPk($varIdOrModel);
		}
		else
		{
			$this->objModel = $varIdOrModel;
		}


		if($this->objModel === null)
		{
			throw new \Exception(sprintf('Invalid filter id given. Filter ID "%s" does not exists', $varIdOrModel));
		}
	}


	/**
	 * generate field name combined with filter id so it will be a unique one
	 * @param $strName
	 * @return string
	 */
	protected function getFieldName($strName)
	{
		return $this->objModel->id . '_' . $strName;
	}

}
