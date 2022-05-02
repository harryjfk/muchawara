<style type="text/css">
    a.sociallink{
    color: #fff;
    text-decoration: none;
    }
    .me {
    width: 400px;
    margin: 90px auto;
    }
    .me p,
    .me h1 {
    text-transform: uppercase;
    letter-spacing: 3px;
    text-align: center;
    }
    .me p {
    font-weight: 200;
    }
    .me span {
    font-weight: bold;
    }
    .social {
    position: fixed;
    bottom: 20px;
    }
    .social ul {
    padding: 0px;
    -webkit-transform: translate(-270px, 0);
    -moz-transform: translate(-270px, 0);
    -ms-transform: translate(-270px, 0);
    -o-transform: translate(-270px, 0);
    transform: translate(-270px, 0);
    }
    .social ul li {
    display: block;
    margin: 5px;
    background: rgba(0, 0, 0, 0.36);
    width: 300px;
    text-align: right;
    padding: 10px;
    -webkit-border-radius: 0 30px 30px 0;
    -moz-border-radius: 0 30px 30px 0;
    border-radius: 0 30px 30px 0;
    -webkit-transition: all 1s;
    -moz-transition: all 1s;
    -ms-transition: all 1s;
    -o-transition: all 1s;
    transition: all 1s;
    }
    .social ul li:hover {
    -webkit-transform: translate(110px, 0);
    -moz-transform: translate(110px, 0);
    -ms-transform: translate(110px, 0);
    -o-transform: translate(110px, 0);
    transform: translate(110px, 0);
    background: rgba(255, 255, 255, 0.4);
    }
    .social ul li:hover a {
    color: #000;
    }
    .social ul li:hover i {
    color: #fff;
    background: rgba(0, 0, 0, 0.36);
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
    -webkit-transition: all 1s;
    -moz-transition: all 1s;
    -ms-transition: all 1s;
    -o-transition: all 1s;
    transition: all 1s;
    }
    .social ul li i {
    margin-left: 10px;
    color: #000;
    background: #fff !important;
    padding: 12px;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    font-size: 12px;
    background: #ffffff ;
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
    }
</style>

<nav class="social">
    <ul>
    	@foreach($follow_us_links as $link)
        <li>
        	<a data-toggle="tooltip" target="_blank" title="{{$link->hover_text}}" class="sociallink" href="{{$link->redirect_url}}">{{$link->label}} </a>
        	{!! $link->icon_script !!}
        </li>
        @endforeach
    </ul>
</nav>