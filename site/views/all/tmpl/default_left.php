<?php defined('_JEXEC') or die;
JHtml::_('behavior.caption');
$start = microtime(true);
$list = CatalogueHelper::getLeftMenuList();
?>
<ul class="unstyled" id="left-menu">
    <?php
    $sec = '';
    $k = 0;

    foreach ($list as $item) {
        if ($sec != $item->section_name) {
            if ($sec != '')
                echo '</ul>';

            $sec = $item->section_name;
            $k = 0;
            $link = JRoute::_('index.php?option=com_catalogue&view=category&sid=' . $item->id . '&cid=' . $item->categoryid . '&Itemid=103');
            echo '<li class="parent one-sec"><a href="#" class="sec-head sec-closed">' . $item->section_name . '</a><ul class="unstyled menu-categories"><li><span class="dash">&mdash;</span><a class="one-category" href="' . $link . '">' . $item->category_name . '</a></li>';
        } else {
            $k++;
            $link = JRoute::_('index.php?option=com_catalogue&view=category&sid=' . $item->id . '&cid=' . $item->categoryid . '&Itemid=103');
            echo '<li><span class="dash">&mdash;</span><a class="one-category" href="' . $link . '">' . $item->category_name . '</a></li>';
        }

    }
    ?>
</ul>
</ul>