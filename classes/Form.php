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

namespace Netzmacht\Reports;
use Contao\HasteForm;

class Form extends HasteForm
{
	/**
	 * Initialize the form, add datepicker support as well
	 * @param boolean
	 */
	public function initializeWidgets($blnForce=false)
	{
		// Return if the widgets have been already initialized
		if (count($this->arrWidgets) > 0 && !$blnForce)
		{
			return;
		}

		parent::initializeWidgets($blnForce);

		foreach($this->arrFields as $arrField)
		{
			if(isset($arrField['eval']['datepicker']))
			{
				$this->addDatePicker($arrField, $this->arrWidgets[$arrField['name']]);
			}
		}
	}


	/**
	 * @param array $arrField
	 * @param $objWidget
	 */
	protected function addDatePicker(array $arrField, $objWidget)
	{
		$rgxp = $arrField['eval']['rgxp'];
		$format = \Date::formatToJs($GLOBALS['TL_CONFIG'][$rgxp.'Format']);

		switch ($rgxp)
		{
			case 'datim':
				$time = ",\n      timePicker:true";
				break;

			case 'time':
				$time = ",\n      pickOnly:\"time\"";
				break;

			default:
				$time = '';
				break;
		}

		$wizard = ' <img src="assets/mootools/datepicker/' . DATEPICKER . '/icon.gif" width="20" height="20" alt="" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['datepicker']).'" id="toggle_' . $objWidget->id . '" style="vertical-align:-6px;cursor:pointer">
  <script>
  window.addEvent("domready", function() {
    new Picker.Date($$("#ctrl_' . $objWidget->id . '"), {
      draggable:false,
      toggle:$$("#toggle_' . $objWidget->id . '"),
      format:"' . $format . '",
      positionOffset:{x:-197,y:-182}' . $time . ',
      pickerClass:"datepicker_dashboard",
      useFadeInOut:!Browser.ie,
      startDay:' . $GLOBALS['TL_LANG']['MSC']['weekOffset'] . ',
      titleFormat:"' . $GLOBALS['TL_LANG']['MSC']['titleFormat'] . '"
    });
  });
  </script>';


		$objWidget->template = 'form_widget_wizard';
		$objWidget->wizard = $wizard;
	}

}