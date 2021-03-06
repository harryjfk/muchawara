
<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<?php use \App\Models\Settings; ?>
<!DOCTYPE HTMl>
<html>
<head>
<title>{{{Settings::_get('website_title')}}}</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="icon" href="{{{asset('uploads/favicon')}}}/{{{Settings::_get('website_favicon')}}}" type="image/gif" sizes="16x16">
<link href='http://fonts.googleapis.com/css?family=Strait' rel='stylesheet' type='text/css'>
</head>
<body>
	
			<div class="wrap">
				<h1>{{{Settings::_get('website_title')}}}</h1>
				<div class="banner"><h1>{{trans('app.error_page_any_error_text')}}</h1></div>
				
			
			</div>
	    	
</body>


<style type="text/css">
	

	/*--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
*/
/* reset */
html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,dl,dt,dd,ol,nav ul,nav li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,embed,figure,figcaption,footer,header,hgroup,menu,nav,output,ruby,section,summary,time,mark,audio,video{margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline;}
article, aside, details, figcaption, figure,footer, header, hgroup, menu, nav, section {display: block;}
ol,ul{list-style:none;margin:0;padding:0;}
blockquote,q{quotes:none;}
blockquote:before,blockquote:after,q:before,q:after{content:'';content:none;}
table{border-collapse:collapse;border-spacing:0;}
/* start editing from here */
a{text-decoration:none;}
.txt-rt{text-align:right;}/* text align right */
.txt-lt{text-align:left;}/* text align left */
.txt-center{text-align:center;}/* text align center */
.float-rt{float:right;}/* float right */
.float-lt{float:left;}/* float left */
.clear{clear:both;}/* clear float */:
.pos-relative{position:relative;}/* Position Relative */
.pos-absolute{position:absolute;}/* Position Absolute */
.vertical-base{	vertical-align:baseline;}/* vertical align baseline */
.vertical-top{	vertical-align:top;}/* vertical align top */
.underline{	padding-bottom:5px;	border-bottom: 1px solid #eee; margin:0 0 20px 0;}/* Add 5px bottom padding and a underline */
nav.vertical ul li{	display:block;}/* vertical menu */
nav.horizontal ul li{	display: inline-block;}/* horizontal menu */
img{max-width:100%;}
/*end reset*/
body{
	font-family: 'Strait', sans-serif;
	 background: #340030;

}
.wrap{
	 height:750px;
	 margin: 0 auto;
	 position: relative;
}
h1{
	color: #fff;
	font-size: 30px;
	padding: 30px;
}
.banner{
	width: 343px;
	margin: 100px auto 0 auto ;
}
.banner img{

}
.search{
	width:300px;
	margin:50px auto 0 auto; 
	position: relative;
}
.search input[type="text"]{
	font-family: 'Strait', sans-serif;
	background: #491c47;
	padding:6px 30px 6px 6px;
	width: 250px;
	border:none;
	color: #986595;
	font-size: 18px;
	outline: none;

}

.banner{
	width: 100%;
	text-align: center;
}

</style>
</html>    

