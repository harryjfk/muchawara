<?php ?>
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{{$website_title}}}</title>
    <link href="@theme_asset('css/bootstrap.min.css')" rel="stylesheet" type="text/css">
    <script src="@theme_asset('js/jquery.min.js')" type="text/javascript"></script>

    <style>
        * {
            margin: 0px;
            padding: 0px;
        }
    </style>
</head>
<body>
<a href="{{{asset('wara.apk')}}}">
    <img src="{{{asset('themes/DefaultTheme/images/LANDING_PAGE.jpg')}}}" class="img-responsive">
</a>
</body>

<script type="text/javascript" >
    $(document).ready(function () {
        var width = $(window).width();
        if(width < 768) {
            $('img').prop('src', '{{{asset("themes/DefaultTheme/images/LANDING_PAGE_MOVIL.jpg")}}}');
        } else  if(width >= 768 && width <= 1200) {
            $('img').prop('src', '{{{asset("themes/DefaultTheme/images/LANDING_PAGE_TABLET.jpg")}}}');
        } else {
            $('img').prop('src', '{{{asset("themes/DefaultTheme/images/LANDING_PAGE1920.jpg")}}}');
        }

    });

    $(window).resize(function() {
        var width = $(window).width();
        if(width < 768) {
            $('img').prop('src', '{{{asset("themes/DefaultTheme/images/LANDING_PAGE_MOVIL.jpg")}}}');
        } else  if(width >= 768 && width <= 1200) {
            $('img').prop('src', '{{{asset("themes/DefaultTheme/images/LANDING_PAGE_TABLET.jpg")}}}');
        } else {
            $('img').prop('src', '{{{asset("themes/DefaultTheme/images/LANDING_PAGE1920.jpg")}}}');
        }
    });

</script>
</html>
