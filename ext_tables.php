<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

require_once( \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY).'config_inc.php' );
$tx_mwimagemap_extconf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['mwimagemap']);
include_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY).'class.tx_mwimagemap_ufunc.php');
include_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY).'class.tx_mwimagemap.php');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'static/pi1/', 'MW Imagemap');

//\TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA('tt_content');
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages,recursive';

// -------------------------------------------------------------------
// Use an image map with the Content-type "image", "image with Text"
// -------------------------------------------------------------------
if($tx_mwimagemap_extconf['disable_IMAGE'] == 0) {
$tempColumns = Array (
			'tx_mwimagemap' => Array (
			'label' => 'LLL:EXT:mwimagemap/locallang_db.php:tt_content.tx_mwimagemap',
			'exclude' => 1,											// CR by Stefan Galinski
			'config' => Array (
				'type' => 'select',
				'renderType' => 'selectSingle',
				'size' => '1',
				'itemsProcFunc' => 'tx_mwimagemap->main',
				'wizards' => array(
					'uproc' => array(
						'type' => 'userFunc',
						'userFunc' => 'tx_mwimagemap_ufunc->user_TCAform_procWizard',
						'params' => array(
							'tempid' => '###THIS_UID###'
						),
					),
				),
			),
		),
	);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns, 1);

$GLOBALS['TCA']['tt_content']['palettes']['mwimagemap'] = array( 'showitem' => 'tx_mwimagemap', 'canNotCollapse' => 1 );
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--palette--;LLL:EXT:mwimagemap/locallang_db.php:tx_mwimagemap;'.'mwimagemap', 'textpic,image');
}

// --------------------------------------------
// Flexform for directly inserting the plugin.
// --------------------------------------------
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='pi_flexform';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($_EXTKEY.'_pi1', '
<T3DataStructure>
	<ROOT>
		<type>array</type>
		<el>
		<imagemap>
			<TCEforms>
			<label>LLL:EXT:mwimagemap/locallang_db.php:tt_content.tx_select_imagemap</label>
			<config>
				<type>select</type>
					<items type="array">
						'.mwimagemap_getitems().'
					</items>
				<maxitems>1</maxitems>
				<size>1</size>
				<disableNoMatchingValueElement>1</disableNoMatchingValueElement>
			</config>
			</TCEforms>
		</imagemap>
		</el>
	</ROOT>
</T3DataStructure>');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(Array('LLL:EXT:mwimagemap/locallang_db.php:tt_content.list_type_pi1', $_EXTKEY.'_pi1'), 'list_type');

if (TYPO3_MODE=='BE')	{
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule('file', 'txmwimagemapM1', '', \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY)."mod1/");
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_mwimagemap_pi1_wizicon'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY).'pi1/class.tx_mwimagemap_pi1_wizicon.php';
}
?>
