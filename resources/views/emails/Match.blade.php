<?php

use App\Models\Settings;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Email Templates</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
            <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
                </head>
                <body style="margin: 0; padding: 0;">

                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">

                        <tr>

                            <td align="center" style="padding: 40px 0 30px 0; background: linear-gradient(to right, #F9BF2C , #F36E21);">

                                <img src="{{{url('uploads/logo/LOGO_NEW_W.png')}}}" alt="WARA" width="50%" style="display: block;" />

                            </td>

                        </tr>

                        <tr>

                            <td bgcolor="#ffffff" style="padding: 40px 30px 10px 30px;">

                                <table border="0" cellpadding="0" cellspacing="0" width="100%">

                                    <tr style="text-align: -webkit-center;">

                                        <td style="color: #153643; font-family: Raleway, sans-serif; font-size: 24px;">

                                            <b>Hay alguien que quiere WARA</b>

                                        </td>

                                    </tr>

                                    <tr style="text-align: -webkit-center;">

                                        <td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">

                                            <img src="{{{$user2->profile_pic_url()}}}" width="100" height="100" style="object-fit:cover; border-radius: 50px;"/>

                                        </td>

                                    </tr>

                                    <tr style="text-align: -webkit-center;">

                                        <td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">

                                            {{{$user2->name}}}

                                        </td>

                                    </tr>

                                    <tr style="text-align: -webkit-center;">

                                        <td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">

                                            <a href="{{{url('/')}}}"  style="margin-top: 0px!important;background-color: #ec650f;color: #fff;
                                               background-color: #ec650f;
                                               border-color: #4cae4c;display: inline-block;
                                               padding: 6px 12px;
                                               text-decoration: none;
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
                                               background-color: #ec650f;
                                               width:220px;
                                               border-radius:23px;
                                               box-shadow: 1px 1px 8px #bbb;">
                                                Ir a WARA
                                            </a>

                                        </td>

                                    </tr>


                                </table>
                                <hr width="150px"/>  

                            </td>

                        </tr>


                        <tr>



                            <td align="center" style="color: #333; font-family: Arial, sans-serif; font-size: 16px; ">

                                <span>
                                    <b>Enviado por WARA</b>
                                    <br />
                                    <b><a href="www.muchawara.com" style="color: #333; text-decoration: blink;"/> www.muchawara.com</b>
                                </span>

                            </td>

                        </tr>
                        <tr>

                            <td align="center" style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; ">

                                <a href = "www.facebook.com"> <img src="{{{url('uploads/email_template/fb.png')}}}" alt="Síguenos" width="32px" style="margin-top: 13px;"/> </a>

                            </td>

                        </tr>

                    </table>

                    </td>

                    </tr>

                    </table>

                </body>

                </html>
