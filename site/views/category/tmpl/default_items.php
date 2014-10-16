<?php defined('_JEXEC') or die;

$supersection_id = $this->state->get('supersection.id');
$section_id = $this->state->get('section.id');
$category_id = $this->state->get('category.id');

$params = $this->state->get('params');

$addprice = $params->get('addprice', 0);
$slice_desc = $params->get('slice_desc', 0);
$slice_len = $params->get('short_desc_len', 150);
$img_width = $params->get('img_width', 220);
$img_height = $params->get('img_height', 155);

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));


?>

<div class="catalogue-category-items clearfix">
    <?php if (!empty($this->items[0]->category_description)): ?>
        <div class="category-desc-wrapper">
            <?php echo $this->items[0]->category_description; ?>
        </div>
    <?php endif; ?>
    <?php if ($this->pagination->getPagesLinks()): ?>


        <div class="pagination">
            <span>Сортировать по цене:</span>
            <?php if ($listOrder == 'itm.price' && $listDirn == 'asc') : ?>
                <span class="sort">Сначала дешевые</span>
                <a href="#" class="sort" onclick="orderTable('itm.price', 'desc'); return false;">Сначала дорогие</a>
            <?php else : ?>
                <a href="#" class="sort" onclick="orderTable('itm.price', 'asc'); return false;">Сначала дешевые</a>
                <span class="sort">Сначала дорогие</span>
            <?php endif; ?>

            <?php echo $this->pagination->getPagesLinks(); ?>
        </div>
    <?php endif; ?>
    <div id="items-wrapper">


        <?php foreach ($this->items as $k => $item) : ?>
            <?php $techs = json_decode($item->techs); ?>
            <?php
            $ilink = JRoute::_(CatalogueHelperRoute::getItemRoute($item->id, $item->category_id));
            if (!empty($item->item_image)) {
                $src = CatalogueHelper::createThumb($item->id, $item->item_image, $img_width, $img_height, 'min');
            } else $src = '/templates/blank_j3/img/imgholder.png';
            ?>

            <div class="catalogue-one-item white-box" itemscope="" itemtype="http://schema.org/Product">
                <div class="catalogue-one-item-img <?php if ($item->item_sale) echo 'discount-label'; ?>">
                    <a href="<?php echo $ilink; ?>" title="<?php echo $item->item_name; ?>">
                        <img src="<?php echo $src ?>" title="<?php echo $item->item_name; ?>"
                             alt="<?php echo $item->item_name; ?>" width="<?php echo $img_width; ?>px"
                             height="<?php echo $img_height; ?>px"
                             style="width: <?php echo $img_width; ?>px;height: <?php echo $img_height; ?>px"
                             itemprop="image"/>
                    </a>
                </div>
                <div class="catalogue-one-item-desc">
                    <h5 itemprop="name">
                        <a class="product-name" href="<?php echo $ilink; ?>" title="<?php echo $item->item_name; ?>"
                           itemprop="url"><?php echo $item->item_name; ?></a>
                    </h5>

                    <div class="item-shortdesc">
                        <?php //if(!empty($item->item_shortdesc)) echo $item->item_shortdesc; ?>
                        <?php if (!empty($techs)): ?>
                            <ul>
                                <?php foreach ($techs as $tech): if ((int)$tech->show_short): ?>
                                    <li>
                                        <span class="gray-text"><?php echo $tech->name; ?>
                                            : </span><?php echo $tech->value; ?>
                                    </li>
                                <?php endif; endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <?php if (!$item->item_sale): ?>
                        <div class="item-price-wrapper">
                            <p class="item-price" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                                <?php if ($item->price) {
                                    echo number_format($item->price, 0, '.', ' ') . ' ' . $params->get('catalogue_currency', 'руб.');
                                } ?>
                                <meta itemprop="priceCurrency" content="0">
                            </p>
                        </div>
                    <?php else: ?>
                        <?php $new_price = $item->price - (($item->price / 100) * $item->item_sale); ?>
                        <div class="item-price-wrapper">
                            <p class="item-old-price" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                                <?php echo number_format($item->price, 0, '.', ' '); ?>
                                <meta itemprop="priceCurrency" content="0">
                            </p>
                            <p class="item-price" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                                <?php echo number_format($new_price, 0, '.', ' ') . ' ' . $params->get('catalogue_currency', 'руб.'); ?>
                                <meta itemprop="priceCurrency" content="0">
                            </p>
                        </div>
                    <?php endif; ?>
                    <a data-toggle="modal" data-target="#fastOrderModal" data-item="<?php echo $item->id; ?>"
                       class="orange-btn one-click-order cat-one-click">Купить в 1 клик</a>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
    <div class="clearfix"></div>
    <?php if ($this->pagination->getPagesLinks()): ?>
        <div class="pagination">
            <?php echo $this->pagination->getPagesLinks(); ?>
        </div>
    <?php endif; ?>
</div>
  