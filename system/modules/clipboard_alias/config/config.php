<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2014
 * @package    clipboard
 * @license    GNU/LGPL
 * @filesource
 */


$GLOBALS['TL_HOOKS']['clipboard_alias_suffix'] = '-KOPIE';
$GLOBALS['TL_HOOKS']['clipboard_alias'][] = array('ClipboardAlias', 'generateAlias');