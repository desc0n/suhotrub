<div class="contentArea">
    <div class="divPanel notop page-content">
        <?=Content::breadcrumbs(Arr::get($pageData, 'slug'));?>
        <div class="row-fluid">
            <!--Edit Sidebar Content here-->
            <div class="span3">
                <?=Arr::get($pageData, 'secondary_content');?>
            </div>
            <!--/End Sidebar Content -->

            <!--Edit Main Content Area here-->
            <div class="span9" id="divMain">
                <?=Arr::get($pageData, 'main_content');?>
            </div>
            <!--/End Main Content Area here-->
        </div>
        <div id="footerInnerSeparator"></div>
    </div>
</div>