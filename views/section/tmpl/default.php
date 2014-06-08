<?php
defined('_JEXEC') or die;
JHtml::_('behavior.caption');
JHtml::_('bootstrap.tooltip');


$supersection_id = $this->state->get('supersection.id');
$section_id = $this->state->get('section.id');

$section_name = $this->state->get('section.name');
$section_desc = $this->state->get('section.desc');

?>

<div class="catalogue-categories">
	<div class="page-header">
		<h1 class="section-head"><?php echo $section_name; ?> </h1>
	</div>
	<p class="catalogue-section-description">
	<?php echo $section_desc ?>
	</p>
	<ul class="unstyled">
		<?php foreach ($this->items as $item){ ?>
		<li>
			<?php
                $link = JRoute::_( 'index.php?option=com_catalogue&view=category&ssid='.$supersection_id.'&sid='.$section_id.'&cid='.$item->id );
				echo '<a href="'.$link.'">'.$item->category_name.'</a>';
			 ?>
		</li>
		<?php } ?>
	</ul>
 </div>