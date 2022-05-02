<!DOCTYPE html">
<html>
    <head>
        <title>DatingFramework Installer v1.5.13</title>
        <link rel="stylesheet" href="{{{asset('css/bootstrap3.3.6.min.css')}}}">
        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <script src="{{{asset('js/jquery1.12.0.min.js')}}}"></script>
        <script src="{{{asset('js/bootstrap3.3.6.min.js')}}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.3.0/zxcvbn.js"></script>
        <link rel="stylesheet" href="{{{asset('Install')}}}/css/installer_new.css">
    </head>
    <body>
        <div class="col-md-12 full-col">
            <div class="col-md-8 main-col">
                <div class="col-md-12 text-center top-head-col">
                    <img class="text-center" src="{{{asset('Install')}}}/images/logo.png" height="50px" width="250px" />
                </div>
                <div class="row">
                    <div class="col-md-6 main-left-col">
                        <h5 class="text_white opacity_6">DatingFramework v1.5.13</h5>
                        <h2 class="text_white width_50">SETUP DATABASE.</h2>
                        <div class="col-md-10 left_bottom_div">
                            <div class="col-md-2 text-center step1_div">
                                <div class="row">
                                    <button type="button" class="btn circle-btn outline_none active1"></button>
                                    <h5 class="text_white step_text">Step 1</h5>
                                </div>
                            </div>
                            <div class="col-md-3 step_line_col">
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="row">
                                    <button type="button" class="btn circle-btn outline_none"></button>
                                    <h5 class="text_white step_text">Step 2</h5>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-md-6 main-right-col">
                        <div class="top-text-col">
                            <h5 class="text_white">Installing DatingFramework</h5>
                        </div>
                        @if(session()->has('status'))
                        <div class="error_para_div custom-error">
                            <i class="glyphicon glyphicon-remove-sign pull-left text_white"></i>
                            <p class="error-para">{{{session('status')}}}{{{session()->forget('status')}}}</p>
                        </div>
                        @endif
                        <form action = "{{{url('/installer')}}}" method = "POST" id = "liteoxide-default-signin">
                            {{csrf_field()}}
                            <input type = "hidden" name = "installation" value = "step2">
                            <div class="form-group form_style">
                                <label class="control-label" for="db_host">Database Host</label>
                                <input type="text" class="form-control input_style" name = "host" id="db_host">
                                <p class="error-para1" id="host-error">Database required</p>
                            </div>
                            <div class="form-group form_style">
                                <label class="control-label" for="db_uname" >Database Username</label>
                                <input type="text" class="form-control input_style" id="db_uname" name ="username">
                                <p class="error-para1" id="username-error">Username required</p>
                            </div>
                            <div class="form-group form_style">
                                <label class="control-label" for="db_pass">Database Password</label>
                                <input type="password" class="form-control input_style" id="db_pass" name ="password">
                                <div class="password-background"></div>
                                <p class="error-para1" id="password-error">Password required</p>
                            </div>
                            <div class="form-group form_style">
                                <label class="control-label" for="db_name">Database Name</label>
                                <input type="text" class="form-control input_style" id="db_name" name ="database">
                                <p class="error-para1" id="database-error">Database Name required</p>
                            </div>
                            <div class="form-group form_style">
                                <label class="control-label" for="db_name">License Key</label>
                                <input type="text" class="form-control input_style" id="license_key" name ="license_key">
                               <p class="error-para1 " id="license-key-error">License key required</p> 
                            </div>

                            <div class="form-group form_style">
                                <button type="submit" class="btn btn-danger pull-right next_btn outline_none">Next <i class="fa fa-angle-right next_icon"></i> </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>

            $(document).ready(function()
            {



            $('.form-control').on('focus blur', function (e) {
               $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
            }).trigger('blur');
            });
        </script>
        <script>
            $(document).ready(function(){
              $( document ).ready(function() {
             $('#db_pass').on('propertychange change keyup paste input', function() {
               // TODO: only use the first 128 characters to stop this from blocking the browser if a giant password is entered
               var password = $(this).val();
               var passwordScore = zxcvbn(password)['score'];
               
               var updateMeter = function(width, background, text) {
                 $('.password-background').css({"width": width, "background-color": background});
                 $('.strength').text('Strength: ' + text).css('color', background);
               }
               
               if (passwordScore === 0) {
                 if (password.length === 0) {
                   updateMeter("0%", "#ffa0a0", "none");
                 } else {
                   updateMeter("20%", "#ffa0a0", "very weak");
                 }
               }
               if (passwordScore == 1) updateMeter("40%", "#ffb78c", "weak");
               if (passwordScore == 2) updateMeter("60%", "#ffec8b", "medium");
               if (passwordScore == 3) updateMeter("80%", "#c3ff88", "strong");
               if (passwordScore == 4) updateMeter("100%", "#ACE872", "very strong"); // Color needs changing
               
             });
             
             // TODO: add ie 8/7 support, what browsers didnt support this check market share
             $('.show-password').click(function(event) {
               event.preventDefault();
               if ($('#db_pass').attr('type') === 'password') {
                 $('#db_pass').attr('type', 'text');
                 $('.show-password').text('Hide password');
               } else {
                 $('#db_pass').attr('type', 'password');
                 $('.show-password').text('Show password');
               }
             });
             
            });
            });
            
            $(document).ready(function()
            {
             $("#liteoxide-default-signin").submit(function(e){
              
               $(".error-para1").hide();
             var host=$("#db_host").val();
             var username=$("#db_uname").val();
             var password=$("#db_pass").val();
             var database=$("#db_name").val();
             var flag=0;
             if(host == '')
             {
               $("#host-error").show();
               flag=1;
             }
             if(username == '')
             {
               $("#username-error").show();
               flag=1;
             }
            
             
             
             if(database == '')
             {
               $("#database-error").show();
               flag=1;
             }
            
             if(flag ==1)
             {
               e.preventDefault();
             }
             
            });
            }); 
            
            
            
            
        </script>  
    </body>
    <style type="text/css">
      body
     {
        background: url("{{{asset('Install')}}}/images/installer_welcome.jpg");
        background-repeat: no-repeat;
        background-attachment: fixed;
        height: auto;
        background-size:cover;
        background-position: center;
        
    }
        .custom-error{
        margin-left: 0px;
        width: 100%;
        }
        .error-para1 {
        color:rgb(181, 71, 71);
        display: none;
        }
    </style>
</html>