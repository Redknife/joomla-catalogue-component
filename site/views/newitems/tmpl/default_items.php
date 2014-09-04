<?php defined('_JEXEC') or die; ?>
<h2>Новинки</h2>
<ul class="unstyled">
<?php foreach ($this->items as $item) : ?>
        <li class="one-item span3 white-box">
        <?php $ilink = JRoute::_( 'index.php?option=com_catalogue&view=item&sid='.$item->sectionid.'&cid='.$item->categoryid.'&id='.$item->id.'&Itemid=110' ); ?>
                <a href="<?php echo $ilink; ?>" class="item-link">
                        <img src="<?php $imgpath = CatalogueHelper::createThumb($item->id, $item->item_image, 218, 203); if($imgpath){ echo $imgpath;} else{ echo '/templates/blank_j3/img/imgholder.jpg'; }  ?>"/>
                        <p class="item-head"><?php echo $item->item_name; ?></p>
                </a>
                <div class="info">
                        <p class="item-desc">
                                <?php echo mb_substr(strip_tags($item->item_shortdesc),0,65).' ...'; ?>
                        </p>
                        <span class="item-price"><?php echo $item->price.' руб.'; ?></span>
                      <?php if (!CatalogueHelper::inCart($item->id)) : ?>
                        <a href="index.php?option=com_catalogue&task=order&orderid=<?php echo $item->id; ?>&Itemid=114&backuri=<?php echo base64_encode(JURI::current()); ?>" class="addToCart">Заказать</a></td>
                <?php else : ?>
                        <a id="b<?php echo $item->id; ?>" data-id="<?php echo $item->id; ?>" class="addToCart inCart">В корзине</a>
                <?php endif; ?>

                </div>
        </li>
<?php endforeach; ?>
</ul>
