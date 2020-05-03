<!DOCTYPE html>
<html ⚡ lang="<?php echo Lang::active();?>">
<head>

    <meta charset="utf-8">
    <meta name="description" content="<?php echo $metaDescription;?>"/>
    <meta name="keywords" content="<?php echo $metaKeywords;?>"/>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <?php echo Params::param('metainfo-google-webmasters');?>

    <meta property="fb:app_id" content="168728593755836" />
    <meta property="og:title" content="<?php echo $title;?>" />
    <meta property="og:description" content="<?php echo $metaDescription;?>" />
    <meta property="og:url" content="<?php echo $metaUrl;?>" />
    <?php echo $metaImage;?>

    <link rel="shortcut icon" href="<?php echo BASE_URL;?>visual/img/favicon.ico"/>
    <link rel="canonical" href="<?php echo $metaUrl;?>" />

    <title><?php echo $title;?></title>

    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    <style amp-custom><?php echo str_replace('../../img/', BASE_URL.'visual/img/', str_replace('../fonts/icons', BASE_URL.'visual/css/fonts/icons', file_get_contents(BASE_FILE.'visual/css/stylesheets/public.css')));?></style>

    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
    <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
    <script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>

    <?php echo $header;?>

</head>
<body>

    <?php echo Navigation_Ui::menuAmp();?>
    <?php echo Navigation_Ui::analyticsAmp();?>
    <div id="bodyFrame" class="<?php echo Params::param('country-code');?>">
        <?php echo $content;?>
    </div>

</body>
</html>