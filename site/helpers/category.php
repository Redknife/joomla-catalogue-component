<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_catalogue
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Content Component Category Tree
 *
 * @package     Joomla.Site
 * @subpackage  com_content
 * @since       1.6
 */
class CatalogueCategories extends JCategories
{
    public function __construct($options = array())
    {
        $options['table'] = '#__catalogue_item';
        $options['extension'] = 'com_catalogue';
        $options['field'] = 'category_id';

        parent::__construct($options);
    }
}
