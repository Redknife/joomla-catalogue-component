<?php defined('_JEXEC') or die; 

	$app = JFactory::getApplication();
	$menuitem   = $app->getMenu()->getActive(); // get the active item
	$menuparams = $menuitem->params; // get menu params

	$supersection = $this->state->get('supersection.object');

	$params = $app->getParams()->toArray();
	if((int)$params['supersection_tmpl'] == 3){
		$layout_type = (int)$menuitem->query['layout_type']; // get the layout type
	}
	else{
		$layout_type = (int)$params['supersection_tmpl'];
	}
?>
<?php if($menuparams->get('show_page_heading')): ?>
<div class="page-header">
	<h1><?php echo $menuitem->title; ?></h1>
</div>
<?php else: ?>
<div class="page-header">
	<h1><?php echo $supersection->supersection_name; ?></h1>
</div>
<?php endif; ?>
<div class="catalogue-wrapper">
	<div class="supersection-wrapper">
		<?php 
			if($layout_type == 1){
				echo $this->loadTemplate('items_rows');
			}
			else{
				echo $this->loadTemplate('items_grid');
			}
		?>
	</div>
 </div>