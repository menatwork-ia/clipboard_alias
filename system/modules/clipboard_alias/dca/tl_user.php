<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2014
 * @package    clipboard
 * @license    GNU/LGPL 
 * @filesource
 */

/**
 * Palettes
 */
foreach ($GLOBALS['TL_DCA']['tl_user']['palettes'] as $key => $row)
{
    if ($key == '__selector__')
    {    
        continue;
    }

    $arrPalettes = explode(";", $row);

	foreach($arrPalettes as $intKey => $strPalette)
	{
		if(stripos($strPalette, '{clipboard_legend}') !== false)
		{
			$arrPalettes[$intKey] = $strPalette . ',clipboard_alias_mode';
		}
	}
    
    $GLOBALS['TL_DCA']['tl_user']['palettes'][$key] = implode(";", $arrPalettes);
}

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_user']['fields']['clipboard_alias_mode'] = array(
	'label'          => &$GLOBALS['TL_LANG']['tl_user']['clipboard_alias_mode'],
	'inputType'      => 'select',
	'options'        => array('no_change', 'generate', 'suffix'),
	'reference'      => $GLOBALS['TL_LANG']['tl_user']['clipboard_alias_value'],
	'eval'           => array('tl_class' => 'clr')
);