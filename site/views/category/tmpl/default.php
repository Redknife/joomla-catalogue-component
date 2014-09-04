<?php defined('_JEXEC') or die;
JHtml::_('behavior.caption');

$app = JFactory::getApplication();

$jinput = $app->input;
$view = $this->getName();
$layout = $this->getLayout();

$menu = $this->menu;
?>
<div class="catalogue-<?php echo $view ?>-<?php echo $layout ?>">

	<div class="page-header">
		<h1><?php echo $this->category->title; ?></h1>
	</div>


	<?php $cat_child = $this->category->getChildren(); if(!empty($cat_child)): ?>
	<div class="subcategories">
		<?php echo $this->loadTemplate('subcategories'); ?>
	</div>
	<?php endif; ?>

	<?php //if(!empty($this->items)):
	if(empty($cat_child)): ?>
	<form action="<?php echo JRoute::_(CatalogueHelperRoute::getCategoryRoute($this->state->get('category.id'))); ?>" method="post" id="catalogueForm">
		<div class="catalogue-items">
			<div class="filters">
			</div>
			<?php echo $this->loadTemplate('items'); ?>
		</div>

		<div class="pagination"><?php if($this->pagination->getPagesLinks()) echo '<span>Страницы:</span>'; echo $this->pagination->getPagesLinks(); ?></div>

		<input type="hidden" name="option" value="com_catalogue" />
		<input type="hidden" name="task" value="setFilter" />
	</form>
	<?php endif; ?>
</div>