<?php defined('_JEXEC') or die;
JHtml::_('behavior.caption');
$start = microtime(true);

$app = JFactory::getApplication();
$path = $app->getPathway();

$category_name = $this->state->get('category.name');
$category_desc = $this->state->get('category.description');

$jinput = $app->input;
$view = $this->getName();
$layout = $this->getLayout();

?>
<div class="catalogue-<?php echo $view ?>-<?php echo $layout ?>">

    <form action="index.php" method="post" id="catalogueForm">
        <div class="row">
            <div class="span9">
                <?php echo $this->loadTemplate('items'); ?>
            </div>
        </div>
        <div class="row">
            <div class="span9">
                <div class="pagination"><?php if ($this->pagination->getPagesLinks()) echo '<span>Страницы:</span>';
                    echo $this->pagination->getPagesLinks(); ?></div>
            </div>
        </div>
        <input type="hidden" name="option" value="com_catalogue"/>
        <input type="hidden" name="task" value=""/>
    </form>

</div>