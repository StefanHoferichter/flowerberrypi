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
    <link rel="stylesheet" href="/rezeptexperte.css">
	<link rel="apple-touch-icon" sizes="180x180" href="/images/logo/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/logo/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/logo/favicon-16x16.png">
    <link rel="manifest" href="/images/logo/site.webmanifest">   
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <body>
      <header> 
        <nav>
              <div class="nav_blog"><a class="nav_item" href="/zones">Zones</a></div>
              <div class="nav_logo"><a href="/">Sensors</a></div>
              <div class="nav_logo"><a href="/remote_sockets">Remote Sockets</a></div>
              <div class="nav_ingredients"><a class="nav_item" href="/trigger_job">Job</a></div>
              <div class="nav_ernaehrung"><a class="nav_item" href="/forecast">Forecast</a></div>
              <div class="nav_about"><a class="nav_item" href="/manual_watering">Manual Watering</a></div>
              <div class="nav_search"><form style="display:inline-block" action="/search_recipe" target="_top" method="post" novalidate="">
              @csrf
              <table><tr><td><input style="display:inline-block" type="text" name="search_term" value=""></td><td><button class="button_search" style="display:inline-block" type="submit"><img src="/images/icon/search.png" width="15" alt="Suche"></button></td></tr></table>
	          </form>
              </div>
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