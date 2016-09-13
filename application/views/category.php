<?php
/** @var $itemModel Model_Item */
$itemModel = Model::factory('Item');
?>
<div class="market-content">
    <div class="col-lg-12">
        <?=$market_content;?>
    </div>
</div>
<div class="pagination-row">
    Страница
    <?php for ($i = 1; $i <= ceil($itemsCount / $itemModel::NOTICES_MARKET_LIMIT); $i++) {?>
        <a class="btn btn-default <?=($i == $page ? 'btn-pagination-active' : 'btn-pagination');?>" href="/category/list/<?=$categoryId;?>?page=<?=$i;?>"><?=$i;?></a>
    <?}?>
</div>
