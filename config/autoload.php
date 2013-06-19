<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Reports
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Netzmacht',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Netzmacht\Reports\Form'                                    => 'system/modules/reports/classes/Form.php',
	'Netzmacht\Reports\Translator'                              => 'system/modules/reports/classes/Translator.php',
	'Netzmacht\Reports\Filter\ReportFilterTimeFrame'            => 'system/modules/reports/classes/ReportFilterTimeFrame.php',
	'Netzmacht\Reports\Filter\ReportFilter'                     => 'system/modules/reports/classes/ReportFilter.php',
	'Netzmacht\Reports\Filter\ReportFilterYesNo'                => 'system/modules/reports/classes/ReportFilterYesNo.php',
	'Netzmacht\Reports\DataContainer\ReportFilterDataContainer' => 'system/modules/reports/classes/ReportFilterDataContainer.php',
	'Netzmacht\Reports\DataContainer\ReportsDataContainer'      => 'system/modules/reports/classes/ReportsDataContainer.php',
	'Netzmacht\Reports\Filter\ReportFilterInterface'            => 'system/modules/reports/classes/ReportFilterInterface.php',
	'Netzmacht\Reports\Config\ReportsConfig'                    => 'system/modules/reports/classes/ReportsConfig.php',

	// Modules
	'Netzmacht\Reports\Module\ReportsModule'                    => 'system/modules/reports/modules/ReportsModule.php',

	// Models
	'ReportsModel'                                              => 'system/modules/reports/models/ReportsModel.php',
	'ReportFilterModel'                                         => 'system/modules/reports/models/ReportFilterModel.php',

	// Vendor
	'Contao\HasteForm'                                          => 'system/modules/reports/vendor/haste/HasteForm.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'be_report'          => 'system/modules/reports/templates',
	'be_report_chooser'  => 'system/modules/reports/templates',
	'form_widget_wizard' => 'system/modules/reports/templates',
	'report_default'     => 'system/modules/reports/templates',
));
