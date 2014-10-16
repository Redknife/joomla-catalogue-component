<?php
defined('_JEXEC') or die;
JHtml::_('behavior.caption');
JHtml::_('bootstrap.tooltip');

$category_name = $this->state->get('category.name');
$category_desc = $this->state->get('category.desc');
?>

<div class="catalogue-items" id="catalogue">
    <div class="page-header">
        <h1 class="catalogue-head"><?php echo $category_name; ?> </h1>
        <?php if (!empty($this->items)) : ?>
        <ul class="unstyled">
            <?php foreach ($this->items as $item) : ?>
                <?php var_dump($item); ?>
            <?php endforeach; ?>
            <?php else : ?>

            <?php
            endif; ?>
    </div>

</div>