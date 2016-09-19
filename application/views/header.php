<div class="divPanel notop nobottom">
    <div class="row-fluid">
        <div class="span12">

            <div id="divLogo" class="pull-left">
                <a href="index.html" id="divSiteTitle">Your Name</a><br />
                <a href="index.html" id="divTagLine">Your Tag Line Here</a>
            </div>

            <div id="divMenuRight" class="pull-right">
                <div class="navbar">
                    <button type="button" class="btn btn-navbar-highlight btn-large btn-primary" data-toggle="collapse" data-target=".nav-collapse">
                        NAVIGATION <span class="icon-chevron-down icon-white"></span>
                    </button>
                    <div class="nav-collapse collapse">
                        <ul class="nav nav-pills ddmenu">
                            <?foreach ($menu as $menuData) {
                                $activeClass = $rootPage === $menuData['slug'] ? ' class="active"' : null;
                                ?>
                            <li<?=$activeClass;?>><a href="/page/<?=$menuData['slug'];?>"><?=$menuData['name'];?></a></li>
                            <?}?>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>