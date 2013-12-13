<?php

defined('_JEXEC') or die;

class CatalogueTableSections extends JTable
{
	/**
	 * Constructor
	 *
	 * @since	1.5
	 */
	public function __construct(&$_db)
	{
		parent::__construct('#__catalogue_section', 'id', $_db);
	}
	
}