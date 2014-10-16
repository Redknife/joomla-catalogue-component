<?php defined('_JEXEC') or die;
$app = JFactory::getApplication();
$params = $app->getParams();
?>

<div class="catalogue-categories">
    <div class="row">
        <?php foreach ($this->category->getChildren() as $category) : ?>
            <?php $category->params = new JRegistry($category->params); ?>
            <?php $clink = CatalogueHelperRoute::getCategoryRoute($category->id, $category->language); ?>
            <?php $img = json_decode($category->params)->image; ?>
            <div class="span4">

                <div class="item-image-wrapper">
                    <a href="<?php echo $clink; ?>">
                        <img src="<?php echo $img; ?>"/>

                        <div class="item-link-wrapper">
                            <span class="item-link"><?php echo $category->title; ?></span>
                        </div>
                    </a>
                </div>
                <div class="item-desc-wrapper">
                    <?php echo $category->description; ?>
                </div>
                <div class="item-price-wrapper">
                    <p>
                        <span class="price-name">Цена: </span>
                        <span class="item-price"><?php if ($category->min_price && $category->max_price) {
                                echo 'от ' . number_format($category->min_price, 0, '.', ' ') . ' до ' . number_format($category->max_price, 0, '.', ' ') . ' ' . $params->get('catalogue_currency');
                            } else {
                                echo "по запросу";
                            } ?></span>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="clearfix"></div>
</div>