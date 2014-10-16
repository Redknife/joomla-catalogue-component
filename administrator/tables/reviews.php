<?php

defined('_JEXEC') or die;

class CatalogueTableReviews extends JTable
{

    public function __construct(&$_db)
    {
        parent::__construct('#__catalogue_item_review', 'id', $_db);
    }

}