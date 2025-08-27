<!DOCTYPE html>
<html lang="de">
  <head>
    <title>FlowerBerryPi - @yield('title')</title>
    <link rel="canonical" href="@yield('url')" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <META NAME="Content-Language" CONTENT="de">
    <meta name="referrer" content=“no-referrer-when-downgrade“>
    <META NAME="ROBOTS" CONTENT="index, follow">
    <meta name="Description" content="@yield('description')">
    <meta name="KeyWords" content="RezeptExperte, Triglyzerid, Hypertriglyceridämie, Cholesterin, Hypercholesterinämie, Diät, gesunde Ernährung, Rezepte">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">	
	<meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="@yield('description')">
    <meta property="og:url" content="@yield('url')">
    <meta property="og:image" content="@yield('image')">
    <meta property="og:type" content="@yield('og_type', 'article')">
    <meta name="thumbnail" content="@yield('image')" />
    <link rel="stylesheet" href="/flowerberrypi.css">
	<link rel="apple-touch-icon" sizes="180x180" href="/images/logo/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/logo/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/logo/favicon-16x16.png">
    <link rel="manifest" href="/images/logo/site.webmanifest">   
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <body>
      <header> 
        <nav>
              <div class="nav_logo"><a href="/"><img class="logo" src="/images/logo/flowerberrypi.jpg" alt="logo"></a></div>
              <div class="nav_zones"><a class="nav_item" href="/zones">Zones</a></div>
              <div class="nav_sensors"><a class="nav_item"  href="/sensors">Sensors</a></div>
              <div class="nav_rs"><a class="nav_item"  href="/remote_sockets">Remote Sockets</a></div>
              <div class="nav_job"><a class="nav_item" href="/trigger_job">Job</a></div>
              <div class="nav_forecast"><a class="nav_item" href="/forecast">Forecast</a></div>
              <div class="nav_manual"><a class="nav_item" href="/manual_watering">Manual Watering</a></div>
        </nav>
	 </header>
	 <article>
	    <div class="home">
            @yield('content')
            
	    </div>
      </article>
      
      
     <footer>
	        <div class="footer_menu">
              <div class="footer_impressum"><a class="nav_item" href="/impressum">Impressum</a></div>
              <div class="footer_datenschutz"><a class="nav_item" href="/datenschutz">Datenschutz</a></div>
 			</div>
 			
  </footer>
  </body>
</html>