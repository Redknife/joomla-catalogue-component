<?php

defined('_JEXEC') or die;

class CatalogueTableCategories extends JTable
{

	public function __construct(&$_db)
	{
		parent::__construct('#__catalogue_category', 'id', $_db);
	}
	
	public function store($updateNulls = false)
	{
		
		$result = parent::store($updateNulls);
		// Item
		$query_delete = 'DELETE FROM #__catalogue_links WHERE parent = '.$this->id;
		$this->_db->setQuery($query_delete);
		$this->_db->execute();
		
		
		$keyName = $this->getKeyName();
		$this->load($this->$keyName);
		
		$jform = JFactory::getApplication()->input->post->get('jform', array(), 'array');
		
		//Types of child and parent
		//1 - Item
		//2 - Category
		//3 - Section
		//4 - Supersection
		
		$cItems = $jform['child_item'];
		
		if (!empty($cItems))
		{
			$query_item = 'INSERT INTO #__catalogue_links (`ptype`,`ctype`,`parent`, `child`) VALUES ';
		
			foreach($cItems as $child)
			{
				$val_item[] = '(2,1,'.$this->id .','. $child.')';
			}
			$query_item .= implode(',', $val_item);
			$this->_db->setQuery($query_item);
			
			$this->_db->execute();
		}
		
		$cCategory = $jform['child_category'];
		
		if (!empty($cCategory))
		{
			$query_category = 'INSERT INTO #__catalogue_links (`ptype`,`ctype`,`parent`, `child`) VALUES ';
		
			foreach($cCategory as $child)
			{
				if( $this->id != $child )
					$val_category[] = '(2,2,'.$this->id .','. $child.')';
			}
			$query_category .= implode(',', $val_category);
			$this->_db->setQuery($query_category);
			if($val_category)
				$this->_db->execute();
		}
		
		$cSections = $jform['child_section'];
		
		if (!empty($cSections))
		{
			$query_sction = 'INSERT INTO #__catalogue_links (`ptype`,`ctype`,`parent`, `child`) VALUES ';
		
			foreach($cSections as $child)
			{
				$val_section[] = '(2,3,'.$this->id .','. $child.')';
			}
			$query_sction .= implode(',', $val_section);
			$this->_db->setQuery($query_sction);
			
			$this->_db->execute();
		}
		
		$cSupersections = $jform['child_supersection'];
		
		if (!empty($cSupersections))
		{
			$query_supersection = 'INSERT INTO #__catalogue_links (`ptype`,`ctype`,`parent`, `child`) VALUES ';
		
			foreach($cSupersections as $child)
			{
				$val_supersection[] = '(2,4,'.$this->id .','. $child.')';
			}
			$query_supersection .= implode(',', $val_supersection);
			$this->_db->setQuery($query_supersection);
			
			$this->_db->execute();
		}
		
		return $result;
	}

	
}