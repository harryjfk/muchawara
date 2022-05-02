<html>
    <head>
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#DA4655">
        <meta name="msapplication-navbutton-color" content="#DA4655">
        <!-- iOS Safari -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="#DA4655">
        {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">--}}
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,100' rel='stylesheet' type='text/css'>

        <style>
            @import url(https://fonts.googleapis.com/css?family=Roboto:300);
            .login-page {
            width: 360px;
            padding: 8% 0 0;
            margin: auto;
            }
            .form {
            position: relative;
            z-index: 1;
            background: #FFFFFF;
            max-width: 480px;
            margin: 0 auto 100px;
            padding: 45px;
            text-align: center;
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
            
            }
            .form input {
            font-family: "Roboto", sans-serif;
            outline: 0;
            background: #f2f2f2;
            width: 100%;
            border: 0;
            margin: 0 0 15px;
            padding: 15px;
            box-sizing: border-box;
            font-size: 14px;
            }
            .form button {
            font-family: "Roboto", sans-serif;
            text-transform: uppercase;
            outline: 0;
            background: #4CAF50;
            width: 100%;
            border: 0;
            padding: 15px;
            color: #FFFFFF;
            font-size: 14px;
            -webkit-transition: all 0.3 ease;
            transition: all 0.3 ease;
            cursor: pointer;
            }
            .form button:hover,.form button:active,.form button:focus {
            background: #43A047;
            }
            .form .message {
            margin: 15px 0 0;
            color: #b3b3b3;
            font-size: 12px;
            }
            .form .message a {
            color: #4CAF50;
            text-decoration: none;
            }
            .form .register-form {
            display: none;
            }
            .container {
            position: relative;
            z-index: 1;
            max-width: 300px;
            margin: 0 auto;
            }
            .container:before, .container:after {
            content: "";
            display: block;
            clear: both;
            }
            .container .info {
            margin: 50px auto;
            text-align: center;
            }
            .container .info h1 {
            margin: 0 0 15px;
            padding: 0;
            font-size: 36px;
            font-weight: 300;
            color: #1a1a1a;
            }
            .container .info span {
            color: #4d4d4d;
            font-size: 12px;
            }
            .container .info span a {
            color: #000000;
            text-decoration: none;
            }
            .container .info span .fa {
            color: #EF3B3A;
            }
            body {
            background: #76b852; /* fallback for old browsers */
            background: -webkit-linear-gradient(right, #76b852, #8DC26F);
            background: -moz-linear-gradient(right, #76b852, #8DC26F);
            background: -o-linear-gradient(right, #76b852, #8DC26F);
            background: linear-gradient(to left, #76b852, #8DC26F);
            font-family: "Roboto", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;   
            width:100%; height:100%; 
            background:#fff url("{{ asset('admin_assets/images/adminlogin.png') }}") center center no-repeat;
            background-attachment: fixed;
            background-size:cover;  
            position: absolute;

            }
            .login-admin-jumbotron
            {
            text-align: center;
            color: white;
            }
            .loader 
            {
                display: none;
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0px;
                left: 0px;
                padding-top: 98px;
                background: rgba(57, 156, 148, 0.26);
            }

            .loader img 
            {
                width: 50px;
            }
        </style>
    </head>
    <body >
        <div class="login-page">
            <div class="row">
                <div class="login-admin-jumbotron">
                    <h1 >Master Control Panel</h1>
                    <p class="liteoxide-panel-text">{{{$website_title}}}</p>
                </div>
            </div>
        </div>
        <div class="form">
            <div class="row" id ="error_div" style="display: none;">
                
                <div class="col-sm-12">
                    <br><div class="alert alert-danger" id="error_text_div"></div>
                </div>
               
            </div>
           
            <form class="login-form" method = "" action = "">
                <span class="loader"><img src="data:image/gif;base64,R0lGODlheAB4APcAAAAAAAEBAQICAgMDAwQEBAUFBQYGBgcHBwgICAkJCQoKCgsLCwwMDA0NDQ4ODg8PDxAQEBERERISEhMTExQUFBUVFRYWFhcXFxgYGBkZGRoaGhsbGxwcHB0dHR4eHh8fHyAgICEhISIiIiMjIyQkJCUlJSYmJicnJygoKCkpKSoqKisrKywsLC0tLS4uLi8vLzAwMDExMTIyMjMzMzQ0NDU1NTY2Njc3Nzg4ODk5OTo6Ojs7Ozw8PD09PT4+Pj8/P0BAQEFBQUJCQkNDQ0REREVFRUZGRkdHR0hISElJSUpKSktLS0xMTE1NTU5OTk9PT1BQUFFRUVJSUlNTU1RUVFVVVVZWVldXV1hYWFlZWVpaWltbW1xcXF1dXV5eXl9fX2BgYGFhYWJiYmNjY2BqZ0qTfTO5kS/BlS/BlS/BlS/BlS/BlS/BlS/BlS/BlS/BlS/BlS/BlS/Bli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/CljDCljLDlzXDmTfEmjnEmzrFmzvFnD3GnT7GnT/HnkHHn0HHn0LHn0LIn0PIoETIoEXIoUXIoUXIoUbIoUbIoUbJoUbJoUbJoUfJokfJokfJoknJo0zKpE7LpVLMp1bNqVnOq1zPrF/QrmbSsW3UtXPVuHfXunvYvH7ZvYHZv4TawIfbworcw4zdxJDexpPfyJXfyZbgypjgy5rhzJ3izaDjz6Pk0aTk0afl0qnm1K3n1rDo17Tp2bnr3L7s3sLt4MXu4sfv48rw5Mzw5s7x59Hy6NXz6tf069r07N317uD27+P38eb48uv59Oz59e769u/69/D69/L7+PP7+fb8+vn9/P3+/v7+/v7//////////////////////////////////////////////////////////////////////////////////////////////////////////////////yH/C05FVFNDQVBFMi4wAwEAAAAh+QQJBADxACwAAAAAeAB4AAAI/gDjCRxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzatzIseNAXh5DitQYydjIkygfJvsTqVvKlzALzvrzB2TMmykj0WyJs2fIlTT/zPJJdOPM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////////////////////////////////////////////////CP4A5wkcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY4a85szZNJk4D04r5gsWLFOVggoV6hNWrmTcMCbNGdFZz6FQo0pddXRpRF68mDbklswWUKlgww5dVQyis0qztCq8luur2LdvTfm6xpAbqkqr1B5MBguu379onSmcJVQvQWd9ASuGC+umwWBDBeu9lnix5be+rM6b5rZSML2+LouGiyqZQG6roNrSOi316NdiZ13DFTVvzmCdYeuGWhlqTlu7g0c9lVuo45fcegsX7loqMZjXmi8XrhwqLuTFp8Nm9RaWS9TCTf7BwpqsZs1kWHFJ91vq7tuW4HWPP76QK6/qUl35pasyvmhTuEhG0WzZBfXKX6apBNx/vGhmETfEuDdUKgVGlVVKkF1mSoMf4TYhYN6hNI1oHIbEzYIHAmYKStxUGJYpCZKEmGX8lUTYYqvUSFKLlgk4UjKWrYYSfnBdOJJdiz2HEi+ipUUSbYoZadKIoqFC0jWLCXkSkqM56NGCf4WI0o2KiTcLL8F4yRGVf62ipkjExAXLmcHYlBKRUpnyZkicCWUmmna+hCVgSqKUTJ305QSlX2IatpKLUCXq6Elx/nXdpCvhCZWemPYH2GedpgTkX3uGChKYbzlp6kkSvhXjqv5X/rUirCad5ZeWtI7EpF+v5hoSme/5qpAzPF1KkaZ+CjsQN84Ek8tPQzUqEaRBSWuqM7YgOytFf6kK665wWfSXlKuC+5aOELH5VqGwVgqXj2b9Be+18lJk67u+3vvWvA6pK1av9PrFr0Pj+spNvdz6RW7A+CZcpK/u7lsRtWj5aq5Y6EKEbLW+oiqWRcC+6OvGQVl0cViSdtpqWLZRFLFYoK46KFy4SuRvWN6GmiFcMTscLqzrhTXwQyRXMrRaM8NVakMng2Vsp01PhdGocHEa6sphLTwRxZVorZa+b6UskccvLi1T0GBZmdHLYnmdE9thPf0g12obxuNfGVdEdv5YPWu1KGNrKna0Szf/21HRlVjNFNppewR3WNbCtHdY7G6EdduKKlZ3R2DDVXlLj4cF8EaIB/W5SlSHKVLhb+VCONfGjfT3XzVTapnbG/kHmJssTi5WyyOx/hYqg2/U2mVi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0a3n1rHo17Tp2bfq27rr3Lzr3b7s38Lt4cXu4sfv48jv5Mrw5cvw5c3w5s/x59Tz6dn07N727uP38ef48+n59Oz59e769u/69/H7+PP7+fn9/P7+/v7+/v7+/v///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////wj+ANsJHEiwoMGDCBMqXMiwocOHECNKnEixosWLGDNq3Mixo8ePIEOKHEmypMmTKFOqXMmypcuXMGOGtKasZjSZOA1G+3Vr1apUgoIKHboKVi9l1nKu3Amr1NCnUKGWuuVMKUlnTaNq3TpUEyxiVjtG6+WUq9mzpXQlDWsxGqyzcON6vck2otu4ePHColt3obW3eQPHVds3YS9NghPD1fSrcEFnQBVLPrtqbeFfiCdr5qoJbN2/m0ObhcXWWmTRqKOmsizTWebUsJ+Wqtr6dezbQTXRfukataZVuoInGz78ly5Xo3zvZtlbsyZXvZYvJAYruXPpKE1rXuWZItbrLE/+J96r0Zou64lXqwQsWBNhj7oku0pJTLEr1h6jrVLc66Q123I1VtJhiSVj0n6BrcJXSda4IlgpJf0iWH8rsYeXLiP9F5iALPUi2ILwBUahSxLmtUpI0QRGWkwl4mXgRxaetaJMLcJ1Yn55zaeUgy56FCNn+MWkYVw3bmRNXiMqVR9eIF4UH5F93YLXLQlZ051DAHKFXU7WoGcWhAVZ84uDMza0JFwYXoaXZzvxGFSTCv2olSZBhpWlVtCJF1SZDd0ZFZWOtfPkVp+sgspWcCZ05lmJWpViVIUeypWOD8kZVZGBWojKKqDE9eJDZZ3FYaDJCLJpp3hh2tCRiwU6kDP+t6Aa2KcOLcoVoIVRF6pgqUg0qFm0WkWdn3mN6hCCjLJ1C7GBjTIRspx9pguzeBnrEJR93TWZJhOxehau2UIbWJoRlQqXtXUlo+didTJk7lnBOvaLl2eRW66nrhY0bVztuotvvgRZI+VoFb0LLMA6uRlVo/7CFS/C7SQj7p4WGczVwxC3Qwy9DDPkrVlJZlwQgYKoOtFgIlc5LcYQYZsyQh0f2+rLIlkK1ZY0a/QrV+jmnJHFW/Hpc3lxgTl0R+tqhfPRFQ0sI9McAb1Vv1BLRK1Q9lZtkcLRBkr1R7byLC9jKV0dlNGlZVZKzx85/W1fMa5dkjN5sfyS1ILIPdL+xFuV8jVLu0rF9s95CQ3Tzlrp/RHfW10JU41nUdoR3nM63hLk8IbE+FaDm4S5WZJ7RPeGLjXH5Ehux9V5SMmYPVTWH3UpGOwkpQ6XeiSFDVcpdnMEWWJLe2R7XLD87aRitIu0uVlkfySxYr2mNGRiq6z+kJiB5zWK8aK7nvh7FEWz7LbBj2S6ZKv0EjNByuiyfI8u6a5YKcARU9OrNfXifmrWk/Q5brDp39y8B8C8dEYpvyugcsJijfcpMC6pWF9LEPfAwgUqgRXEywFdZZ4MEo97OYkG1zw4lFT0rjDqIuFQRiFAFDowNSysmrYKCIsTvswavUiaZlLxCxDmTEw1IwxMKm5BDB9WLRm96Mnt6mdErR3EGsQhhi5+MRwJOvGKWMyiFrfIxS568YtgDKMYx/iRgAAAIfkECQQA9gAsAAAAAHgAeACHAAAAAQEBAgICAwMDBAQEBQUFBgYGBwcHCAgICQkJCgoKCwsLDAwMDQ0NDg4ODw8PEBAQEREREhISExMTFBQUFRUVFhYWFxcXGBgYGRkZGhoaGxsbHBwcHR0dHh4eHx8fICAgISEhIiIiIyMjJCQkJSUlJiYmJycnKCgoKSkpKioqKysrLCwsLS0tLi4uLy8vMDAwMTExMjIyMzMzNDQ0NTU1NjY2Nzc3ODg4OTk5Ojo6Ozs7PDw8PT09Pj4+Pz8/QEBAQUFBQkJCQ0NDRERERUVFRkZGR0dHSEhISUlJSkpKS0tLTExMTU1NTk5OT09PUFBQUVFRUlJSU1NTVFRUVVVVVlZWV1dXWFhYWVlZWlpaW1tbXFxcXV1dXl5eX19fYGBgYWFhW25oQp2CMb2TL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWMMKWMcKXMcKXMsKXM8OYM8OYNMOYNMOYNcOZNcOZNsOZOMSaOcSbOsSbO8WcPMWcPcWcPcWdPsadQMaeRMegR8iiS8qkUMumVc2pXM+sY9GwadOzbtS2ctW4dda5dte6d9e6ede7e9i8fNi9ftm+gNq/g9rAhdvBiNzCidzDjN3Fj97Gkt/Ild/JmODLmuHMneLNouPQpeTRp+XSqubUrefVsOfXs+nYt+rbvezewO3fxu7iyu/kzPDl0PHn1PLp2PTr2/Xt3/bv5Pfx5/jz6vn07fn17/r28Pr38fr38fr38fr38fv48/v49vz6+P37/P79/v7+/v7+/v7+/v7+/v7+/v7+/v7+/v7+/v7+/v7+/v7+////////////////////////////////////////////////////////////////////////CP4A7QkcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcufLbM2LAXrEaxLOnT1avgBF79o2mym/LeKnyybSpU1W8lhU1GvJbsldOs2pt+irZVKoan9kKtbWs2UGhbD0De/HZzrNwzbJayzai27h4z86t29Aa1ryAzb76ytcgMbKBE28NRaxwQWtLFUveqopw3WWIJ2t2GopuXWCbQ2sFxvbbX9GomdqyDPNb5NSwe1ae6Tq2bdmsa77WDIoVq13AgD17FnyX71OiZ7usPRmUK2S5D1pD5gqUZuW6JZ/atczisl3IFf5jT2lLMajGG4FZT2xr5a7EoIBFt/gNdOJdKZclrmVNpLVaiXVnkjWZxQWKNCY9sx5eofRX0m5wqeKgSdJAeJYqJdmHlyvzVfUWXqT5V+BZ+LX0HoMTfnQaXMjAhExer4T0TF4lwnRiXAJ6hMqGNLmCFyofvRjXeK1ZuFWOG31oFigpymTNgnJ1NGNcCIIlDV6eZeQjXDWCBSBcrmz0jYEdtgZlWWU+JORZIfKloVktZmSkU6CkaWaEGVkTV5yOrWlWkxP5udUpjhEUHpwYfWlWm4XeWFYtGO14VpWF2nPlWUBapOdZoFRK0JlaAQqRfmdB6qlAipaVpURvHnmqQP6kLmpRqlvZadSYJFqkpFasvDrQrln1WtGIwfoqELBOZUoRXKb6SqtWFsHF6KmtQluRtMbaU21WFV1qFpKnxlqWqA1NadaqnpqrKkXqboUuTc+i1uxB3q7LV7ybzYsQtoXhK5m++7JZqL+BARywWV3Wxdx1toLqlLCOLSyerfYg2xTEEc8ZIcX2ENxTKKdKjBeRDG3rFLiFSeNwWQdKJO5WCTumMl4tS1TvVsp6OjOnlEq0clM9V7rzVjVTtKVZ6L0qqFN8UkQMnsYu7VPTFN3sbrZSD0J1RYeWlbPSTW1d0dMsZtuxTwZXtCmmHBv1ZdoWadzUtK/WArdFWXNGrv7ZGnVdFsl8a5S3UzEG/pHfZbVneEeDM714R3I79W5KbY9kNaItvSi2iYDxwhIvPW1eE+KCVU7filrPdLlZEg5opOgqkZ0XYyQBQ2zoMx2dFyrJgJSMpFspXqR4k7MbuU/Cv/QN6XGxkszefSVjcfAyDS0eMEEvJA0wx1Mfk/WShcKKLcENJ400wwVnCyvATxYzS+DfhlrRMEnDvPySqZK98t3jvzFY39Cd/xLDIb7IboCASRpf7IdAvJxif6XxWAN5UgvTveQZ/ZOfKopXKGL8DH/nWdw3iHG/2JyCGBasCzIyKB7YGU4a4IHNdiD4uIJsb3rNw14NJ3IT4xxPFSOsEAoHdzgR9A3niNAjohKXyMQmOvGJUIyiFKdIxSpakYgBAQAh+QQJBAD0ACwAAAAAeAB4AIcAAAABAQECAgIDAwMEBAQFBQUGBgYHBwcICAgJCQkKCgoLCwsMDAwNDQ0ODg4PDw8QEBARERESEhITExMUFBQVFRUWFhYXFxcYGBgZGRkaGhobGxscHBwdHR0eHh4fHx8gICAhISEiIiIjIyMkJCQlJSUmJiYnJycoKCgpKSkqKiorKyssLCwtLS0uLi4vLy8wMDAxMTEyMjIzMzM0NDQ1NTU2NjY3Nzc4ODg5OTk6Ojo7Ozs8PDw9PT0+Pj4/Pz9AQEBBQUFCQkJDQ0NERERFRUVGRkZHR0dISEhJSUlKSkpLS0tMTExNTU1OTk5PT09QUFBRUVFSUlJTU1NUVFRVVVVWVlZXV1dYWFhZWVlaWlpbW1tcXFxdXV1eXl5fX19gYGBhYWFiYmJjY2Ndb2pGmYAyvJMvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYwwpYxwpc0w5g2xJk5xJs7xZw9xZ0/xp5Bx59Dx6BFyKFIyaJKyaNMyqRPy6ZRzKdTzKhVzalWzapYzqpazqxez61g0K5i0K9k0bBn0rJt1LVz1bh417t+2b6D2sCH28KK3MSM3cWO3cWQ3seS38iU38mV4MmW4MqY4Mub4cyf4s6i49Cm5NKp5tOs5tWw6Ne06dm26dq46tu669y8692/7N/C7eDF7uLH7uPI7+TJ7+TK8OXO8efU8unY9Ovd9e7h9vDm9/Lo+PPt+vbz+/j0+/n1/Pr3/Pv4/fv5/fz6/fz9/v7+/v7+/v7+/v7+/v7+/v7+/v7+/v7///////////////////////////////////////////////////////////////////////////////////////8I/gDpCRxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzatzIsaPHjyBDihxJsqTJkyhTqlzJsqXLlzBjgsQmreY2mTgNbpM2jFaqQkCDCk316tUtZthyspR2a5TQp1ChjqJlLKnSkcxocYrKtavQUbeqXe247ZdTr2jTjhpmdWxFbFrTyp1Lq63bh3Dn6tVb927DsnsD873pF6GxrYITp+U0rHDBba8US5abSqxjZogna+7KyZjfbbQ2i0ZLi7DSbT9Hq+aayrTMamdXy346ynLMapln6wbKybZL3KpFxfJlrKZxX8hf5dbc+yVwzaJoMXtYTVis5Yk52U35XDEnWr4l/hqLPLn1ym2xBXPy5dpitbiKX62UJdnXx23CsM+9lVJY/O1kxaLYdCZJk50wJh2mXnsgoSdYLAyKhA15e8lSki+C0cKSf4F5JqFgHrJkTGCcRLgRhXOF2BKGe9kHkoF7qehSaHsBqBGKacn4Eo10fQTjXLFcJaBeNl6Eo1clXoWNflxpyFE1e0njFodzmUjRLXrxd9eRXbmoEZNQiWLlbXpxshEzeul4FYtyEYjRkIs5JhCYTzmJkV5eFoalXGZi9GNaRSqpl5sV7ZmWnXJyGZWWFqWWlpRyCjQiZRdhMxcqkRJEp1BjMvSnV4hGaihahErEJlpqFoamXHlKBCda/oHeZalc8lXkKJKZFrQpULVSBGSuBCkKlUVztZrpqF51mtCnXUEKLD2TPkoRs1w5Cyy1UVkLEbZQaZspt0956xC4QokrJ7lBmevpXOoWhi5Q7Sr0biHx3jVvvQjd+6xA+k7U77PRolURlKzuS8+pXhErV5D7CvuURbsWkorBEfc6kcNC3WWMiQQfahGPaOG7UmiybFwQlWghWBHKXqUKU8eFyMKWQCB7JTJCMHs1sVtHpjIMK1VeFHEh4eHEbSmuhOIVphe9CuqWaCENyqIYscyZsiLOFYorpZSL0axyuQyTKHtt3bVGqMy181gB6yVKWBi17VWpOZHtnXQVbVMm/tYrWa0e3hLV7BWjpw29mLEL5RyyWwh7J3ZCGH/l1jaGR7Ue3wbNCxTiMSG7V2kXpZ3mWGDv9UqsD2kO1OMsCb70zQ05naOgconCukSl81k0TLI/tV5IjaPFCewlgXsL5hNtY/devyjFZV8kKU7Z7ks99QrxFwUvV/MyiS4K3SVFztX1MR12e4PLCzaKyRqNhzw973vUXXa3oL5QWWdx7pfqXb3yC+zYGMatCpEkYMltMv4bRk1sUxNm/OIV6anTvg64G7nYj22Vq2AhQpWp+WkQUAbDhug+6DGD0cN1JITKBTWWwdlwEFigSaFXVri/9MkwKC+coA1vSEPHGGOHTxoknwkRkhUSQm+ICtmGMUaoGlQII34m3AYzbsHExMSCfUjMmzR88QogQsUowsBeFvHSQF8YR4xjTKMa18jGNrrxjXCMoxznSMc62pEkAQEAIfkECQQA8QAsAAAAAHgAeACHAAAAAQEBAgICAwMDBAQEBQUFBgYGBwcHCAgICQkJCgoKCwsLDAwMDQ0NDg4ODw8PEBAQEREREhISExMTFBQUFRUVFhYWFxcXGBgYGRkZGhoaGxsbHBwcHR0dHh4eHx8fICAgISEhIiIiIyMjJCQkJSUlJiYmJycnKCgoKSkpKioqKysrLCwsLS0tLi4uLy8vMDAwMTExMjIyMzMzNDQ0NTU1NjY2Nzc3ODg4OTk5Ojo6Ozs7PDw8PT09Pj4+Pz8/QEBAQUFBQkJCQ0NDRERERUVFRkZGR0dHSEhISUlJSkpKS0tLTExMTU1NTk5OT09PUFBQUVFRUlJSU1NTVFRUVVVVVlZWV1dXWFhYWVlZWlpaW1tbXFxcXV1dXl5eX19fYGBgYWFhYmJiY2NjZGRkZWVlZmZmZ2dnaGhoaWlpampqa2traHFuWIh6SZ6FPq2MNriRMr6UMMCVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWMcKXNMOYNsOZOsSbPcWdRMegSsmjT8umU8yoVc2pWc6rXM+sX9CuYdCvY9GwZdGxZ9KyaNOzatOzbNS1cNW3ctW4dda5eNe7fNi9ftm+gdq/hdvBiNzDjd3Fkd7Hk9/Ild/Jl+DKl+DKmODLm+HMneLOoOPPpOTRq+bUsOjXuOrbu+vdvuzfwe3gxe7iye/ky/DlzvHmz/Hn0vLo1fPq2fTs3PXt3vXu4Pbv4/fx5Pfx6Pjz7fr28vv49/z6+v38/f7+/v7+/v7+/v7+/v7+////////////////////////////////////////////////////////////////////////////////////////////////////////////////////CP4A4wkcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY4p05gybzJsHpx0LJouVKEhAgwIlxYqVrGDOcLJUtouV0KdQo7JCqnTktmO3fkbdylWoLGVVO07L2rWsWUikim0Le/GY07Nwy4q6ZZNtRGek4uo9K6uu3YXO3u4d3FVUsb8Jt+0izNjsqWmIC0471bhy2V1rIwezzLkwZLvbZHUezfVw2G2USauGeivzzWlaV8sOeso1TNizc9O23RL3aFGsdAFTRpNmMWDAbOUdXfulb8uyin1muE2ZrtSVSflVuW05Y1G6kv5WnGbLcvOVggeLAsa74jZg3gezWqmLMSn2H4E1vpXyGGPTIWGTnl4AkoRNbHGJMt1IyiAIlyjbiTTgWay0JxI22MV1SknFDAbMSttMaNaHFzpo1jEulbdXhB2J2BWKL+mnlywhObOXLjL5p5d4Hrm4lS04qQjXfB7ZqKFSqO3oUXxlkWLhSwfGReRGRsLFo1LK6MWiRUKaRaNd9cGFo0bYJLjlTduYuJUoG8l4Fol/uWkWWBkxyRUpkcWTJlxAYlSmlXnGI2dhGXV4Fp6B7nnWghT5+NSYgXbZFZwVxcUoYlWWNSVFmXaFaKACPXiRoSOCOpCjQl0KUZhmnRknXP4wNnoWm6YK1ClXlEo0ZK0DqQnVlxTBBWytqAK1aUTbwJUrqJKuWdGtW8Va66BcPQsor4LCZe2i2GZ71rZmXWlqlmeJ+xC0UZkbKLpQqdsQu0+5Gxm8QskLGKzdkmuWqg1No2y31G5l0b/YNitwRb4+2m2xQFnE8IbY2imVw3A9iZiwFgUcFZ2m+vumRfqWBSmorJbF8UTJngWxqQk/5apDEkf1clUhE3qRaGdJG5nBUfVpkY5mfZpny0LpPNGfOcukDL8CAW2WxRBl6KlMxYhitEDdwbXyqHGd3JKRsrSncVQFWoS0Wcey5DFaC54tF9QRMQyU1yw9FcxAOJ/lc/5GNRc2c0kOVkjvU0xTFHNU57U0IXBSejR2VPy5lPdTcttLkaL4tjR2KaVoChKpsxY+EuhQiaJKV5ZbdHhUoqQO0uCphNKzSE5nntLaXY3SuVAQjjQ5XLusJKVQZX+EeVyRo0S0UKeMAknaIPWt8t8cyQ0UcNRrxLNZwcC90e9nBW9SkoSR4jpGj3P12PjLc8UK3R2RnuDVHz1XvlqvVxZ2SdLvJUp0opMI7soXwIzYrzL/C0YxihMPbNBEGVR5SGfuRpID6mZvDGmf+mSBnwpq0DLQS4j1gHIKWyxwJRjSTVBo5RDwQSU4xyggSbaxPdJ4byDUAs5wsqcS+a3mfGzx6BApOFiTQE1jhHspXmJuyJaqzWZk3XpIaGQTwigyBC+qEZoVI3IMqVVmixYhT2eACMaDvGd1JyojRqZRDFl8MCjLUuN42sgKND4PinLkyDaKUxwZ5vGPgAykIAdJyEIa8pCITKQiF5nIgAAAIfkECQQA9QAsAAAAAHgAeACHAAAAAQEBAgICAwMDBAQEBQUFBgYGBwcHCAgICQkJCgoKCwsLDAwMDQ0NDg4ODw8PEBAQEREREhISExMTFBQUFRUVFhYWFxcXGBgYGRkZGhoaGxsbHBwcHR0dHh4eHx8fICAgISEhIiIiIyMjJCQkJSUlJiYmJycnKCgoKSkpKioqKysrLCwsLS0tLi4uLy8vMDAwMTExMjIyMzMzNDQ0NTU1NjY2Nzc3ODg4OTk5Ojo6Ozs7PDw8PT09Pj4+Pz8/QEBAQUFBQkJCQ0NDRERERUVFRkZGR0dHSEhISUlJSkpKS0tLTExMTU1NTk5OT09PUFBQUVFRUlJSU1NTVFRUVVVVVlZWV1dXWFhYWVlZWlpaW1tbXFxcXV1dXl5eX19fYGBgYWFhYmJiY2NjTYt5NLiRL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWMcKXNMOYNsSZOMSaOsWbPMWcPcWcP8aeQsefRsihScmjTMqkTsulUMumUcunUsynU8yoVcypVs2pWc6rXc+tY9GwaNKybdS1cdW3dNa4d9e6e9i8ftm9gNm+gtq/hNrAh9vCi93Ej97Gk9/IleDJmeHLn+LOpOTRqOXTq+bUrefVr+fWsOjXsujYtunauOrbuuvcvOvdv+zfwe3gwu3hxe7iyO/kzPDlz/Hn0vLp1PPp1vPq1/Pr2/Tt3vXu4fbw5ffy6fj07Pn17vr28Pr38fr38vv48vv48/v49Pv59vz69/z6+Pz7+f38/v7+/v7+/v7+/v7+/v7+/v7+/v7+/v7+////////////////////////////////////////////////////////////////////////////////////CP4A6wkcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY4aMxoxZNZk4DXI7pkuVT0JAgwbV5FOXsGg5V+60dUqo06dQVdlilpSksKZQs2oVqinWMW5VOVbTpWmr2bOENNm6GdYis1ho46JVRbVtxGiq5OqdW9fuQm629gpG24qt34PHPg1efFbX4YLVWjGebPaUYb/BylLenFVTsMOBOYvOGqstt7yjUz9VBTZnNMWqYws91TpmNM2ycxOibRv35lO2cuU6VrPmMeGoRfN2eXuzquG1FzLLlXzy8pXVfAtWe1niW8qnWP5ywzr4U7DoFrnl0q63tMrqej1/VM8+7meUoQXHQt8R8GKkJjFTXl8jHVOfWZrwNx9serGGUjXkyeXeSHDtNWFKFepF4EcC7pWLS8Hs9YmCGzFoH0z5xWVLSCFqKFOGcXVXol685ASfWRduxIterSQ1nl4AcmSiWSNWxc2BUPXI0TFAttVijBxJdqJdN2q1okbVyPXJYdHIpYmOct3nF4xmiXlRhFtt+diRcSl5ETdhPibQjnGRCNGTRMopEJxxHYORlGfVqGc9gOKI0ZBa2RkWnmm+GVd4gwqEpFOKMtThWVdGSqZWG0aUS5+RCsQkWh9WVOhWlbbVpkVVPgVpqP71xKWKRZMGlWmorTqlJkVxlQrrqVpVlCWpsAr0KVoVXWqWn8UyqpWMDim7Vad6SsspRdZmRa2c2UIVZETdPrXtY3SelSyUxR5rLrZxjXuYumadixazsMK7lbC9FksoWrtOFFeOg+Yq1KwVyapvrUC5ORGaWfU7aJfEVrRpVqlWZa9WZkp0cVaCRiqwUN969yiscjmKbrUGn5nvoClu5WtFG0PlsF9souWuQxDbrGfMT32ZEaJZEXxYzWcBTFHLW2UcFs9PKU3RsGgleBjQUEmt0cdC3WqxhBw5u1XIJYF9ULhQQWsR1VGlBGGlRJ8l9EZea2V0SALOPRDWQjl9qP5eL490LL0GMe0qSHFjbJKUmkBbuLYh4S0U4CEx+OpAi6c9016aiM0Rn0G9jPRZmm8keM96a2RtXRObpTVIDBdd8UTlDgWhYJOLlLNep4Ru0cSpDKZ7R7F7CXlGrRPieOcYLqbK8BZtFQrXKf242CfCZER2KJmgdd2DCKcpzOsIBf9U70SC71Fz4NnylUSfO4XKVpm/hD5npzCfENaaeNLZ7ybNv1npB4nL83rGv5MYSDQ3I8jtzAKf3CXlNZy5E+6C4iAjFU9LEGlfZ7K3upxwI3Upc8jxgJIKAOLkgIPpYEK655SODQ1YaHHhQqC2lwLKJDF7SeBARkU788EkM2FyMdtBRie3UKkHbUKByAifYr/DHENgb2NINJCzxLQI8TDV4EXr7Bat6VDnY1GEFTeC0QoG9c10XvwJ8vRVEG5Mx4bWm44P2UjHOtrxjnjMox73yMc++vGPgAykIAcpkYAAACH5BAkEAPAALAAAAAB4AHgAhwAAAAEBAQICAgMDAwQEBAUFBQYGBgcHBwgICAkJCQoKCgsLCwwMDA0NDQ4ODg8PDxAQEBERERISEhMTExQUFBUVFRYWFhcXFxgYGBkZGRoaGhsbGxwcHB0dHR4eHh8fHyAgICEhISIiIiMjIyQkJCUlJSYmJicnJygoKCkpKSoqKisrKywsLC0tLS4uLi8vLzAwMDExMTIyMjMzMzQ0NDU1NTY2Njc3Nzg4ODk5OTo6Ojs7Ozw8PD09PT4+Pj8/P0BAQEFBQUJCQkNDQ0REREVFRUZGRkdHR0hISElJSUpKSktLS0xMTE1NTU5OTk9PT1BQUFFRUVJSUlNTU1RUVFVVVVZWVldXV1hYWFlZWVpaWltbW1xcXF1dXV5eXl9fX2BgYGFhYWJiYmNjY2RkZF5wa0GjhTC/lC/BlS/BlS/BlS/BlS/BlS/BlS/BlS/BlS/BlS/BlS/BlS/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/CljDCljHClzHClzLClzPDmDPDmDTDmDTDmDTDmDXDmTbDmTjEmjnEmzrFmzvFnDzFnDzFnD7FnUDGnkLHn0XIoUfIoUjJokrJo0vKpE7LpVHMp1TMqFfNqlrOq13PrV7PrWDQrmHQr2PRsGXSsWfSsmnTs2zUtG7UtnDVtnLWuHbXuXrYvH/ZvoXbwYvcxI/expPfyJXfyZjgy5vhzZ/jz6Tk0anm1Kzm1a3n1q/n1rHo17Po2LXp2rjq27rr3b7s38Ht4MTu4sbu48jv5Mnv5Mvw5c3x5tDx59Ly6dTz6dbz6tj069n07Nz17eD27+L38OT38ej48+z59e769vL7+PX8+vn9/P3+/v7+/v///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////wj+AOEJHEiwoMGDCBMqXMiwocOHECNKnEixosWLGDNq3Mixo8ePIEOKHEmypMmTKFOqXMmypcuXMGOGbEaTJjeZOAtO6wVLlao/QIMK/UMKlq5mN3Oq3Anr0tCnUIN+snVMKcljTaNq3frnEq2qVjlOs+WUq9mtn3QlDVux2c+zcLl6xcZWotu4eM/SoluX4bS3eQNzVdsXITdbghObvSSscMFjnxRL5gprbV/EkzNrvQSWLTfAmkM/pcV2WlnRqIeqshzTdOrXQ0nxjSnsNOzbl6bRRu2Jlq6jNWvqokVKdO6X0zT3Fjab4TFbnjIfZ+laMi3dFafRsiT5EmuT3Ir+J7ZEOCM3XdETk/pOEjReT409nldMGqWuxLpG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////////////////////////////////////////////////////////////CP4A4wkcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY35UVqymTZk4C1ILZuvUqUZAgwoFaupUq17Pcqp81kvVqKFQowoddTSp0pDPcpmSyrVr0FG9qF3lGOyn17NePcWyOpYiNVtP0co9eypYW4m5PM3di/aUsrsNe8XlS9hrLLGAD1JTVbjx2VF2ExMMptex5a6q2N6l1uqy566eegGmtvWzaamx2iqrfLr10FOIcVJ2TXuoKc0vi9XeLdTTX5irT5uKZStYTWXUbNrqedr3y+CXhweLzVBZLsaXnbOE3lgUUovPbP51sjyK+knSjr1z7FW6sCnzJTsXrguy1/jCrVLmKtwpckhqrDRmy0nKuIdbSMWIUthvJLU3FyvwiYQeX6aUZAtfnYi2UoB8pSZSgXt1wuBKsRA2okdmzVUMTL3wdUpILe6lIYt8rfjRYGgNiFOJc1XoUYxysXJVimjNuBGOXvmoFDX39dURkGidiJNucx1oEZGGAbZfkBs9M1cnEebUZFphSsRjkZJdKJd/FyHJ1YuSMcklRiCiZWOacnmSkZpniSLZQHLaiZGDXeXy50BneqVjRdRUeahAVNJ1UaRJPjpQnhfx6ZWhlsaDJVdSQsThWVYmtuVZRkpEqFR+dhpPnf6KWiQXnK6OyZUqsqIlpKueOkmRlznyGg92Xo1SEaVdsWmppl0dK9ednTLLlbNolZmYtFJRexa0lkLZlbUKIcsVt4+KKxW5DpkbFbp/qgsVuwy5O5Syj57qVanVybVotHLleta+lhLrlUW2DtXJKazAm9inUbVKEZGinBJLLsWA+ydrXdE6EXG9KCxsPHN+HFIw+oos0qheeWyyRRh/u/JH3nLl8MscCdzVrjRv1OiaOXNk78A9a0RNy7cGrRHKXqVq9ETyGrw0RgyjploxudBLUsxd4Usg1baocgprnWj90TNEv5kSTbl0/XXSKU0ol9gXUZO212V7hfNJq9480v7O3VnckXyOjqRgY6GKhG1XHo5k81wAX82XKH5jdLhXGpPEGWEqa9R0VGCe9EzedntO2CmZZzQbhpFrVHDGQLWS+kTUJLpX4SFFHVUpvXHa0Vt1o6rS5AaDAhVYryvEu2N3n0RyX1ypBXe4rfSO1nsrwcoV6K/FMl26zHlGPUtnhcIJhcTZ0rFNPMWytmnfs2Q7UKTw9ln7LMn+mvzePz8S1kThfxlsMbFeUDoRCv8hLydSeZ8BudIJ3clkVWtZ3AK7cpurIE1JwRjcBLvSOJnYS0QEecsGpZKZu1BKaa9SIG9IJ5mg4CohxUAa/lj4JwVBjiHhWd1pOrGWgDWCdkAHYY9rOsEKq6WpgwzZSSywd5aD2QKIccLITnriEwb6xHxQfFpEaFIM/Wnxi2AMoxjHSMYymvGMaEyjGtc4kYAAACH5BAkEAPYALAAAAAB4AHgAhwAAAAEBAQICAgMDAwQEBAUFBQYGBgcHBwgICAkJCQoKCgsLCwwMDA0NDQ4ODg8PDxAQEBERERISEhMTExQUFBUVFRYWFhcXFxgYGBkZGRoaGhsbGxwcHB0dHR4eHh8fHyAgICEhISIiIiMjIyQkJCUlJSYmJicnJygoKCkpKSoqKisrKywsLC0tLS4uLi8vLzAwMDExMTIyMjMzMzQ0NDU1NTY2Njc3Nzg4ODk5OTo6Ojs7Ozw8PD09PT4+Pj8/P0BAQEFBQUJCQkNDQ0REREVFRUZGRkdHR0hISElJSUpKSktLS0xMTE1NTU5OTk9PT1BQUFFRUVJSUlNTU1RUVFVVVVZWVldXV1hYWFlZWVpaWltbW1xcXF1dXV5eXl9fX2BgYGFhYWJiYmNjY2BqZ0aZgDG9ky/BlS/BlS/BlS/BlS/BlS/BlS/BlS/BlS/BlS/BlS/BlS/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/Cli/CljDCljLClzPDmDXDmDbDmTfEmjjEmjjEmjjEmjnEmjnEmjnEmznEmzrEmzrEmzvEmzvFnDzFnDzFnD3FnD3FnT7FnT/GnUHHn0THoEXIoUjJokrJo0zKpE7KpVDLplLMp1TMqFjNqmLQr2rTs3LVt3fXun7ZvYLawIbbwovdxJDex5TfyZbgypvhzJ/izqLj0KTk0abk0qjl06rm1Kzm1a7n1q/n1rDo17Lo2LPp2bbp2rvr3cDt38Tu4cfv48jv5Mvw5czw5s7x5tDx59Ly6NTz6dbz6tf069n07N317uD27+H28OL38OT38eb48uj48+v59Oz59e/69vT7+fb8+vn9+/v+/f7+/v7+/v7+/v7+/v7+/v7+/v7+/v7+/v7+/v7+/v///////////////////////////////////////////////////////////////////////wj+AO0JHEiwoMGDCBMqXMiwocOHECNKnEixosWLGDNq3Mixo8ePIEOKHEmypMmTKFOqXMmypcuXMGN+3Naspk1qMnMSpNYr1itUgIIKHRr0VaxczcDpVMkz1ieiUKMORRWrmNKlIbfleiq1q9egrnptw8qx2ayvaNPGSkb2YjFXaeOmRdWrrcRmQOXqRYuqmN2G4GLtHTy32d+EvbgSXux11tXDAqm9Ykz5KyrDkHtV3vzV1l9wZzmLlvrqsc5tcEerhupqrE5qilfLDvoJp0zYs3MLrR0TN+dOR3vZtAfOZi5brUbzdoksNuFOs3rZBowM+eblK30vPtXLtMRtvU7+UUbl3SQ453pfYdZITPziVyrBpR58an3H9os9oww9OFf5+4sRcxIyhL0y3UjUuKfXJ66NdN5gs6QU2GDwkSTYXrmwpNledYlEzGACtkTggg1+lFdcnSAD04d6RQjShige6FIue9m30YNyhSgTf2lV2BGNcrmo02Ry2ZjRiWi18t9L4CiIlo/sydVJiTo1o5eMFxGZlop2XZiWkBhtIxeUZG3TiVxLSgQjWkZiBWRaHWI031enQEack165kpGYcel4GItppfkQoHTaOdCZaflJkZdfKXoYj17FghGSXgmK1YhofXIROHG1YihBeHZFZURWpqXfp/bYEheXFL35Fav+n1ITV4YVNZPLLFpKZWlbcUmqEU292PKKgp6iOlCoUZHZUTPEwIpqrl4Zu19c0p7karTVloTpV21mu2xc3Xq7UalsiushuOaGdG1X6apLbbsfrSsVvB9BKlWd9HYELWn5dtRrv+POCrBG8kbl6ESS5ZLMqNnuKxWWE7n6ySu5FAOxoSgS7NUrtgj3KblfKUuRw1K5El1SdtkrFa0YkczXUc0wzBKncV0sEWet2JxSwVB1stFmjsmEI1pgWgTyYCnqxDNUztbKWCsyz4yeVJ3s6tDSXrGck6pxnYoR1lF1Eq6IekUtkcsla4PVNlPzuxHaUE2mp07y6TX2zXGtoor+UEW/pHJXIlsU1yucEOW1S4SmdTepaHUy51AHp8S1XL4G7NUqpngVuUl/e2V2xBunpfVJdWPoUcGOj2n1jY+nVay+UblS+IKLa1RM215N+dG+vSQu1+gebQN3V5tfRFTOAk2+F12rRwR2V33/LJQtpg3f1Sf+aQROLpSmF1KpSRfUJGWf2FJ7QtR03mnzdwHyiszUIEp+LNI5tE0xtrQ+mJIi5QJ8QfFTDscUZpNm9MJ/seje09g3EasFUDe54d9t5AfB0UgwJ+BITgVFE72YgGaDmyleTJ4HwqicQmc5oYYGS9g1Br5EeSyUCvKk1QzrQbATccpWM1YYQ0DE4nNPnyKGDX8zCyBKK30UlA13XIgqcBBjFkncTCyaNjCCNENYlKHY+aoYGWLk4hWviGJQTgFGmHGxLCg7oxrXyMY2uvGNcIyjHOdIxzraUSIBAQAh+QQJBAD0ACwAAAAAeAB4AIcAAAABAQECAgIDAwMEBAQFBQUGBgYHBwcICAgJCQkKCgoLCwsMDAwNDQ0ODg4PDw8QEBARERESEhITExMUFBQVFRUWFhYXFxcYGBgZGRkaGhobGxscHBwdHR0eHh4fHx8gICAhISEiIiIjIyMkJCQlJSUmJiYnJycoKCgpKSkqKiorKyssLCwtLS0uLi4vLy8wMDAxMTEyMjIzMzM0NDQ1NTU2NjY3Nzc4ODg5OTk6Ojo7Ozs8PDw9PT0+Pj4/Pz9AQEBBQUFCQkJDQ0NERERFRUVGRkZHR0dISEhJSUlKSkpLS0tMTExNTU1OTk5PT09QUFBRUVFSUlJTU1NUVFRVVVVWVlZXV1dYWFhZWVlaWlpbW1tcXFxdXV1eXl5fX19gYGBhYWFiYmJjY2NkZGRlZWVmZmZnZ2doaGhpaWlqampra2tsbGxtbW1ubm5vb29gg3hOm4Q+r401upIxv5QvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYxwpc1w5k3xJo5xJs7xZw9xZw+xp1Axp5Cx59Dx59FyKBGyKFIyaJKyaNLyqNMyqROyqVPy6VQy6ZTzKdXzalazqtczqxez61fz65h0K9j0bBn0rJr07Rv1LZx1bd21rp92L2F28GK3MON3cWR3seW4Mqf4s6j5NGo5dOw6Ne46tu9697C7eHH7+PJ7+TJ7+TL8OXO8ebQ8ejT8unV8+rY9Ovb9e3e9u7h9vDk9/Hm+PLo+PPp+fTr+fXv+vbw+vfx+vfy+/j0+/n1/Pn3/Pr4/Pv4/Pv4/fv5/fv6/fz+/v7+/v7+/v7+/v7+/v7+/v7+/v7///////////////////////////////////////////////////////////////////////////////////////8I/gDpCRxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzatzIsaPHjyBDihxJsqTJkyhTqlzJsqXLlzBjfpyWLJkwX8RqJtMms6dAbcR2zTLVqKjRo0Zn7QKWzKdKoLtaIZ1KFektYTydhtQmTGrVr2CLtgKWVatGYrnCql2ba5rZi8KIrp2rdlbTtxHj0t27ttVdvAuTyeVLOOwtt4APaktbuLFaX4kLCvPkuHLYVs4iL7bMOSxkvNO8dh5NdVZZn84ok149FbNTYaxjT/WUWSZs2biPCot5e/QsX0yT1aZXk5gvW6VIE3uZrPPvvw2nAZvFmXbL1JU77UJMURuw5I6t/j8dTLgU1o3CqDf2dNqkaL52QQrr1HhWSl+NgY3cXHjXSWeFuVZSMuDxtRxJ2pA312co4ceXKe19tAtfnUCHUm90MQjSNBQOtxKGc3no0S0GwgRiXSE1t9duMRFDH10WbmTLij4Rs5d98hUIloYyMTZXjBwJo+NUpZilzZBg4SiSkFUBGZOKc3G35JD+4TXhXFWWxGQjnUToFJJVeZKSkAcCduJXZZrkpVkvqmVLZCw5qJaYcKrE4Y91quRjWFnmaRKAffmZEphUrSnoR3uC5eShHgEzl36MkgSoWrlEWlKbSWYkpaUGqacWRsl44ouhkcoZFkbqeQIppwSZChap/gxNWpQpaXIKZViLNpRoUfGxequiFd1ZVVu24knRrlPtAitev36Vq0LChiUqo802SZGrYZnCYp7VUvUsQtqotpdfeZ7p7UTY0jXLpm9d+elE4jZGLGCeniqRuXyJumxL8X6lJEQKOqbdW9FmmhdnneRSq1PdTsVjQwHTVcouC5vlblgVK9QwWKPsImJi/X61L0H1qjWKLKo08q1P+CLVSkQbH6UKykdVmmfJX9n8EM5IqTILoewSTFfGCBUs88+e1XkxWJ1EtOtYJK5FZ2LhzqVzdEfZch49Ns61LV7pVkX0QWlpHSGmYEEImKxhNQ0RMYYuDVafWvFc1cMWGQ3W/tguOUrXyDvTxZ5WVVvtUcxWaRV1lB/ZXdXXMCGbM0iIIwV5S5J/FbRGjj8OU9hf4b2R3mGt+iGFgF9L2NUo+U0jSYSCdUvqGE3TeVVvDliYqiZNRliXJ7kO3+YaabN4iSjN2JjoF2njS8hYPvVevqNq5Ezma/2LkjNor/7x27er1QrtHHHPmSkKP+QMMLdATyHxf3bvmCm3AKdTTb7kP4v7hXXyvUrmyw1u/NcTZ8ROgI4pxf9aoo3pIbAy43sL9h5oOMC4iIL9u5yRwofBo8xigWaZTwfbZro8OW+EwyKfT6YxwdzMi1X0YKH8cPNCGA7EeQfkzFhU6Ce0zLAxOqXIBQhtaBBn7CKHYAmiMOBHRIYkAxi56FwrZqEUuDVxQzrh4RW3yMUuevGLYAyjGMdIxjKa8YxhDAgAIfkECQQA8gAsAAAAAHgAeACHAAAAAQEBAgICAwMDBAQEBQUFBgYGBwcHCAgICQkJCgoKCwsLDAwMDQ0NDg4ODw8PEBAQEREREhISExMTFBQUFRUVFhYWFxcXGBgYGRkZGhoaGxsbHBwcHR0dHh4eHx8fICAgISEhIiIiIyMjJCQkJSUlJiYmJycnKCgoKSkpKioqKysrLCwsLS0tLi4uLy8vMDAwMTExMjIyMzMzNDQ0NTU1NjY2Nzc3ODg4OTk5Ojo6Ozs7PDw8PT09Pj4+Pz8/QEBAQUFBQkJCQ0NDRERERUVFRkZGR0dHSEhISUlJSkpKS0tLTExMTU1NTk5OT09PUFBQUVFRUlJSU1NTVFRUVVVVVlZWV1dXWFhYWVlZWlpaW1tbXFxcXV1dXl5eX19fYGBgYWFhYmJiSZJ8M7qSL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GVL8GWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWL8KWMMKWMsKXNMOYNsOZN8SaOMSaOcSbOsSbOsSbOsSbO8SbO8SbO8SbO8SbO8SbO8WcPMWcPMWcPcWcPsWdP8adQMaeQcaeQ8egRcihSMiiSsmjTsqlUsunVcyoWM2qXc+tYNCuYtCvZNGwaNKybdS1ctW3dte6ede7fNi8fNi9ftm9f9m+gdq/hNrAhtvBiNvCitzEjN3Fkd7Hld/Jm+HMoOPPouPQpuXSqeXUrObVsOfXs+jZt+ravOvdwe3gxe7iyO/kye/ky/DlzfDmz/Hn0vLo1PPp2PTr2vTs3fXu4Pbv5Pfx5/jz6vn07Pn17vr28Pr38fr38vv48/v49Pv5+Pz7+v38+/79/P79/v7+/v7+////////////////////////////////////////////////////////////////////////////////////////////////////////////CP4A5QkcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY36MxqymzWgycxJk5ksXK1J+ggodGpQUK12+mOlUGc0XK6JQo0Jl5Qvn0pDRdAGVyrVrUV1Wr2oshsqr2bOoiom1yM3X1rNwvZIKxm1tRF+b4uo9uymY3YbMyu4dbBaV0r8HudEizPgsrbqIBzLL27hyV1Jh/wazzNmrX7vcXnUezfUV5KXcBJNeTRTVaZnR3rKeXTTzy2iUaev2s8l2S12jRx3t1atmNJrMiPscNbq3zFmVT/XyrTBar1OcnceEvpcVMWsWrf4Re9rY9fO4rA5rZIadsfntZkcRA0mMOeFXOXtxzQSMJLBMhPUnEzFRnQJeSda0txd1LBE4lGkohTYYKa+95KAfurAE3F4Z5kTgfC1dGBeDLFW4EjN7oRKZSiKeBeKKJ3EHFykwpkQeXC/WSBI3CppFo44mWQMgjh/RQguQBekHFyseLebHkUgKxM2QZx2okZNBQRlli10JmBGXOQJpX2EacRlUmDUeMyJGZgqFJow9dnlRm0O9GRkwcOFXEW5maadjNHBtsmduXvlZ45heWQkRn3EZuuKGZh0jEaN6OYqYmmf1ElFq7pn4F5VdzQIRp+V5utaNXTHpEKmVvRcZqv5cCdoQq5a5+hekXjmEZWda/qWkWQ3tOlqva/2a60LCkkbsVSieRaI8ya62rE7NmqVeRNV6da2O2Xa17UOAnvUtjJiapWhEcGkapbFdWZRulPKwy5W7Z4kapYzHUgQqV6oiCatUo1j0b1T9AjnwVBbh25Wp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S0tMTExNTU1OTk5PT09QUFBRUVFSUlJTU1NUVFRVVVVWVlZXV1dYWFhZWVlaWlpbW1tcXFxdXV1eXl5fX19gYGBhYWFiYmJjY2NkZGRlZWVmZmZnZ2doaGhpaWlqampra2tsbGxtbW1ubm5vb29oenReh3tTlYJIpIk+sI43uJEyvZQwwJUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYwwpYxwpcxwpcywpczw5g0w5g3xJo5xJs9xZ1Bx59EyKBHyKFJyaJMyqRPy6ZSzKdXzapbz6xez61g0K5j0bBn0rJq07Nv1bZz1rh217l417p62Lt72Lx92L1+2b2A2b6E2sCH28KM3cSR3seX4Mqg4s+q5dS06dm669zB7eDG7uLK8OTQ8efW8+rZ9Ozc9e3d9e7f9u/i9/Dk9/Hl9/Hl+PLm+PLn+PPo+PPo+PPp+fTs+fXu+vbw+vfx+vfy+/jy+/j0+/n2/Pr3/Pv6/fz8/v39/v7+/v7+/v7+/v7+/v7+//////////////////////////////////////////////////////////////////////////////////////////////////////////////////8I/gDjCRxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzatzIsaPHjyBDihxJsqTJkyhTqlzJsqXLlzBjypwpERuvYcNOGruJjaZFXpCCQmLFitfNnB6BBiX16lcznxJRCZ06lahRnBdfUQ2KqtdTqA2bgdpKlqpVXsYgsioblFUwsAyBsZ0r9BVEukt59YSL0BZetrweNvsbFNQvvgexSSVMFdjDYYyXOkZM0FjkqV8b9roc9NVeyvE2XwYFMRfnwpMp/1p1mZXa00JtfQarC9KnsYwDP8QNGxKq2TOxreUa+fDD3lNJpaVpjJRZxssbQkYuFBTSmMZ4TzVFGOIv6lNB/kV3mb3sKO1lXT8Ethg8JPEvy7NNhTdXTWC52veGzxIberKszWUcRdj84tx+maGk2F+fcMfWdRYZ2NtvKvlFmINlcQTMcJzZkpJSjAW4FSoeAfPfX73odBp9W3nokTH6QVfSgq0x81+KH2FjYWQkkmTaZS5ONxWEHv1y4ly6hSQkY/YNBGJQwL14JFvjecQhYQMO1B4pJckVmXofLflXlgOJ1ZlJ30WWWkcx0pWkQV6+OdKPhHFZ5JcL+UXkSDuOaSVjoERZkGKCinQlXXZuZBljex5UZUmLYslRn3ThCBedeIGJkX91UsYpYQlGyJilfKX5V5MYUcpWoKDFcyhb/oleNCVVcvIl5lyPSuQlXqy2Gs+BeNUqEaZI+irQk3P1aNGrZeXK12CEFXrcX6QZK1CbZTX6UKR02WVtaISRKpGplX4bD7R4uUgRslSaG8+sQik7EbNbVWsuvVst+5em1qqaYUXwBqXut+yWFepdfwnb6q50adsQYQqDdmu2FUHsbjzczuXwQujSRaaxE5O1sUIhbzWyrYSdjFDJVKkMFstDUpQxWx/7Sq7GFSd8ccFkHTwtXt6ay3O+AO97sb/16svrxcDOxW9EWv3lbsdzBT3R0I2ZezNgFsEcm7lIm2wRNoTZa2zTc0nrUMBBrQka1bCmSpjVoGE9Fd0TMUyX/to+oU3zpozVDNbWbPHtUNS8Gh6T3+lpRHhZqJY66kZsB+XsTIwbvBGxTiMm2l94WwS3gHDNjDNH+NareErYZE5WrBp5PRWFNCGO4kdhk/W0S5//BftGo881sEuP/w0S53RFzlLxZcnrUeuXCW6S3SKPpPdfw5+UO+Ql2U4YKj6HJBxnpKx+0aeRgeK2kq6fXpLsZOli/kTYeM9kSr2nr9dHvVS+Vegk2d5cQLG/jBSofXShnUqwFRlbuAwhzdCF/8gCivDNiIGtuYlDsBEMXWAQUJczCY3cMxSjBAMnOPmFUTxIwveE8CQjbKEMy8KfmMRwhjiEhHJ8Mr4c4pAVhPNDiQB9GBnlQcVIRKSOYVrVjA8msWpBfAn1npicB9KkGfaj4lYKeLFhpC6JrHghyL44QwdeTCHNyMUEOUNAC56RINgAhi3WiBdUSO+NCtkJK+gYr1wAI4p4NIgxVEiUI4GCKEX5YyBHgg2ciHGRkIykJCdJyUpa8pKYzKQmN8nJTrokIAAh+QQJBADwACwAAAAAeAB4AIcAAAABAQECAgIDAwMEBAQFBQUGBgYHBwcICAgJCQkKCgoLCwsMDAwNDQ0ODg4PDw8QEBARERESEhITExMUFBQVFRUWFhYXFxcYGBgZGRkaGhobGxscHBwdHR0eHh4fHx8gICAhISEiIiIjIyMkJCQlJSUmJiYnJycoKCgpKSkqKiorKyssLCwtLS0uLi4vLy8wMDAxMTEyMjIzMzM0NDQ1NTU2NjY3Nzc4ODg5OTk6Ojo7Ozs8PDw9PT0+Pj4/Pz9AQEBBQUFCQkJDQ0NERERFRUVGRkZHR0dISEhJSUlKSkpLS0tMTExNTU1OTk5PT09QUFBRUVFSUlJTU1NUVFRVVVVWVlZXV1dYWFhZWVlaWlpbW1tcXFxdXV1eXl5fX19gYGBhYWFiYmJjY2NkZGRlZWVmZmZnZ2doaGhpaWlqampra2tsbGxtbW1ubm5vb29rdXJlf3dZj39LoIdBrY05tpE0vJMwwJUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYwwpYxwpc0w5g5xJs/xp5FyKFJyaNLyqRPy6ZUzKhZzqtez65g0K9h0K9h0K9i0bBj0bBm0bFp0rNs07Rx1Ld21rl517t92L2B2r+F28GM3cSQ3saT38iY4Mug486k5NGp5dOt59Ww6Ne06dm7693C7eDJ7+TR8ufW8+rZ9Ozd9e7i9/Dl+PLo+PPq+fTt+fXv+vbw+vfx+vfx+/fy+/j0+/n0/Pn2/Pr3/Pv5/fz7/f39/v7+/v7+/v7+/v7+/v7///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8I/gDhCRxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzatx4sRiuZhxDihRZrVKljyNTqqRYy2SlWsdWypyZcJhLk7Fi0tw5s+RNk6eG8RyasuVPoEKJKs1o86hLUr6qLZ06sZnTn1ClUt3aUNXVoyi5ij0I7KtTXMrGqoVXzJbZqznXTlW2i5TJVG+dkgIpl+exWD9d5T3qq+/Ov05NDb5JSutDmIYhNgP8Fe/iSkkfNq3FN7JCX3kFLz4l8dTNXY49EzxmOq/ixcUiNr15Kq3qgWUvv34Lq/TVwqqrGb0s+q1OzWZVpV6rrPXlSpbN4vJttnZfZXafu9x9tbPD2WZJ/h0Xi137zeJOd1EfnHlrefMuox9tLFt7+6Xv4W//vf7y/aHVOKcfLsfs4hRpEeWmHSm2EVWNV/pBxZcyTv23UDXZmbeXUrjoF5RBAlaiykTFZKjdiEMVox9qB3V403gQVeOiedPt1IyJi5ESG0IqutSbRazBt+NMlD1Xy3IGZehdRaAtiKRICvrXkIs1MkWjTBja55CK9G0E3mIwhtSklA9BJVKJzyE4klXPARMRiyLlx15KMw5W5VjHpLmmkYYZSCZHdb7VZV8QDqamRj4tZuFYFF42ZEZj5vWjZ5G+dSdGOJq1pGGwXPYkiZcBd1ueimoU6Fek3EZQiF+hiOli/uqpKtCXZn0KUY+DbepZlnNe5KekshL0q6UYFfrWop6xmVeqFiUqaLAFdTpYgxORmles0ApU6VfINrTtVY9Ci+tbok403Fu6quqsWZNOJG1e2RbEql4WZepUu/GeepVFsMZLEK1XUQtRo3m56a9A1hpHUcJmhQktw1853BDEV0ksK8UVLgzmwQgvVi5EGB9lsaoh/9StQiXf9HG2Kbs0skLKkssxPOtGXJHHM7ds0ssK9cvxt07xnNC8R7nqr75OWfTuWzMba5ZFQIvM8WL41jfYyrIS/FYtFmnN7sEAO2XwzYMxG6/TXwks0blmhauq109fFKVZseRLNUZwf5Wu/mG8FpwR0WBBG7ZTe0OEtFO2yoX2VUZ3Haqsgx81NkaAY5W4WH3ndTlEc5vFtWqHB75RzWadTJXOLql9UehYFT5VgJdVjVHMedXdV9RBj8T6T5OLFXnRKdGel+k7/S41ndr1vpTxP32eUuZ2bsX8T65j1Hlel/KE5nNYi7T0YkcCuDvwNMmZo/IpBWme6ilNf1Qs7G80LPdEjf/b5hMNU/nXDi4+WlQaaYYv7LUY5SwFejSKX0OUYT9UKVAm5oPPKXbhtoQ0Yxi42N9iHjiTCOpHRLjwxTFGSMJi+MIXGfxgxshDQBW6sGxCs5H/XkhDvXDQQd+roQ4Zc8OlNHCHhNqBBf6osj0g1rB7fRGOEV94ihiqZRgtXCK5hiiXauBOikeBRQ+T9cMdwsKJqmoGLqIIxC/OTCFW1KAKSbGL6p1RIB4h44JqUcE3MqRAarwKKWoBjC3aMSHVOIYvajFDk8ACFnz04x8lEkgSgnGRkIykJCdJyUpa8pKYzKQmN8nJTiolIAAh+QQJBADwACwAAAAAeAB4AIcAAAABAQECAgIDAwMEBAQFBQUGBgYHBwcICAgJCQkKCgoLCwsMDAwNDQ0ODg4PDw8QEBARERESEhITExMUFBQVFRUWFhYXFxcYGBgZGRkaGhobGxscHBwdHR0eHh4fHx8gICAhISEiIiIjIyMkJCQlJSUmJiYnJycoKCgpKSkqKiorKyssLCwtLS0uLi4vLy8wMDAxMTEyMjIzMzM0NDQ1NTU2NjY3Nzc4ODg5OTk6Ojo7Ozs8PDw9PT0+Pj4/Pz9AQEBBQUFCQkJDQ0NERERFRUVGRkZHR0dISEhJSUlKSkpLS0tMTExNTU1OTk5PT09QUFBRUVFSUlJTU1NUVFRVVVVWVlZXV1dYWFhZWVlaWlpbW1tcXFxdXV1eXl5fX19gYGBhYWFiYmJjY2NkZGRlZWVmZmZnZ2doaGhpaWlqampra2tsbGxtbW1ubm5vb29oenRbi31Pm4REqIo8so82uZIyvpQvwJUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZUvwZYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYvwpYwwpYxwpcywpczw5c1w5k5xJs8xZw9xZxDx6BJyaNOyqVTzKdWzalZzqtcz6xez61g0K5j0bBm0bFs07Ry1bd21rl517t92L2B2b+I28KM3MSQ3caV38ma4Muf4s6i48+o5NOx59e66tzC7ODI7uPN8OXT8unX8+vb9Oze9e7i9vDo+PPt+fXw+vfy+/jy+/jz+/j0+/n1/Pn2/Pr3/Pr3/Pv4/fv5/fz6/fz7/v38/v39/v7+/v7+/v7+/v7+/v7+/v7+//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8I/gDhCRxIsKDBgwgTKiyIa6HDhxAjSpxIcWClhhUzatzI0WGlix1DihwZ8eNFaCRTqiRpslIrlCtjypzY0iXMmThzGqxpU6dPnTxd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AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA"></span>
                <span id="login-section">
                    <input type="text" placeholder="{{trans('admin.email_field_placeholder')}}" id="email" name = "username"/>
                    <input type="password" placeholder="{{trans('admin.password_field_placeholder')}}" id="password" name = "password"/>
                </span>
                @if($two_factor_auth)
                <span id="otp-section" style="display: none">
                    <input type="password" id="otp_token" name="otp_token" placeholder="{{trans('admin.enter_otp')}}">
                    <a  style="float: right;position: relative;top:-16px;" id="resend_otp" href="#/">{{trans('admin.resend_otp')}}</a>
                </span>
                @endif
                <button type="button" id="login-btn">{{trans('admin.login_btn')}}</button>
                <button type="button" id="otp-btn" style="display: none">{{trans('admin.submit_otp_btn')}}</button>
            </form>

        </div>
        </div>
        {{--<script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>--}}
        <script src='{{ asset('js/jQuery-2.2.0.min.js') }}'></script>
        <script>

            var csrf_token = "{{csrf_token()}}";

            /*function submit()
            {
                
            }*/

            $("input[name=username]").on("keydown", function(e){
                if(e.keyCode == 13){
                    $("#login-btn").trigger("click");
                }
            });

            $("input[name=password]").on("keydown", function(e){
                if(e.keyCode == 13){
                    $("#login-btn").trigger("click");
                }
            });

            $("input[name=otp_token]").on("keydown", function(e){
                if(e.keyCode == 13){
                    $("#otp-btn").trigger("click");
                }
            });






            $("#otp-btn").on("click", function(){
                hideErrorDiv();
                showLoader();
                var URL = "{{url('admin/login/otp/verify')}}";

                var data = {
                    otp_token : $("#otp_token").val(),
                    _token : csrf_token
                };

                $.post(URL, data, function(response){
                    
                    if(response.otp_ok) {
                        window.location.reload();
                    } else {
                        showErrorDiv(false, "{{trans('admin.enter_valid_otp')}}");
                    }

                    showLoader(false);
               });

            });



            $("#resend_otp").on("click", function(){
                hideErrorDiv();
                showLoader();
               $.post("{{url('admin/login/otp/resend')}}", {_token:csrf_token}, function(response){
                    if(response.status === "success" && response.success_type === "OTP_RESEND_SUCCESS") {
                        showErrorDiv(true, response.success_text);
                    } else if(response.status === "success" && response.success_type === "OTP_RESEND_FAILED") {
                        showErrorDiv(false, response.success_text);
                    }
                    showLoader(false);
               });

            });




            $(document).ready(function(){

                $("#login-btn").on("click", function(){
                    hideErrorDiv();
                    showLoader();
                    var URL = "{{url('admin/login')}}";

                    var data = {
                        username : $("#email").val(),
                        password : $("#password").val(),
                        _token : csrf_token
                    };

                    $.post(URL, data, function(response){

                        if(response.status === "success") {


                            if(response.otp_required) {

                                showOtpSection();
                                showOtpBtn();

                                if(response.success_type === "ADMIN_LOGIN_SUCCESS_WITH_OTP") {

                                    showErrorDiv(true, response.success_text);

                                } else if(response.success_type === "ADMIN_LOGIN_SUCCESS_OTP_FAILED") {
                                    showErrorDiv(false, response.success_text);
                                }


                                

                            } else {

                                window.location.reload();
                            }



                        } else if(response.status === "error") {
                            showErrorDiv(false, response.error_text);
                        }
                        showLoader(false);

                    });

                });



            });


            function showLoader(show = true)
            {
                if(show)
                    $(".loader").show();
                else 
                    $(".loader").hide();
            }


            function showOtpBtn()
            {
                $("#login-btn").hide();
                $("#otp-btn").show();
            }


            function hideOtpBtn()
            {
                $("#login-btn").show();
                $("#otp-btn").hide();
            }


            function showOtpSection()
            {
                $("#otp-section").show();
                $("#login-section").hide();
            }


            function hideOtpSection()
            {
                $("#otp-section").hide();
                $("#login-section").show();
            }


            function showErrorDiv(success, text)
            {
                $("#error_div").show();
                
                if(success) {
                    $("#error_text_div").removeClass("alert-danger").addClass('alert-success');
                } else {
                    $("#error_text_div").addClass("alert-danger").removeClass('alert-success');
                }

                $("#error_text_div").text(text);
            }

            function hideErrorDiv()
            {
                $("#error_div").hide();
            }


            $('.message a').click(function(){
                $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
            });
        </script>
    </body>
</html>