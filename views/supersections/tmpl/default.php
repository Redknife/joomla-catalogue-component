<?php
defined('_JEXEC') or die;
JHtml::_('behavior.caption');
JHtml::_('bootstrap.tooltip');
$i = 0;
$tmpsec = 0;
?>

<div class="catalogue-supersections">
	<div class="page-header">
		<h1 class="catalogue-head">Каталог продукции</h1>
	</div>
	<ul class="unstyled">
	<?php
		$ssec = ''; $k = 0;

		foreach ($this->items as $item){
			if ($ssec != $item->supersection_name){
				if ($ssec != '')
					echo '</ul>';

				$ssec = $item->supersection_name;
				$k = 0;
                $link = JRoute::_( 'index.php?option=com_catalogue&view=section&ssid='.$item->id.'&sid='.$item->sectionid );
				echo '<li class="one-supersection parent supersection-closed"><h3>'.$item->supersection_name.'</h3><ul class="sectionlist unstyled"><li><a href="'.$link.'">'.$item->section_name.'</a></li>';
			}
			else{
				$k++;
                $link = JRoute::_( 'index.php?option=com_catalogue&view=section&ssid='.$item->id.'&sid='.$item->sectionid );
				echo '<li><a href="'.$link.'">'.$item->section_name.'</a></li>';
			}

		}
	?>
	</ul>
 </div>