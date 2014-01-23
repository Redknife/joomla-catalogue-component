<?php
defined('_JEXEC') or die;
JHtml::_('behavior.caption');
JHtml::_('bootstrap.tooltip');
$i = 0;
$tmpsec = 0;

$app = JFactory::getApplication('site');
$supsecid = $app->getUserState('supersection.id');
$secid = $app->getUserState('section.id');
?>

<div class="catalogue-sections">
	<div class="page-header">
		<h1 class="catalogue-head">Запчасти для <?php echo $this->items[0]->section_name; ?> </h1>
	</div>
	<ul class="unstyled">
		<?php foreach ($this->items as $item){ ?>
		<li>
			<?php
                $link = JRoute::_( 'index.php?option=com_catalogue&view=items&ssid='.$supsecid.'&sid='.$secid.'&cid='.$item->id );
				echo '<a href="'.$link.'">'.$item->category_name.'</a>';
			 ?>
		</li>
		<?php } ?>
	</ul>
	<div class="working-company">
		<h3>Деятельность компании ООО “Магистраль”</h3>
		<p class="ben-desc">
			Основной сферой деятельности ООО “Магистраль” являются продажа, ремонт и техническое обслуживание железнодорожной техники.
		</p>
		<p class="ben-desc">
			За годы нашей работы на рынке, у нас сформировалась большая база поставщиков и заказчиков, налажены деловые связи и партнерские отношения с крупными участниками данного рынка, такими как Московская, Куйбышевская, Юго-Восточная, Приволжская железные дороги Филиалы ОАО "РЖД", а также "«Пензадизельмаш", "Коломенский завод", "Новочеркасский электровозостроительный завод" и др.
		</p>
		<p class="ben-desc">
			Наше предприятие выполняет ремонтные работы не только в собственных ремонтных базах, но и арендует ремонтные площади, принадлежащие ОАО «РЖД», сертифицированные по стандартам качества и имеющие техническое оснащение для всех видов ремонтов тепловозов, электровозов, путевой техники и железнодорожных кранов. Благодаря этому мы можем в кратчайшие сроки произвести ремонт даже в самых отдаленных районах России.
		</p>
	</div>
 </div>