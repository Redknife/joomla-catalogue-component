<?php defined('_JEXEC') or die;

JHtml::_('behavior.caption');


$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));


$app = JFactory::getApplication();

$jinput = $app->input;
$view = $this->getName();
$layout = $this->getLayout();
$params = $this->state->get('params');
$menu = $this->menu;
?>
<div class="catalogue-<?php echo $view ?>-<?php echo $layout ?>">

    <div class="page-header">
        <h1><?php echo $this->category->title; ?></h1>
    </div>

    <?php //$cat_child = $this->category->getChildren(); if(!empty($cat_child)): ?>
    <!-- <div class="subcategories"> -->
    <?php //echo $this->loadTemplate('subcategories'); ?>
    <!-- </div> -->
    <?php //endif; ?>

    <?php if (!empty($this->items)): ?>
        <form
            action="<?php echo JRoute::_(CatalogueHelperRoute::getCategoryRoute($this->state->get('category.id'))); ?>"
            method="post" id="catalogueForm">

            <?php echo $this->loadTemplate('filters'); ?>

            <?php echo $this->loadTemplate('items'); ?>

            <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
            <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
            <input type="hidden" name="option" value="com_catalogue"/>
        </form>
    <?php else: ?>
        <p>Товары не найдены</p>
    <?php endif; ?>

    <!-- Modal -->
    <div class="modal fade" id="fastOrderModal" tabindex="-1" role="dialog" aria-labelledby="fastOrderModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="#" class="close" data-dismiss="modal" aria-hidden="true">×</a>
                </div>
                <div class="modal-body">
                    <div class="form-wrapper">
                        <div class="form-bg">
                            <form action="<?php echo JRoute::_('index.php?option=com_catalogue'); ?>" method="POST"
                                  id="fastOrderForm" class="mail-form form-validate">
                                <div class="form-lables">
                                    <div class="controlls">
                                        <input type="text" placeholder="Ваш E-mail" data-validation="email"
                                               class="field required" value="" name="email"/>
                                    </div>
                                    <div class="controlls">
                                        <input type="text" placeholder="Ваш телефон" data-validation="custom"
                                               data-validation-regexp="^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$"
                                               class="field required" value="" name="phone"/>
                                    </div>
                                </div>
                                <div>
                                    <a href="#" class="orange-btn order-send order-btn" id="catFastOrder"
                                       value="Validate"><?php echo $params->get('cart_form_btn', 'Заказать') ?></a>
                                </div>
                                <?php echo JHtml::_('form.token'); ?>
                                <input type="hidden" name="id" value="">
                                <input type="hidden" name="task" value="cart.send">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>