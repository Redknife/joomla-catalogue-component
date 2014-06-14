<?php defined('_JEXEC') or die;

?>


<div class="row-fluid">
	<h3>Категории</h3>
	<ul class="unstyled">
	<?php foreach ($this->category->getChildren() as $category) : ?>
		<?php $category->params = new JRegistry($category->params); ?>
			<?php $clink = CatalogueHelperRoute::getCategoryRoute($category->id, $category->language); ?>
	        <li class="span2">
	        	<a href="<?php echo $clink; ?>" class="item-link">
	                <img src="<?php echo $category->params->get('image'); ?>"/>
	                <p class="item-head"><?php echo $category->title; ?></p>
	            </a>
	        </li>
	<?php endforeach; ?>
	</ul>
</div>
<hr/>
<div class="clearfix"></div>
