<html>
    <head>
        <title>@yield('title')</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <!--<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' media="all">-->
        <!--<link href="https://fonts.googleapis.com/css?family=Raleway+Dots" rel="stylesheet">-->
        <link href="{{asset('print/style.css')}}" rel="stylesheet" type='text/css' media="all">
    </head>
    <body>
        <div>
            <div style="text-align: center;">
                <h2 class="name"><b>PT. LAUTAN TIRTA TRANSPORTAMA</b></h2>
                <div style="font-size: 12px;"><b>WAREHOUSING, CONTAINER YARD, CUSTOM CLEARANCE & TRANSPORTATION</b></div>
                <div style="font-size: 12px;">Jl. Ende No. 58 B Tanjung Priok - Jakarta Utara</div>
                <div style="font-size: 12px;">Phone: (021) 4371929, (Hunting) 4371902 &nbsp;Fax: 4371879, 4371917</div>
                <div style="font-size: 12px;">Email : <a href="mailto:primanatajp@yahoo.co.id">lttjkt@cbn.co.id</a></div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div id="main">
            <br /><br />
            @yield('content')

        </div>
    </body>
</html>