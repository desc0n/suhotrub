<?php
/** @var $itemModel Model_Item */
$itemModel = Model::factory('Item');

$i = 0;
foreach ($items as $item) {
    if ($i >= $itemModel::NOTICES_MARKET_LIMIT * ($page - 1) && $i < $itemModel::NOTICES_MARKET_LIMIT * $page) {
        ?>
        <?=View::factory('item_thumb')->set('itemData', $item);?>
        <?
    }

    $i++;
}
?>

