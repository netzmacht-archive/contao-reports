<?php

$GLOBALS['TL_DCA']['tl_report_filter'] = array
(
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_reports',
		'enableVersioning'            => true,
		'sortable'                    => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index'
			)
		)
	),

	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('sorting'),
			'headerFields'            => array('title', 'tableName'),
			'flag'                    => 11,
			'disableGrouping'         => true,
			'child_record_callback'   => array('\Netzmacht\\Reports\\DataContainer\\ReportFilterDataContainer', 'listFilter'),
		),

		'label' => array
		(
			'fields'                  => array('name'),
			'format'                  => '%s'
		),

		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_report_filter']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),

			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_report_filter']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_report_filter']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_report_filter']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_report_filter']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	'palettes' => array
	(
		'__selector__' => array('type'),
	),

	'metapalettes' => array
	(
		'default' => array
		(
			'name'                    => array('name', 'type'),
		),
		'yesNo extends default' => array
		(
			'config'                  => array('field', 'defaultChecked'),
		),
		'timeFrame extends default' => array
		(
			'config' => array('startField', 'defaultStart', 'endField', 'defaultEnd'),
		),
	),

	'metasubselectpalettes' => array
	(
		'type' => array
		(
			//'yesNo' => array('field', 'defaultChecked'),
			//'timeFrame' => array('startField', 'defaultStart', 'endField', 'defaultEnd'),
		),
	),

	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),

		'pid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL",
			'foreignKey'              => 'tl_reports.title',
			'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
		),

		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL"
		),

		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),

		'name' => array
		(
			'label'                   => $GLOBALS['TL_LANG']['tl_report_filter']['name'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple' => false, 'mandatory' => false, 'submitOnChange' => false, 'tl_class' => 'w50'),
			'sql' => "varchar(64) NOT NULL default ''"
		),

		'type' => array
		(
			'label'                   => $GLOBALS['TL_LANG']['tl_report_filter']['type'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('Netzmacht\\Reports\\DataContainer\\ReportFilterDataContainer', 'getFilterNames'),
			'reference'               => $GLOBALS['TL_LANG']['tl_report_filter'],
			'eval'                    => array('multiple' => false, 'mandatory' => false, 'submitOnChange' => true, 'chosen'=>true, 'includeBlankOption' => true, 'tl_class' => 'w50'),
			'sql' => "varchar(64) NOT NULL default ''"
		),

		'field' => array
		(
			'label'                   => $GLOBALS['TL_LANG']['tl_report_filter']['field'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('Netzmacht\\Reports\\DataContainer\\ReportFilterDataContainer', 'getFieldNames'),
			'eval'                    => array('multiple' => false, 'mandatory' => false, 'submitOnChange' => false, 'chosen'=>true, 'tl_class' => 'w50'),
			'sql' => "varchar(64) NOT NULL default ''"
		),

		'startField' => array
		(
			'label'                   => $GLOBALS['TL_LANG']['tl_report_filter']['startField'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('Netzmacht\\Reports\\DataContainer\\ReportFilterDataContainer', 'getFieldNames'),
			'eval'                    => array('multiple' => false, 'mandatory' => false, 'submitOnChange' => false, 'chosen'=>true, 'tl_class' => 'w50'),
			'sql' => "varchar(64) NOT NULL default ''"
		),

		'endField' => array
		(
			'label'                   => $GLOBALS['TL_LANG']['tl_report_filter']['endField'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('Netzmacht\\Reports\\DataContainer\\ReportFilterDataContainer', 'getFieldNames'),
			'eval'                    => array('multiple' => false, 'mandatory' => false, 'submitOnChange' => false, 'chosen'=>true, 'tl_class' => 'w50'),
			'sql' => "varchar(64) NOT NULL default ''"
		),

		'defaultChecked' => array
		(
			'label'                   => $GLOBALS['TL_LANG']['tl_report_filter']['defaultChecked'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('multiple' => false, 'mandatory' => false, 'submitOnChange' => false, 'tl_class' => 'w50 m12'),
			'sql' => "char(3) NULL default ''"
		),

		'defaultStart' => array
		(
			'label'                   => $GLOBALS['TL_LANG']['tl_report_filter']['defaultStart'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('today', 'weekStart', 'monthStart', 'yearStart'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_report_filter']['times'],
			'eval'                    => array('multiple' => false, 'mandatory' => false, 'includeBlankOption' => true, 'tl_class' => 'w50'),
			'sql' =>  "varchar(64) NOT NULL default ''"
		),

		'defaultEnd' => array
		(
			'label'                   => $GLOBALS['TL_LANG']['tl_report_filter']['defaultEnd'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('today', 'weekEnd', 'monthEnd', 'yearEnd'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_report_filter']['times'],
			'eval'                    => array('multiple' => false, 'mandatory' => false, 'includeBlankOption' => true, 'tl_class' => 'w50'),
			'sql' =>  "varchar(64) NOT NULL default ''"
		),
	),

);