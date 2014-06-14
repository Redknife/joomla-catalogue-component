<?php defined('_JEXEC') or die; 
JHtml::_('behavior.caption');

$app = JFactory::getApplication();

$jinput = $app->input;
$view = $this->getName();
$layout = $this->getLayout();


?>
<div class="catalogue-<?php echo $view ?>-<?php echo $layout ?>">
	<div class="page-header">
		<?php if ($this->params->get('show_category_title', 0)) : ?>
			<h1><?php echo $this->category->title; ?></h1>
		<?php endif; ?>
	</div>
	<div class="subcategories">
		<?php echo $this->loadTemplate('subcategories'); ?>
	</div>
	<form action="index.php" method="post" id="catalogueForm">
		<div class="catalogue-items">
			<div class="filters">
				
				<hr/>
			</div>
			<?php echo $this->loadTemplate('items'); ?>
		</div>
		
				<div class="pagination"><?php if($this->pagination->getPagesLinks()) echo '<span>Страницы:</span>'; echo $this->pagination->getPagesLinks(); ?></div>
			
		
		<input type="hidden" name="option" value="com_catalogue" />
		<input type="hidden" name="task" value="" />
	</form>

</div>