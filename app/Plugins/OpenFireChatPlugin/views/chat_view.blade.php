{{--<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">--}}
{{--<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>--}}

<!------ Include the above in your HEAD tag ---------->


{{--<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>--}}
<!------ Include the above in your HEAD tag ---------->


<!DOCTYPE html>
<html class=''>
<head>
    <script src="{{asset('plugins/OpenFireChatPlugin/js/jquery.min.js')}}"></script>
    <link href="{{asset('plugins/OpenFireChatPlugin/css/bootstrap.min.css')}}" rel="stylesheet" id="bootstrap-css">
    <script src="{{asset('plugins/OpenFireChatPlugin/js/bootstrap.min.js')}}"></script>
    {{--<script src='//production-assets.codepen.io/assets/editor/live/console_runner-079c09a0e3b9ff743e39ee2d5637b9216b3545af0de366d4b9aad9dc87e26bfd.js'></script>--}}
    {{--<script src='//production-assets.codepen.io/assets/editor/live/events_runner-73716630c22bbc8cff4bd0f07b135f00a0bdc5d14629260c3ec49e5606f98fdd.js'></script>--}}
    {{--<script src='//production-assets.codepen.io/assets/editor/live/css_live_reload_init-2c0dc5167d60a5af3ee189d570b1835129687ea2a61bee3513dee3a50c115a77.js'></script>--}}
    <meta charset='UTF-8'>
    <meta name="robots" content="noindex">
    {{--<link rel="shortcut icon" type="image/x-icon"--}}
          {{--href="//production-assets.codepen.io/assets/favicon/favicon-8ea04875e70c4b0bb41da869e81236e54394d63638a1ef12fa558a4a835f1164.ico"/>--}}
    {{--<link rel="mask-icon" type=""--}}
          {{--href="//production-assets.codepen.io/assets/favicon/logo-pin-f2d2b6d2c61838f7e76325261b7195c27224080bc099486ddd6dccb469b8e8e6.svg"--}}
          {{--color="#111"/>--}}
    {{--<link rel="canonical" href="https://codepen.io/emilcarlsson/pen/ZOQZaV?limit=all&page=74&q=contact+"/>--}}
    {{--<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,300' rel='stylesheet'--}}
    <link href='http://localhost/css.css?family=Source+Sans+Pro:400,600,700,300' rel='stylesheet'
          type='text/css'>

    {{--<script src="https://use.typekit.net/hoy3lrg.js"></script>--}}
    {{--<script>try {--}}
            {{--Typekit.load({async: true});--}}
        {{--} catch (e) {--}}
        {{--}</script>--}}
    <link rel='stylesheet prefetch' href='{{asset('plugins/OpenFireChatPlugin/css/reset.min.css')}}'>
    <link rel='stylesheet prefetch'
          href='{{asset('plugins/OpenFireChatPlugin/css/font-awesome.min.css')}}'>
    <link rel='stylesheet prefetch'
          href='{{asset('plugins/OpenFireChatPlugin/css/styles.css')}}'>
    <script src="{{asset('plugins/OpenFireChatPlugin/js/chat/strophe.js')}}"></script>
    <script src="{{asset('plugins/OpenFireChatPlugin/js/chat/stropheState.js')}}"></script>
    <script src="{{asset('plugins/OpenFireChatPlugin/js/chat/list.js')}}"></script>
    <script src="{{asset('plugins/OpenFireChatPlugin/js/chat/message.js')}}"></script>
    <script src="{{asset('plugins/OpenFireChatPlugin/js/chat/connection.js')}}"></script>
    <script src="{{asset('plugins/OpenFireChatPlugin/js/chat/interpreter.js')}}"></script>
    <script src="{{asset('plugins/OpenFireChatPlugin/js/chat/eventList.js')}}"></script>
    <script src="{{asset('plugins/OpenFireChatPlugin/js/chat/user.js')}}"></script>
    <script src="{{asset('plugins/OpenFireChatPlugin/js/chat/template.js')}}"></script>
    <script src="{{asset('plugins/OpenFireChatPlugin/js/chat/chat.js')}}"></script>

</head>
<body>
<style>

    .unread .preview
    {
        font-weight: bold !important;
    }
    .messages
    {
        width: 100%;
    }
    .not-friend
    {
        text-align: center;
        margin-top: 25%;
    }
    .not-friend img
    {
        width: 128px;
    }
</style>
<div id="frame">


</div>
<div class="not-friend" style="display: none">

    <img src="{{ asset("images/4_pop_mango.png")}}">
    <p>
        Tu no tienes guara con nadie, haz wara primero para poder chatear!!!!!</p>
</div>
<script>
    var data = <?php echo json_encode($current->getRelatedInformationWEB()); ?>;
    var chat = new Chat("#frame", "{{asset("")}}", '{{asset("")}}index.php');

    chat.load(data);

    if(chat.users.selected==null)
    {
        $("#sidepanel").hide();
        $(".content").hide();
        $(".not-friend").appendTo("#frame");
        $(".not-friend").show();

    }

    else
    {


        

    }



</script>
{{--<script src='//production-assets.codepen.io/assets/common/stopExecutionOnTimeout-b2a7b3fe212eaa732349046d8416e00a9dec26eb7fd347590fbced3ab38af52e.js'></script>--}}
{{--<script src='https://code.jquery.com/jquery-2.2.4.min.js'></script>--}}

</body>
</html>