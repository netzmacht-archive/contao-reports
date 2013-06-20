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
 * Table tl_reports 
 */
$GLOBALS['TL_DCA']['tl_reports'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctables'                     => array('tl_report_filter'),
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('module'),
			'flag'                    => 1
		),
		'label' => array
		(
			'fields'                  => array('title', 'module', 'tableName'),
			'format'                  => '%s [%s, %s]'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_reports']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),

			'filter' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_reports']['filter'],
				'href'                => 'table=tl_report_filter',
				'icon'                => 'system/modules/reports/assets/filter.png'
			),

			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_reports']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_reports']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_reports']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Edit
	'edit' => array
	(
		'buttons_callback' => array()
	),

	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'general' => array('title'),
			'table' => array('module'),
			'description' => array('description'),
			'output' => array('template'),

		)

	),

	'metasubselectpalettes' => array
	(
		'module' => array
		(
			'!' => array('tableName')
		),

		'tableName' => array
		(
			'!' => array('fields', 'sortingFields')
		),
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),

		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),

		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_reports']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class' => 'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),

		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_reports']['description'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>false, 'tl_class' => 'clr', 'rte' => 'tinyMCE'),
			'sql'                     => "mediumtext NULL"
		),

		'module' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_reports']['module'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('Netzmacht\Reports\DataContainer\ReportsDataContainer', 'getModules'),
			'reference'               => &$GLOBALS['TL_LANG']['MOD'],
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'includeBlankOption' => true, 'submitOnChange' => true, 'tl_class' => 'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),

		'tableName' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_reports']['tableName'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('Netzmacht\Reports\DataContainer\ReportsDataContainer', 'getTables'),
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'includeBlankOption' => true, 'submitOnChange' => true, 'tl_class' => 'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),

		'fields' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_reports']['fields'],
			'exclude'                 => true,
			'inputType'               => 'multiColumnWizard',
			'eval'                    => array
			(
				'mandatory'=>true,
				'maxlength'=>255,
				'multiple' => true,
				'tl_class' => 'clr',
				'columnFields' => array
				(
					'name' => array
					(
						'label'                   => &$GLOBALS['TL_LANG']['tl_reports']['fieldName'],
						'exclude'                 => true,
						'inputType'               => 'select',
						'options_callback'        => array('Netzmacht\Reports\DataContainer\ReportsDataContainer', 'getTableFields'),
					),

					'default' => array
					(
						'label'                   => &$GLOBALS['TL_LANG']['tl_reports']['fieldDefault'],
						'exclude'                 => true,
						'inputType'               => 'checkbox',
						'eval'                    => array('style' => 'width:80px;'),
					)
				)
			),
			'sql'                     => "blob NULL"
		),

		'sortingFields' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_reports']['sortingFields'],
			'exclude'                 => true,
			'inputType'               => 'multiColumnWizard',
			'eval'                    => array
			(
				'mandatory'=>false,
				'maxlength'=>255,
				'multiple' => false,
				'tl_class' => 'clr',
				'columnFields' => array
				(
					'name' => array
					(
						'label'                   => &$GLOBALS['TL_LANG']['tl_reports']['sortingFieldName'],
						'exclude'                 => true,
						'inputType'               => 'select',
						'eval'                    => array('includeBlankOption' => true),
						'options_callback'        => array('Netzmacht\Reports\DataContainer\ReportsDataContainer', 'getTableFields'),
					),

					'direction' => array
					(
						'label'                   => &$GLOBALS['TL_LANG']['tl_reports']['sortingFieldDirection'],
						'exclude'                 => true,
						'inputType'               => 'select',
						'options'                 => array('asc', 'desc'),
						'reference'               => &$GLOBALS['TL_LANG']['tl_reports']['sortingOptions'],
						'eval'                    => array('style' => 'width:100px;'),
					)
				)
			),
			'sql'                     => "blob NULL"
		),

		'template' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_reports']['template'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('Netzmacht\Reports\DataContainer\ReportsDataContainer', 'getTemplates'),
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class' => 'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
	)
);
