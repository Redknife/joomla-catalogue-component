<?php defined('_JEXEC') or die;

$supersection_id = $this->state->get('supersection.id');
$section_id = $this->state->get('section.id');
$category_id = $this->state->get('category.id');

$params = $this->state->get('params');

$addprice = $params->get('addprice', 0);
$slice_desc = $params->get('slice_desc', 0);
$slice_len = $params->get('short_desc_len', 0);
$img_width = $params->get('img_width', 0);
$img_height = $params->get('img_height', 0);

?>

<ul class="unstyled">
    <?php foreach ($this->items as $item) : ?>
        <li class="one-item white-box">
            <?php
            $ilink = JRoute::_('index.php?option=com_catalogue&view=item&ssid=' . $supersection_id . '&sid=' . $section_id . '&cid=' . $category_id . '&id=' . $item->id);
            $src = CatalogueHelper::createThumb($item->id, $item->item_image, $img_width, $img_height);
            if (!$src) $src = '/templates/blank_j3/img/imgholder.jpg';
            ?>

            <a href="<?php echo $ilink; ?>" class="item-link">
                <img src="<?php echo $src ?>"/>

                <p class="item-head"><?php echo $item->item_name; ?></p>
            </a>

            <div class="info">
                <p class="item-desc">
                    <?php if ($slice_desc) : ?>
                        <?php echo mb_substr(strip_tags($item->item_description), 0, $slice_len) . ' ...'; ?>
                    <?php else : ?>
                        <?php echo $item->item_description ?>
                    <?php endif; ?>
                </p>
                <span class="item-price"><?php echo $item->price . ' руб.'; ?></span>
                <?php if (!CatalogueHelper::inCart($item->id)) : ?>
                    <a href="index.php?option=com_catalogue&task=order&orderid=<?php echo $item->id; ?>&backuri=<?php echo base64_encode(JURI::current()); ?>"
                       class="addToCart">Заказать</a></td>
                <?php else : ?>
                    <a id="b<?php echo $item->id; ?>" data-id="<?php echo $item->id; ?>" class="addToCart inCart">В
                        корзине</a>
                <?php endif; ?>

            </div>
        </li>
    <?php endforeach; ?>
</ul>
