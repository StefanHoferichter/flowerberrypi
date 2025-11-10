<!DOCTYPE html>
<html lang="de">
  <head>
    <title>FlowerBerryPi - @yield('title')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <META NAME="Content-Language" CONTENT="de">
    <meta name="referrer" content=“no-referrer-when-downgrade“>
    <META NAME="ROBOTS" CONTENT="index, follow">
    <meta name="Description" content="@yield('description')">
    <meta name="KeyWords" content="Watering, Flowers, Plants, Vacation, Balcony, Home, Garden">
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
              <div class="nav_logo"><a href="/"><img class="logo" src="/images/logo/flowerberrypi_logo_with_text.jpg" alt="logo"></a></div>
              <div class="nav_job"><a class="nav_item" href="/jobs">Jobs</a></div>
              <div class="nav_zones"><a class="nav_item" href="/zones">Zones</a></div>
              <div class="nav_sensors"><a class="nav_item"  href="/sensors">Sensors</a></div>
              <div class="nav_forecast"><a class="nav_item" href="/forecast">Forecast</a></div>
              <div class="nav_manual"><a class="nav_item" href="/manual_watering">Watering</a></div>
              <div class="nav_setup"><a class="nav_item" href="/setup">Setup</a></div>
              <div class="nav_diagnosis"><a class="nav_item" href="/diagnosis">Diagnosis</a></div>
                            @yield('submenu')
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
              <div class="footer_threads"><a class="nav_item" target="_blank" href="https://www.threads.net/@stefanhoferichter"><img src="/images/icon/threads-logo.png" width="20" alt="Threads"></a></div>
              <div class="footer_hostname"><div class="menu_item">{{ $hostname }}</div></div>
 			</div>
  		</footer>
  </body>
</html>