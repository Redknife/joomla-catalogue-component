<?php defined('_JEXEC') or die;

$supersection_id 	= $this->state->get('supersection.id');
$section_id 		= $this->state->get('section.id');
$category_id 		= $this->state->get('category.id');

$params = $this->state->get('params');

$addprice = $params->get('addprice', 0);
$slice_desc = $params->get('slice_desc', 0);
$slice_len = $params->get('short_desc_len', 150);
$img_width = $params->get('img_width', 0);
$img_height = $params->get('img_height', 0);

$num_columns = $params->get('num_columns', 3);
$span_suffix = 12 / $num_columns;

$price_cat = $this->state->get('filters.price_cat', 0);
?>
<noindex>
<div class="row">
  <div class="span9">
    <div id="catalogueFilters">
      <div class="row">
        <div class="span9">
          <div class="left-filter">
            <p class="filter-header">Выводить товары:</p>
            <ul class="unstyled filter-list">
              <li class="one-filter <?php if($price_cat == 0) echo 'active'; ?>">
                <input type="checkbox" name="price_cat" value="0">
                <a href="#" rel="nofollow" class="filter-select" >Все товары</a>
              </li>
              <li class="one-filter <?php if($price_cat == 1) echo 'active'; ?>">
                <input type="checkbox" name="price_cat" value="1">
                <a href="#" rel="nofollow" class="filter-select" >Бюджетные</a>
              </li>
              <li class="one-filter <?php if($price_cat == 2) echo 'active'; ?>">
                <input type="checkbox" name="price_cat" value="2">
                <a href="#" rel="nofollow" class="filter-select" >Эконом-класс</a>
              </li>
              <li class="one-filter <?php if($price_cat == 3) echo 'active'; ?>">
                <input type="checkbox" name="price_cat" value="3">
                <a href="#" rel="nofollow" class="filter-select" >Бизнес-класс</a>
              </li>
              <li class="one-filter <?php if($price_cat == 4) echo 'active'; ?>">
                <input type="checkbox" name="price_cat" value="4">
                <a href="#" rel="nofollow" class="filter-select" >Премиум</a>
              </li>
              <li class="one-filter <?php if($price_cat == 5) echo 'active'; ?>">
                <input type="checkbox" name="price_cat" value="5">
                <a href="#" rel="nofollow" class="filter-select" >Элитные</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</noindex>
<div class="row">
  <?php foreach ($this->items as $k => $item) : ?>
   <?php
   $ilink = JRoute::_(CatalogueHelperRoute::getItemRoute($item->id, $item->category_id));
   $src	= CatalogueHelper::createThumb($item->id, $item->item_image, $img_width, $img_height, 'min');
   if (!$src) $src = '/templates/blank_j3/img/imgholder.jpg';
   ?>
   <div class="span3">
     <div class="catalogue-item" itemscope="" itemtype="http://schema.org/Product">
      <a href="<?php echo $ilink; ?>" title="<?php echo $item->item_name; ?>">
        <img src="<?php echo $src ?>" title="<?php echo $item->item_name; ?>" alt="<?php echo $item->item_name; ?>" width="<?php echo $img_width; ?>px" height="<?php echo $img_height; ?>px" style="width: <?php echo $img_width; ?>px;height: <?php echo $img_height; ?>px" itemprop="image" />
      </a>
      <h5 itemprop="name">
       <a class="product-name" href="<?php echo $ilink; ?>" title="<?php if(strlen($item->item_shortname) != 0){ echo $item->item_shortname; } else{ echo $item->item_name; } ?>" itemprop="url"><?php if(strlen($item->item_shortname) != 0){ echo $item->item_shortname; } else{ echo $item->item_name; } ?></a>
     </h5>
     <p>
      <span class="price-name">Цена: </span>
      <span class="item-price"  itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
        <?php if($item->price){ echo 'от '.number_format($item->price, 0, '.', ' ').' '.$params->get('catalogue_currency', 'руб.'); } else{ echo 'по запросу'; } ?>
      </span>
      <meta itemprop="priceCurrency" content="0">
    </p>
    <p>
      <span class="price-name">Срок доставки: </span>
      <span class="item-price"><?php echo $item->item_count; ?></span>
    </p>
  </div>
</div>
<?php endforeach; ?>
</div>
