<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2014
 * @package    clipboard
 * @license    GNU/LGPL
 * @filesource
 */

class ClipboardAlias extends \Backend
{
	/**
	 * @var BackendUser
	 */
	protected $user;

	/**
	 * __construct
	 */
	public function __construct()
	{
		parent::__construct();
		$this->user = BackendUser::getInstance();
	}

	/**
	 * Callback for the clipboard for generating the alis.
	 *
	 * @param        $dc              The current DC driver
	 *
	 * @param array  $arrSet          The original set of data from the XML data
	 *
	 * @param        $varValue        The current values from other callbacks
	 *
	 * @param        $objDatabase     The database helper object
	 *
	 * @param string $strTable        The name of the table
	 *
	 * @param int    $intLastInsertId The id of the insert tag.
	 *
	 * @return null|string Return the new alias.
	 */
	public function generateAlias($dc, $arrSet, $varValue, $objDatabase, $strTable, $intLastInsertId)
	{
		if ($this->user->clipboard_alias_mode == 'generate')
		{
			$varValue = '';

			// Trigger the save_callback
			if (is_array($GLOBALS['TL_DCA'][$strTable]['fields']['alias']['save_callback']))
			{
				foreach ($GLOBALS['TL_DCA'][$strTable]['fields']['alias']['save_callback'] as $callback)
				{
					$this->import($callback[0]);
					$varValue = $this->$callback[0]->$callback[1]($varValue, $dc);
				}
			}

			return $varValue;
		}
		elseif ($this->user->clipboard_alias_mode == 'suffix')
		{
			$strSuffix = ($GLOBALS['TL_HOOKS']['clipboard_alias_suffix']) ? $GLOBALS['TL_HOOKS']['clipboard_alias_suffix'] : 'copy';

			// Build alias with suffix.
			$varValue = str_replace(array('\'', '"'), array('', ''), $arrSet['alias']) . $strSuffix;

			// Check if we know this alias already.
			for ($i = 1; $i < 100; $i++)
			{
				if ($this->aliasExists('tl_page', standardize($varValue)))
				{
					$varValue = $varValue . '-' . $i;
				}
				else
				{
					break;
				}
			}

			return standardize($varValue);
		}

		return null;
	}

	/**
	 * Check if an alias already exists.
	 *
	 * @param string $strTable Name of table
	 *
	 * @param string $strAlias Alias to check
	 *
	 * @return bool True => yep | False => nope
	 */
	protected function aliasExists($strTable, $strAlias)
	{
		$objResult = \Database::getInstance()
			->prepare('SELECT count(*) as mycount FROM ' . $strTable . ' WHERE alias=?')
			->execute($strAlias);

		if ($objResult->mycount == 0)
		{
			return false;
		}

		return true;
	}
} 