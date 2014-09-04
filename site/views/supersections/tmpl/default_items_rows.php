<?php defined('_JEXEC') or die; 

$app = JFactory::getApplication();
$params = $app->getParams()->toArray();
$desc_len = (int)$params['short_desc_len'];
?>
<div class="rows-wrapper">
	<ul class="unstyled supersections-list">
		<?php foreach ($this->items as $item ): ?>
		<?php
			$link = JRoute::_('index.php?option=com_catalogue&view=supersection&ssid='.$item->id);
			if($desc_len > 0){
				$desc = substr(strip_tags($item->supersection_description), 0, $desc_len).'...';
			}
			else{
				$desc = strip_tags($item->supersection_description);
			}
		?>
			<li>
				<div class="catalogue-element-header">
					<h2>
						<a href="<?php echo $link; ?>">
							<?php echo $item->supersection_name; ?>		
						</a>
					</h2>
				</div>
				<div class="catalogue-element-desc">
					<?php echo $desc; ?>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</div>