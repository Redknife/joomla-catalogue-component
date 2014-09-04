<?php defined('_JEXEC') or die; 

	$app = JFactory::getApplication();
	$menuitem   = $app->getMenu()->getActive(); // get the active item
	$menuparams = $menuitem->params; // get menu params
?>
<?php if($menuparams->get('show_page_heading')): ?>
<div class="page-header">
	<h1><?php echo $menuitem->title; ?></h1>
</div>
<?php endif; ?>
<div class="catalogue-wrapper">
	<div class="supersections-wrapper">
		<?php 
			$layout_type = (int)$menuitem->query['layout_type']; // get the layout type
			if($layout_type == 1){
				echo $this->loadTemplate('items_rows');
			}
			else{
				echo $this->loadTemplate('items_grid');
			}
		?>
	</div>
 </div>