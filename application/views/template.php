<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?=(isset($title) ? $title : '');?></title>
    <link rel="icon" href="/public/i/fav.png" sizes="38x38" type="image/png">
    <!-- Bootstrap -->
    <link href="/public/css/bootstrap.css?v=1" rel="stylesheet">
    <link href="/public/css/custom.css?v=1" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/jquery.rollbar.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>
<?=View::factory('header')
    ->set('menu', $menu)
    ->set('rootPage', $rootPage)
;?>
<div class="container">
    <div class="row">
        <div class="col-lg-3">
            <div class="catalog-list">
                <h3 class="text-center">Каталог</h3>
                <?foreach ($categories as $category) {?>
                <a href="/category/list/<?=$category['id'];?>"><?=$category['name'];?></a>
                <?}?>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="market">
                <?=$content;?>
                <?=View::factory('footer')
                    ->set('lastSeeItems', $lastSeeItems)
                ;?>
            </div>
        </div>
    </div>
</div>
</body>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/public/js/bootstrap.js"></script>
<script src="/public/js/scripts.js?v=1"></script>
</html>