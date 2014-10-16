<?php defined('_JEXEC') or die;
JHtml::_('behavior.caption');
require_once JPATH_SITE . DS . 'components' . DS . 'com_catalogue' . DS . 'helpers' . DS . 'cart.php';
$config = JFactory::getConfig();
$params = $this->state->get('params');

// $catalague_order_type = $params->get('catalague_order_type', 1);
// $addprice = $params->get('addprice', 0);
// $slice_desc = $params->get('slice_desc', 0);
// $slice_len = $params->get('short_desc_len', 150);
// $img_width = $params->get('img_width', 250);
// $img_height = $params->get('img_height', 250);

$item = $this->item;
$app = JFactory::getApplication();
$doc = JFactory::getDocument();

$img_width = $params->get('img_width', 220);
$img_height = $params->get('img_height', 155);
?>
<div id="item-open">
<div class="page-header">
    <h1 class="item-name"><?php echo $item->item_name; ?></h1>
</div>
<section class="open-item-top-block clearfix">
    <div class="top-left-block">
        <div class="open-item-img <?php if ($item->item_sale) echo 'discount-label'; ?>" id="zoom-gallery">
            <a href="<?php echo $item->item_image; ?>" data-source="<?php echo $item->item_image; ?>"
               title="<?php echo $item->item_image_desc; ?>">
                <img src="<?php echo CatalogueHelper::createThumb($item->id, $item->item_image, 380, 380, 'max'); ?>"
                     width="380px" height="380px">
            </a>
        </div>
        <div class="open-item-top-desc">
            <?php echo $item->item_description; ?>
        </div>
        <div class="open-item-socials">
            <h4>Понравился товар? Расскажите о нем друзьям!</h4>
            <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
            <div class="yashare-auto-init" data-yashareL10n="ru"
                 data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki" data-yashareTheme="counter"
                ></div>
        </div>
        <div class="open-item-delivery-desc">
            <a href="/delivery-and-payment.html">Информация о доставке и оплате товаров</a>
        </div>
    </div>
    <div class="top-right-block">
        <div class="open-item-top-order white-box">
            <h4>Подбор матраса</h4>

            <div class="open-item-price-wrap clearfix">
                <?php if (!$item->item_sale): ?>
                    <p class="item-price" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                        <?php if ($item->price) {
                            echo number_format($item->price, 0, '.', ' ') . ' ' . $params->get('catalogue_currency', 'руб.');
                        } ?>
                        <meta itemprop="priceCurrency" content="0">
                    </p>
                <?php else: ?>
                    <?php $new_price = $item->price - (($item->price / 100) * $item->item_sale); ?>
                    <div class="item-price-wrapper clearfix">
                        <p class="item-old-price" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                            <?php echo number_format($item->price, 0, '.', ' '); ?>
                            <meta itemprop="priceCurrency" content="0">
                        </p>
                        <p class="item-price" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                            <?php echo number_format($new_price, 0, '.', ' ') . ' ' . $params->get('catalogue_currency', 'руб.'); ?>
                            <meta itemprop="priceCurrency" content="0">
                        </p>
                    </div>
                    <div class="discount-sum-wrap">
                        <p>Экономия <span
                                class="bold-text"><?php echo number_format((($item->price / 100) * $item->item_sale), 0, '.', ' '); ?></span>
                            руб.</p>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (!CatalogueHelperCart::inCart($item->id)): ?>
                <a href="/index.php?option=com_catalogue&task=cart.add&tmpl=raw" item-id="<?php echo $item->id; ?>"
                   item-price="<?php echo $item->price; ?>" item-count="1" class="orange-btn add-to-card-btn"
                   id="addToCart">Положить в корзину
                    <div class="loader"></div>
                </a>
            <?php else: ?>
                <a class="orange-btn add-to-card-btn disable" id="addToCart">В корзине</a>
            <?php endif; ?>
            <a data-toggle="modal" data-target="#orderModal-<?php echo $item->id; ?>"
               class="orange-link one-click-order" id="fastOrder">Купить в 1 клик</a>
            <hr>
            <div class="bottom-order-info">
                <p class="order-info"><?php echo $params->get('order_info'); ?></p>

                <p class="manager-phone"><?php echo $params->get('manager_phone'); ?></p>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="orderModal-<?php echo $item->id; ?>" tabindex="-1" role="dialog"
                 aria-labelledby="orderModalLabel-<?php echo $item->id; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <a href="#" class="close" data-dismiss="modal" aria-hidden="true">×</a>
                        </div>
                        <div class="modal-body">
                            <div class="form-wrapper">
                                <div class="form-bg">
                                    <form action="<?php echo JRoute::_('index.php?option=com_catalogue'); ?>"
                                          method="POST" id="orderForm" class="mail-form form-validate">
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
                                            <a href="#" class="orange-btn order-btn order-send"
                                               value="Validate"><?php echo $params->get('cart_form_btn', 'Заказать') ?></a>
                                        </div>
                                        <?php echo JHtml::_('form.token'); ?>
                                        <input type="hidden" name="id" value="<?php echo $item->id; ?>">
                                        <input type="hidden" name="task" value="cart.send">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="open-item-tabs-block">
    <ul class="nav nav-tabs clearfix" role="tablist">
        <li class="active"><a href="#item-techs" role="tab" data-toggle="tab">Тех. характеристики</a></li>
        <li><a href="#item-manufacturer" role="tab" data-toggle="tab">О производителе</a></li>
        <li><a href="#item-reviews" role="tab" data-toggle="tab">Отзывы</a></li>
        <li><a href="#similar-items" role="tab" data-toggle="tab">Сопутствующие товары</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="item-techs">
            <?php //if(!empty($item->item_techdesc)) echo $item->item_techdesc; ?>
            <?php $techs = json_decode($item->techs);
            if (!empty($techs)): ?>
                <table class="table table-striped">
                    <tbody>
                    <?php foreach ($techs as $tech): ?>
                        <tr>
                            <td><?php echo $tech->name; ?></td>
                            <td><?php echo $tech->value; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <div class="tab-pane" id="item-manufacturer">
            <?php echo $item->manufacturer_description; ?>
        </div>
        <div class="tab-pane" id="item-reviews">
            <?php if (!empty($item->reviews)): ?>
                <ul class="unstyled reviews-list">
                    <?php foreach ($item->reviews as $review) : $date = JFactory::getDate($review->item_review_date); ?>
                        <li class="one-review white-box">
                            <div class="reviewer-info">
		              <span class="reviewer-name">
		                <?php echo $review->item_review_fio; ?>
		              </span>

                                <div class="review-rate-wrapper">
                                    <div class="star-rating">
                                        <span class="rate-stars star<?php echo $review->item_review_rate; ?>"></span>
                                    </div>
                                </div>
                                <p class="review-date">
                                    <?php echo JHTML::_('date', $review->item_review_date, JText::_('j F Y')); ?>
                                </p>
                            </div>
                            <div class="review-text-wrapper">
                                <p class="review-text">
                                    <?php echo strip_tags($review->item_review_text); ?>
                                </p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="review-form-wrapper white-box">
                    <form id="reviewForm" class="form-validate mail-form review-form has-validation-callback" action=""
                          method="post">
                        <div class="form-lables">
                            <div class="form-header">
                                <h4>
                                    Оставить отзыв
                                </h4>
                            </div>
                            <div class="controlls">
                                <span class="rating-head">Оцените нас:</span>
							<span class="star-rating">
							<input type="radio" name="jform[item_review_rate]" value="1"><i></i>
							<input type="radio" name="jform[item_review_rate]" value="2"><i></i>
							<input type="radio" name="jform[item_review_rate]" value="3"><i></i>
							<input type="radio" name="jform[item_review_rate]" value="4"><i></i>
							<input type="radio" name="jform[item_review_rate]" value="5"><i></i>
						</span>
                            </div>
                            <div class="controlls">
                                <input type="text" name="jform[item_review_fio]" id="item_review_fio" value=""
                                       placeholder="Ваше имя" data-validation="required" required=""
                                       aria-required="true">
                            </div>
                            <div class="controlls">
                                <textarea name="jform[item_review_text]" data-validation="required" class="required"
                                          placeholder="Ваш отзыв" aria-required="true" required="required"></textarea>
                            </div>
                        </div>
                        <a href="#" id="reviewSend" class="jbtn orange-btn">Отправить отзыв</a>
                        <input type="hidden" name="option" value="com_catalogue">
                        <input type="hidden" name="task" value="review.save">
                        <input type="hidden" name="jform[item_id]" value="<?php echo $item->id; ?>">
                        <?php echo JHtml::_('form.token'); ?>
                    </form>
                </div>
            <?php else: ?>
                <p class="pull-left">Отзывов нет</p>
                <div class="review-form-wrapper white-box">
                    <form id="reviewForm" class="form-validate mail-form review-form has-validation-callback" action=""
                          method="post">
                        <div class="form-lables">
                            <div class="form-header">
                                <h4>
                                    Оставить отзыв
                                </h4>
                            </div>
                            <div class="controlls">
                                <span class="rating-head">Оцените нас:</span>
							<span class="star-rating">
							<input type="radio" name="jform[item_review_rate]" value="1"><i></i>
							<input type="radio" name="jform[item_review_rate]" value="2"><i></i>
							<input type="radio" name="jform[item_review_rate]" value="3"><i></i>
							<input type="radio" name="jform[item_review_rate]" value="4"><i></i>
							<input type="radio" name="jform[item_review_rate]" value="5"><i></i>
						</span>
                            </div>
                            <div class="controlls">
                                <input type="text" name="jform[item_review_fio]" id="item_review_fio" value=""
                                       placeholder="Ваше имя" data-validation="required" required=""
                                       aria-required="true">
                            </div>
                            <div class="controlls">
                                <textarea name="jform[item_review_text]" data-validation="required" class="required"
                                          placeholder="Ваш отзыв" aria-required="true" required="required"></textarea>
                            </div>
                        </div>
                        <a href="#" id="reviewSend" class="jbtn orange-btn">Отправить отзыв</a>
                        <input type="hidden" name="option" value="com_catalogue">
                        <input type="hidden" name="task" value="review.save">
                        <input type="hidden" name="jform[item_id]" value="<?php echo $item->id; ?>">
                        <?php echo JHtml::_('form.token'); ?>
                    </form>
                </div>
            <?php endif; ?>
        </div>
        <div class="tab-pane" id="similar-items">
            <?php foreach ($item->assoc as $similar): ?>
                <?php
                $ilink = JRoute::_(CatalogueHelperRoute::getItemRoute($item->id, $item->category_id));
                if (!empty($item->item_image)) {
                    $src = CatalogueHelper::createThumb($item->id, $item->item_image, $img_width, $img_height, 'min');
                } else $src = '/templates/blank_j3/img/imgholder.png'; ?>
                <div class="catalogue-one-item white-box" itemscope="" itemtype="http://schema.org/Product">
                    <div class="catalogue-one-item-img">
                        <a href="<?php echo $ilink; ?>" title="<?php echo $similar->item_name; ?>">
                            <img src="<?php echo $src ?>" title="<?php echo $similar->item_name; ?>"
                                 alt="<?php echo $similar->item_name; ?>" width="<?php echo $img_width; ?>px"
                                 height="<?php echo $img_height; ?>px"
                                 style="width: <?php echo $img_width; ?>px;height: <?php echo $img_height; ?>px"
                                 itemprop="image"/>
                        </a>
                    </div>
                    <div class="catalogue-one-item-desc">
                        <h5 itemprop="name">
                            <a class="product-name" href="<?php echo $ilink; ?>"
                               title="<?php echo $similar->item_name; ?>"
                               itemprop="url"><?php echo $similar->item_name; ?></a>
                        </h5>

                        <p class="item-price" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                            <?php if ($similar->price) {
                                echo number_format($item->price, 0, '.', ' ') . ' ' . $params->get('catalogue_currency', 'руб.');
                            } ?>
                            <meta itemprop="priceCurrency" content="0">
                        </p>
                        <a href="#" class="orange-btn one-click-order">Купить в 1 клик</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
</div>