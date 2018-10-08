<!DOCTYPE html>
<?php $version = (DEBUG) ? md5(rand()) : date('mY').'3';?>
<html lang="<?php echo Lang::active();?>">
<head>

    <meta charset="utf-8">
    <meta name="description" content="<?php echo $metaDescription;?>"/>
    <meta name="keywords" content="<?php echo $metaKeywords;?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php echo Params::param('metainfo-google-webmasters');?>

    <meta property="og:title" content="<?php echo $title;?>" />
    <meta property="og:description" content="<?php echo $metaDescription;?>" />
    <meta property="og:url" content="<?php echo $metaUrl;?>" />
    <?php echo $metaImage;?>

    <link rel="shortcut icon" href="<?php echo BASE_URL;?>visual/img/favicon.ico"/>
    <link rel="canonical" href="<?php echo $metaUrl;?>" />

    <title><?php echo $title;?></title>

    <link href="<?php echo BASE_URL;?>visual/css/stylesheets/public.css?v=<? echo $version; ?>" rel="stylesheet" type="text/css" />

    <?php echo Params::param('metainfo-google-analytics')?>
    <?php echo $header;?>

</head>
<body>

    <div id="bodyFrame" class="<?php echo Params::param('country-code');?>">
        <?php echo $content;?>
    </div>
    <?php echo Adsense::code()?>
    <?php echo Adsense::page()?>

</body>
</html>