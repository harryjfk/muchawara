<?php use App\Components\Theme;?>
<!doctype html>
<html>
    <head>
        <meta name="csrf-token" content="{{{ csrf_token() }}}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{{$website_title}}}</title>
        {{{Theme::render('metaTags')}}}
        <link rel="stylesheet" href="@plugin_asset('LandingPagesPlugin/userinteractive/css/main.css')" />


        {{{Theme::render('landing_header_scripts')}}}
		<style>
			.main .goto-next
			{
				
				background-image: url("@plugin_asset('LandingPagesPlugin/userinteractive/images/arrow.svg')");
			}
			
			#header-bg
			{
				  background: url("@plugin_asset('LandingPagesPlugin/userinteractive/images/pic1.jpeg')")  50% 0% no-repeat rgb(249,249,249);
            opacity: 0.6;
			}
		</style>	
           
    </head>


	<body>

		<!-- Header -->
			<section id="header" class="main_header">
				
				<header class="major">
					<h1> <img class="logo_margin" src="{{{asset('uploads/logo')}}}/{{{$website_outerlogo}}}"/></h1>
					<p>True friends never leave their friends single and lonely</p>
				</header>
				<div class="container">
					
										<ul class="actions">
						<li><a href="#one" class="button special scrolly">Begin</a></li>
					</ul>
				</div>
			</section>

		<!-- One -->
			<section id="one" class="main special">
				<div class="container">
					<span class="image fit primary"><img src="@plugin_asset('LandingPagesPlugin/userinteractive/images/pic4.jpeg')" alt="" /></span>
					<div class="content">
						<header class="major">
							<h2>Who I am</h2>
						</header>
						<p>I am match maker.<br/>Match-make your friends (or yourself) with thousands of awesome singles. </p>
					</div>
					<a href="#three" class="goto-next scrolly">Next</a>
				</div>
			</section>



		<!-- Three -->
			<section id="three" class="main special">
				<div class="container">
					<span class="image fit primary"><img src="@plugin_asset('LandingPagesPlugin/userinteractive/images/pic3.jpeg')" alt="" /></span>
					<div class="content">
						<header class="major">
							<h2>One more thing</h2>
						</header>
						<p>It’s social dating.</p>
					</div>
					<a href="#footer" class="goto-next scrolly">Next</a>
				</div>
			</section>

		

		<!-- Footer -->
			<section id="footer" class="main special">
				<div class="container">
					<span class="image fit primary"><img src="@plugin_asset('LandingPagesPlugin/userinteractive/images/pic2.jpeg')" alt="" /></span>
					
					<div class="content">
						<header class="major">
							<h2>‘You know at least one person who is too all-round awesome to still be single. Care enough to help them find love?’</h2>
						</header>
<!-- 						<p>‘You know at least one person who is too all-round awesome to still be single. Care enough to help them find love?’</p> -->
					</div>
					
					<form method="get" action="{{{url('/login')}}}">
						
							<div class="12u$">
								<ul class="actions">
									<li><input type="submit" value="Tell us about your friend" class="special" /></li>
								</ul>
							</div>
						</div>
					</form>
				</div>
				<footer>
					<ul class="icons">
						<li><a href="#" class="icon alt fa-twitter"><span class="label">Twitter</span></a></li>
						<li><a href="#" class="icon alt fa-facebook"><span class="label">Facebook</span></a></li>
						<li><a href="#" class="icon alt fa-instagram"><span class="label">Instagram</span></a></li>
						<li><a href="#" class="icon alt fa-dribbble"><span class="label">Dribbble</span></a></li>
						<li><a href="#" class="icon alt fa-envelope"><span class="label">Email</span></a></li>
					</ul>
					<ul class="copyright">
						<li>&copy; Untitled</li>
					</ul>
				</footer>
			</section>

		<!-- Scripts -->
			<script src="@plugin_asset('LandingPagesPlugin/userinteractive/js/jquery.min.js')"></script>
			<script src="@plugin_asset('LandingPagesPlugin/userinteractive/js/jquery.scrollex.min.js')"></script>
			<script src="@plugin_asset('LandingPagesPlugin/userinteractive/js/jquery.scrolly.min.js')"></script>
			<script src="@plugin_asset('LandingPagesPlugin/userinteractive/js/skel.min.js')"></script>
			<script src="@plugin_asset('LandingPagesPlugin/userinteractive/js/util.js')"></script>
			
			<script src="@plugin_asset('LandingPagesPlugin/userinteractive/js/main.js')"></script>
			<div class="main-bg" id="header-bg" ></div>
	</body>
</html>