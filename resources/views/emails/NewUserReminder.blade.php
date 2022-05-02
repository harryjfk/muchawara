<?php 
    use App\Models\Settings;
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Email Templates</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    </head>
    <body style="font-family: 'Roboto' !important;text-decoration: none;margin: 0; padding: 0;background-color:rgba(128, 128, 128, 0.39);">
        <table border="1" cellpadding="0" cellspacing="0" width="100%;">
            <tr>
                <td>
                    <table align="center" border="1" cellpadding="0" cellspacing="0" width="600px" style="border-collapse: collapse;border-color:rgba(0, 0, 0, 0.07);box-shadow: 0px 2px 5px #888888;">
                        <tr>
                            <td style="background-color: #FFFFFF;padding:0px 0px 10px 24px;border-top: 2px solid #FF4E79;">
                                <div style="display:inline-block;">
                                    <div class="row">
                                        <div style="display:inline-block;margin-right:335px;vertical-align: middle;">
                                            <img style="max-width: 230px" src="{{{url('uploads/logo/')}}}/{{{$website_logo}}}">
                                        </div>
                                        <div style="display:inline-block;">
                                            <img class="td-fold" src="{{{url('uploads/email_template/fold.png')}}}" />
                                        </div>
                                        <p style="font-size: 18px;margin-top: 24px;">{{trans('email.checkout_these_new_people')}}</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-user" style=" padding: 24px 24px 0px 24px;
                                background-color: #FFFFFF;">

                                @foreach($user2 as $newUser)
                                <a class="img-div" style="display: inline-block;text-decoration: none;text-align: center;background: rgba(255, 78, 121, 0.13);border-radius:5%;padding: 10px 5px 5px 5px;color:black" href="{{url('user/'.$newUser->slug_name)}}">
                                    <img src="{{$newUser->thumbnail_pic_url()}}" width="70" height="70" />
                                    <div style="padding:5px">
                                        {{explode(" ", $newUser->name)[0]?explode(" ", $newUser->name)[0]:""}}, 
                                        {{$newUser->age()}}
                                    </div>
                                </a>
                                @endforeach

                                <!-- <div class="img-div" style="background:url('http://placekitten.com/g/400/200') no-repeat top / 130px auto;background-position: center;
                                    background-size: 80px 60px;  height:62px;
                                    width:64px;
                                    overflow:hidden;
                                    border-radius:50%;
                                    display: inline-block;">
                                    <div>asdfa</div>
                                </div> -->
                                <p class="td-user-text" style="font-size: 21px;">{{trans('email.be_first_one_send_message')}}</p>
                                <a  class="btn btn-success td-user-btn" style="  border-radius: 15px;
                                    padding-left: 20px;
                                    padding-right: 20px;
                                    background-color: #1AC636;border-color: #4cae4c;display: inline-block;
                                    padding: 6px 12px;
                                    margin-bottom: 0;
                                    font-size: 14px;
                                    font-weight: 400;
                                    line-height: 1.42857143;
                                    text-align: center;
                                    white-space: nowrap;
                                    vertical-align: middle;
                                    -ms-touch-action: manipulation;
                                    touch-action: manipulation;
                                    cursor: pointer;
                                    -webkit-user-select: none;
                                    -moz-user-select: none;
                                    -ms-user-select: none;
                                    user-select: none;
                                    background-image: none;
                                    text-decoration: none;
                                    border: 1px solid transparent;color:#FFFFFF" href="{{url('peoplenearby')}}">{{trans('email.say_hello')}}</a>
                                <p class="td-social-text" style="  font-size: 18px;
                                    color: black;
                                    opacity: 0.5;
                                    margin-bottom: 3px;
                                    display: table;
                                    margin-top: 10%;
                                    float: right">{{trans('email.have_fun')}}<br>{{$website_title}}</p>
                                <!-- <p class="td-social-team" style="  font-size: 16px;
                                    color: black;
                                    opacity: 0.4;
                                    display: table;
                                    margin-bottom: 30px;
                                    float: right">{{$website_title}}</p> -->
                            </td>
                        </tr>
                        <tr>
                            <td class="td-footer" style="  background-color: #FFFFFF;padding: 32px 24px 24px 24px;position:relative;z-index:999999;">
                                <p class="footer-text" style=" color: #000000;opacity: 0.54;font-size: 11px;">{{{$footer_text}}}</p>
                            </td>
                    </table>
                </td>
            </tr>
        </table>
        </td>
        </tr>
        </table>
        <div class="image-div" style=" height:62px;
            width:64px;
            overflow:hidden;
            border-radius:50%;
            display: inline-block;">
            <img class="td-custom-image td-custom-bday-img" style="  
                margin: auto;right: 310px;
                top: 484px;
                z-index: 1;
                position: absolute;
                opacity: 0.3;"src="{{{url('uploads/email_template/images/message.png')}}}"/> 
        </div>
        <script>
            $(document).ready(function() {
            
              $("button").hover(function(){
              $(this).css("background-color", "#398439");
               $("a.button-link").css("color","white");
              }, function(){
              $(this).css("background-color", "#1AC636");  
              $("button").find("a").css("color", "#FFFFFF");
            });  
             $("a.li-a").hover(function(){
              $(this).css("color", "blue");
              }, function(){
              $(this).css("color", "black");
            });
            
            });
        </script> 
    </body>
</html>