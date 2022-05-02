<?php use App\Components\Theme;?>
<!doctype html>
<html>
    <head>
        <meta name="csrf-token" content="{{{ csrf_token() }}}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{{$website_title}}}</title>
        {{{Theme::render('metaTags')}}}
        <link href="@theme_asset('css/bootstrap.min.css')" rel="stylesheet" type="text/css">
        <link href="@theme_asset('css/font-awesome.min.css')" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,100' rel='stylesheet' type='text/css'>
        <link 
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" type="text/css">
        <link rel="stylesheet" href="{{{asset('themes/DefaultTheme/css/reset.css')}}}" type='text/css'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
        <link rel="stylesheet" href="{{{asset('themes/DefaultTheme/css/flag.css')}}}" type="text/css" media="screen" />
        <link href="@theme_asset('css/toastr.min.css')" rel="stylesheet" type="text/css">
        <link href="{{{asset('themes/DefaultTheme/css/LandingPageSignIn.css')}}}" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Raleway:400,200' rel='stylesheet' type='text/css'> 
        <script type="text/javascript" src="@theme_asset('js/jquery.min.js')"></script>
        <script type="text/javascript" src="@theme_asset('js/underscore-min.js')"></script>
        {{{Theme::render('landing_header_scripts')}}}
        <style>
            body{
            color: #4d4d4d;
            font-family: 'Raleway', sans-serif;
            }
            
            .form_width {
    width: 28%;
}
.form-control
            {
	            font-size: 12px;
            }
            .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            /*   background: url("{{{asset('themes/DefaultTheme/images/heart_small.gif')}}}")  50% 50% no-repeat rgb(249,249,249); */
            opacity: 0.7;
            }
            .social_login_cnt{
            margin-top: 0px;
            }
            .btn--google {
            background: #dc5050;
            color: #fff;
            padding: 7px 20px 7px;
            border-radius: 16px;
            font-size: 1em;
            line-height: 1.3572;
            transition: background .2s,color .2s;
            margin-right: 4%;
            }
            .icongoogle {
            background-color: white;
            color: #dc5050;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            line-height: 1.5em;
            margin-right: 2%;
            margin: -2px 10px -6px 0;
            }
            .display_in_block {
            display: inline-block;
            }
            .profile_drop_menu {
            border-radius: 35px;
            color: rgba(0, 0, 0, 0.68);
            background: white;
            line-height: 4px;
            padding: 3px;
            border: 1px solid rgba(0, 0, 0, 0.61);
            }
            .social-dropdown-div-ul {
            top: 140%;
            left: -13px;
            padding: 8px 8px;
            border-radius: 11px;
            }
            .social-dropdown-div-ul>li>a {
            margin-bottom: 2px;
            }
            .btn--facebook {
            background: #3464d4;
            color: #fff;
            padding: 7px 24px 7px;
            border-radius: 16px;
            font-size: 1em;
            line-height: 1.3572;
            transition: background .2s,color .2s;
            }
            .iconfb {
            background-color: white;
            color: #3464D4;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            line-height: 1.5em;
            margin-right: 2%;
            margin: -2px 10px -6px 0;
            }
            .btn--facebook:hover {
            background: #2851AF !important;
            color: #fff !important;
            }
            .btn--google:hover {
            background: #BD3E3E !important;
            <meta name="csrf-token" content="{{{ csrf_token() }}}">
            color: #fff !important;
            }
            .dropdown-custom-styling {
            float: right;
            margin-right: 13%;
            margin-top: 0.3%;
            }
            .validation-error {
            color: #AD4646;
            display: none;
            margin-top:-9px;
            font-size:13px;
            }
            .liteoxide-purpose {
            font-weight: 400;
            font-size: 11px;
            opacity: 0.7;
            }
            .alert-danger {
            border-radius: 4px;
            margin-bottom: 4px;
            padding-left: 9px;
            padding-top: 9px;
            padding-bottom: 9px;
            }
            .liteoxide-purpose {
            width: 87%;
            }
            .date-input-addon
            {
            padding-top:20px;
            }
            .social_p_margin
            {
            display:none;
            }
            #options
            {
            top:-20px;
            }
            .here-dropdown
            {
            width:106%;
            text-align:left;
            }
            .chatmeetdate-caret
            {
            float:right;
            margin-top:8px;
            }
            .btn-vk
            {
            background: #587ea3;
            color: #fff;
            padding: 7px 20px 7px;
            border-radius: 16px;
            font-size: 1em;
            line-height: 1.3572;
            transition: background .2s,color .2s;
            margin-right: 4%;
            }
            .form_bg {
            background: #FFF;
            box-shadow: 0 0 40px 10px rgba(0,0,0,.3);
            border-radius: 15px 15px 15px 15px;
            }
            .btn_signup_bg{
            	color: #FFF;
            	background: linear-gradient(rgb(29, 185, 84) ,rgb(22, 152, 68) );
            }
            #options > button
            {
	            background: none !important;
				border: none !important;
/* 				color: gray; */
            }
            
           @media only screen and (min-width:281px) and (max-width: 992px){
            
            .form_width {
	width: 95%;
	margin: 3%;
	float: left;
	z-index: 9;
}
}

 


			
        </style>
    </head>
    <body style="background:url('@if($website_backgroundimage) {{{asset("uploads/backgroundimage/{$website_backgroundimage}")}}} @else {{{asset('themes/DefaultTheme/images/bg.png')}}} @endif');background-size:cover;">
    <div class="col-md-12 header-height header-bg header-padding absolute-position z_index-change">
        <ul class="list-inline display_inline float_right display_flex">
            <li>
<!--                 <p class="  opacity_7 signin_padding">{{{trans('LandingPagesPlugin.top_signin_text')}}}</p> -->
            </li>
            <li class="li-padding">
                <a href="{{{url('/register')}}}" class="btn btn-padding btn_border btn-border_radius btn_bg btn_color login_btn">{{{trans('app.signup')}}}</a>
            </li>
            <li>
                <div class="form-group display_block">
                    {{{Theme::render('top-header')}}}
                </div>
            </li>
        </ul>
    </div>
    <div class="col-md-12 col_bg_img col_main-height bg_size">
       
        <div class="row">
	        
	         
            <div class="@if($only_social_logins == 'true') col-md-12 makeitcenter @else col-md-6 @endif col-xs-12 col_main-text-margin text-center">
	            
	            <div class="text-center logo_col_margin">
	            <img src="{{{asset('uploads/logo')}}}/{{{$website_outerlogo}}}"/>
<!-- 	            <h4 class="text-center  ">{{{trans_choice('LandingPagesPlugin.first_screen_main_heading',0)}}}</h4> -->
	        </div>
              
                <p class="  opacity_7 text-center" style="font-size: 15px;color: white;">{{{trans_choice('LandingPagesPlugin.first_screen_main_heading_sub',1)}}}
	                 <span style="font-size: 19px;color:#ffea02">{{$total_signups}}</span>
	                {{{trans_choice('LandingPagesPlugin.active_users_text',1)}}}
	                
	                
                </p>
                
                  <div class="social_margin text-center">
                    {{{Theme::render('login')}}}
                </div>
            </div>
            <div class="col-md-4 form_margin form_bg form_padding form_width form_boxshadow"  style="margin-top: 8%;" @if($only_social_logins == 'true') style="display:none" @endif>
            <form id="form1" action = "{{{ URL::to('login') }}}" method = "POST">
                {!! csrf_field() !!} 
                <input type = "hidden" value = "" id = "lat" name = "lat">
                <input type = "hidden" value = "" id = "lng" name = "longi">
                @if(session()->has('message'))
                <div class="alert-danger">
                    {{{session('message')}}}{{{session()->forget('message')}}}
                </div>
                @elseif(session()->has('emailChange'))
                <div class="alert-danger">
                    {{{session('emailChange')}}}
                    {{{session()->forget('emailChange')}}}
                </div>
                @endif
                <div class="form-group form_group_margin-bottom">
                    <input type="email" readonly onfocus="this.removeAttribute('readonly');" class="form-control remove_boxshadow input_height" name="username" id="email" placeholder="{{{trans('app.email')}}}">
                </div>
                <div class="form-group form_group_margin-bottom">
                    <input type="password" readonly onfocus="this.removeAttribute('readonly');" class="form-control remove_boxshadow input_height" id="pwd1" name="password" placeholder="{{{trans('app.password')}}}">
                </div>
                <div class="form-group" style="position:relative">
                    <input type="checkbox" class="form-control remember-me-checkbox" name="remember_me" style="width:auto;">
                    <span style="position: absolute;top: 6px;margin-left: 16px;width: 100%;font-size:16px">{{{trans('LandingPagesPlugin.remember_me')}}}</span>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block btn_signup_bg input_height border_none signup_height">{{{trans('app.signin')}}}</button>
                </div>
                <div class="form-group">
                    <p class="sign_up_text  ">{{trans_choice('LandingPagesPlugin.signup_bottom',3)}} <a href="@LandingPageUrl('terms_and_conditions')">{{trans('LandingPagesPlugin.terms_and_conditions')}} </a> and <a href="@LandingPageUrl('privacy_policy')">{{trans('LandingPagesPlugin.privacy_policy')}}</a></p>
                    <p class="sign_up_text  "> {{trans_choice('LandingPagesPlugin.about_us_before_text',0)}} <a href="@LandingPageUrl('about_us')"> {{trans_choice('LandingPagesPlugin.about_us',1)}} </a></p>
                </div>
                @if($show_forgot_password_link)
                <a id="forgot-password-link" href="#/" class="forgetPassword" >{{{trans('LandingPagesPlugin.forgot_password_link_text')}}}</a>
                @endif
            </form>
            <form id="forgot-password-form" action="{{{url('/forgotPassword/submit')}}}" method="POST" style="display:none;">
                {!! csrf_field() !!} 
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" name="username" id="username_forgotpwd" class="form-control remove_boxshadow input_height" placeholder="{{{trans('app.email')}}}" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group ">
                            <p class=" ">{{{trans('LandingPagesPlugin.reset_password_text')}}}</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <a href="{{{url('/login')}}}" class="rememberPassword" >{{{trans('LandingPagesPlugin.remember_password_link_text')}}}</a>
                        <button type="submit" class="btn btn-primary border_none btn_signup_bg pull-right" id="reset">{{{trans('LandingPagesPlugin.reset_password_button_text')}}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="@theme_asset('js/bootstrap.min.js')"></script>
    <script src="{{{asset('themes/DefaultTheme/js/bootstrap-datepicker.js')}}}"></script> 
    <script type="text/javascript" src="@theme_asset('js/moment.min.js')"></script>
    <script type="text/javascript" src="@theme_asset('js/toastr.min.js')"></script>
    <script>
        $.ajaxSetup({ 
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        })
    </script> 
    <script>
        $(document).ready(function(){
        
        if('{{{session()->get("account_activation")}}}'=='true')
        {
          toastr.success('Account activated!');
        }
        });
        
    </script> 
    <script>
        $(document).ready(function()
        {
        $('.form-control').on('focus blur', function (e) {
           $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
        }).trigger('blur');
        });
    </script>  
    <script>
        $(document).ready(function() {
          $("#datetimepicker")
                .datepicker({
                    format: 'dd/mm/yyyy'
                });
          });
              
    </script>
    <script type="text/javascript">
        $(window).load(function() {
          $(".loader").fadeOut("slow");
        })
    </script>
    <script>
        $(document).ready(function(){
         $("#forgot-password-link").click(function(){
          $("#forgot-password-form").show();
          $("#form1").hide();
        });
        });
    </script>
    <script>
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();
		});
	</script>
    {{{Theme::render('landing_footer_scripts')}}}
    <footer>
			{{{Theme::render('follow_us')}}}	    
    </footer>
    </body>
</html>