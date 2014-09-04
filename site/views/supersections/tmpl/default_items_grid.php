<?php defined('_JEXEC') or die; 

$app = JFactory::getApplication();
$params = $app->getParams()->toArray();
$desc_len = (int)$params['short_desc_len'];
?>
<div class="grid-wrapper">
	<div class="row">
		<?php foreach ($this->items as $item ): ?>
		<?php
			$link = JRoute::_('index.php?option=com_catalogue&view=supersection&ssid='.$item->id);
			if($desc_len > 0){
				$desc = substr(strip_tags($item->supersection_description), 0, $desc_len).'...';
			}
			else{
				$desc = strip_tags($item->supersection_description);
			}
			$imgsrc = $item->supersection_image;
		?>
		<div class="span4">
			<div class="element-wrap">
				<div class="catalogue-element-header">
					<?php if(!empty($imgsrc)): ?>
						<a href="<?php echo $link; ?>">
							<img src="<?php echo $imgsrc; ?>" alt="">
						</a>
					<?php else: ?>
						<a href="<?php echo $link; ?>">
							<div style="width: 100%; padding: 90px 0; text-align: center;">
								<span style="text-decoration: none; color: #000;">Изображение отсутствует</span>
							</div>
						</a>
					<?php endif; ?>
					<h2>
						<a href="<?php echo $link; ?>">
							<?php echo $item->supersection_name; ?>		
						</a>
					</h2>
				</div>
				<div class="catalogue-element-desc">
					<?php echo $desc; ?>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>