<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Table
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
  */

// No direct access
defined('JPATH_BASE') or die();

/**
 * Menu Types table
 *
 * @package 	Joomla.Framework
 * @subpackage	Table
 * @since		1.5
 */
class JTableMenuTypes extends JTable
{
	/** @var int Primary key */
	protected $id					= null;
	/** @var string */
	protected $menutype			= null;
	/** @var string */
	protected $title				= null;
	/** @var string */
	protected $description		= null;

	/**
	 * Constructor
	 *
	 * @access protected
	 * @param database A database connector object
	 */
	protected function __construct(&$db)
	{
		parent::__construct('#__menu_types', 'id', $db);
	}

	/**
	 * @return boolean
	 */
	public function check()
	{
		$this->menutype = JFilterOutput::stringURLSafe($this->menutype);
		if (empty($this->menutype)) {
			$this->setError("Cannot save: Empty menu type");
			return false;
		}

		// correct spurious data
		if (trim($this->title) == '') {
			$this->title = $this->menutype;
		}

		$db		=& JFactory::getDBO();

		// check for unique menutype for new menu copy
		$query = 'SELECT menutype' .
				' FROM #__menu_types';
		if ($this->id) {
			$query .= ' WHERE id != '.(int) $this->id;
		}

		$db->setQuery($query);
		try {
			$menus = $db->loadResultArray();
		} catch(JException $e) {
			$this->setError($e, true);
			return false;
		}

		foreach ($menus as $menutype)
		{
			if ($menutype == $this->menutype)
			{
				$this->setError("Cannot save: Duplicate menu type '{$this->menutype}'");
				return false;
			}
		}

		return true;
	}
}

