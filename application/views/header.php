<header>
    <div class="container">
        <div class="menu-row">
            <?foreach ($menu as $menuData) {
                $slug = $menuData['slug'];
                $class = $slug === $rootPage ? 'menu-item-active' : 'menu-item';
                
                $slug = '/page/' . $slug;
                $slug = str_replace('page/main', '', $slug);
                ?>
            <a class="<?=$class;?>" href="<?=$slug;?>"><?=$menuData['name'];?></a>
            <?}?>
        </div>
    </div>
</header>