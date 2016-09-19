<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title><?=(isset($title) ? $title : '');?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Html5TemplatesDreamweaver.com">
    <meta name="keywords" content="" />
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW"> <!-- Remove this Robots Meta Tag, to allow indexing of site -->
    <link rel="icon" href="/public/i/fav.png" sizes="38x38" type="image/png">
    <link href="/public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/public/css/bootstrap-responsive.min.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Icons -->
    <link href="/public/css/general_foundicons.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="/public/css/social_foundicons.css" media="screen" rel="stylesheet" type="text/css" />
    <!--[if lt IE 8]>
    <link href="/public/css/general_foundicons_ie7.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="/public/css/social_foundicons_ie7.css" media="screen" rel="stylesheet" type="text/css" />
    <![endif]-->
    <link rel="stylesheet" href="/public/css/font-awesome.min.css">
    <!--[if IE 7]>
    <link rel="stylesheet" href="/public/css/font-awesome-ie7.min.css">
    <![endif]-->

    <link href="/public/css/carousel_style.css" rel="stylesheet" type="text/css" />
    <link href="/public/css/camera.css" rel="stylesheet" type="text/css" />

    <link href="http://fonts.googleapis.com/css?family=Syncopate" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Abel" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Pontano+Sans" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet" type="text/css">

    <link href="/public/css/custom.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body id="pageBody">
<div id="divBoxed" class="container">
    <div class="transparent-bg" style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;z-index: -1;zoom: 1;"></div>
    <?=View::factory('header')->set('menu', $menu)->set('rootPage', $rootPage);?>
    <?=$content;?>
    <?=View::factory('footer');?>
</div>
</body>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="/public/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/public/js/default.js" type="text/javascript"></script>
<script src="/public/js/jquery.carouFredSel-6.2.0-packed.js" type="text/javascript"></script><script type="text/javascript">$('#list_photos').carouFredSel({ responsive: true, width: '100%', scroll: 2, items: {width: 320,visible: {min: 2, max: 6}} });</script>
<script src="/public/js/camera.min.js" type="text/javascript"></script>
<script src="/public/js/jquery.easing.1.3.js" type="text/javascript"></script>
<script type="text/javascript">function startCamera() {$('#camera_wrap').camera({ fx: 'scrollLeft', time: 2000, loader: 'none', playPause: false, navigation: true, height: '35%', pagination: true });}$(function(){startCamera()});</script>
</html>