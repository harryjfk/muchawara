<?php 
use App\Models\Settings;
?>
<!DOCTYPE html>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Email Templates</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
</head>
<body style="text-decoration: none;margin: 0; padding: 0;background-color:rgba(128, 128, 128, 0.39);font-family: 'Roboto', sans-serif;">
 <table border="1" cellpadding="0" cellspacing="0" width="100%;">
  <tr>
   <td>
     <table align="center" border="1" cellpadding="0" cellspacing="0" width="600px" style="border-collapse: collapse;border-color:rgba(0, 0, 0, 0.07);box-shadow: 0px 2px 5px #888888;">

        <tr>
           <td style="background-color: #FFFFFF;padding:0px 0px 10px 24px;border-top: 2px solid #FF4E79;">
              <div style="display:inline-block;">
                 <div class="row">
                  <div style="display:inline-block;margin-right:245px;vertical-align: middle;">
                      <img style="max-width: 230px" src="{{{url('uploads/logo/')}}}/{{{$website_logo}}}">
                  </div>
                  <div style="display:inline-block;">    
                      <img class="td-fold" src="{{{url('uploads/email_template/fold.png')}}}"/>
                  </div>    
                  <p style="font-size: 18px;margin-top: 24px;margin-left:22px;opacity:0.8;">Someone has commented on your photo</p>
                 </div>
               </div>

           </td>
      </tr>
       <tr>
          <td style=" padding: 24px 24px 24px 24px;
  background-color: #FFFFFF;">
              <p style=" color: black;
  opacity: 0.8;
  font-size: 16px;">Hi, {{$user->name}}<br>{{$user2->name}} has commented on your photo.</p>

            <button type="button" style="margin-top: 24px!important;background-color: #1AC636;color: #fff;
    background-color: #5cb85c;
    border-color: #4cae4c;display: inline-block;
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
    border: 1px solid transparent;  display: block;
    margin-top: 18px;
  padding-left: 10px;
  padding-right: 10px;
  background-color: #1AC636;width:106px;border-radius:23px;"><a style="text-decoration:none;color:white;font-weight:normal;" href="{{{url('/')}}}">Log in now!</a></button>
         
        </td>
      </tr>
    <tr>
    </tr>
       <tr>
         <td class="td-footer" style="  background-color: #FFFFFF;padding: 32px 24px 24px 24px;position:relative;z-index:999999;">
           <p class="footer-text" style=" color: #000000;opacity: 0.54;font-size: 11px;">{{{$footer_text}}}</p>
           <ul class="td-social-ul td-footer-ul" style="  display: table;padding-left: 0px;margin-bottom: 0px;margin-top: 24px;">
             <!-- <li class="remove-list-style td-footer-li" style="list-style-type: none; display: inline;"><a href="#" style="color: red; opacity: 0.87;font-size: 11px;font-weight: 400;text-decoration:none;">UNSUBSCRIBE</a></li> -->

           </ul>

          </td>
           </table>
         </td>
       </tr>
     </table>
   </td>
  </tr>
 </table>
</body>
</html>