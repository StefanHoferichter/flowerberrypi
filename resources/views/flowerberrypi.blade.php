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
	
  </head>
  <body>
      <header> 
        <nav>
              <div class="nav_logo"><a href="/">Home</a></div>
              <div class="nav_logo"><a href="/remote_sockets">Remote Sockets</a></div>
              <div class="nav_recipes"><a  class="nav_item" href="/relays">Relays</a></div>
              <div class="nav_ingredients"><a class="nav_item" href="/trigger_job">Job</a></div>
              <div class="nav_ernaehrung"><a class="nav_item" href="/ernaehrung">Ernährung</a></div>
              <div class="nav_blog"><a class="nav_item" href="/blog">Blog</a></div>
              <div class="nav_about"><a class="nav_item" href="/about_me">Über mich</a></div>
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
              <div class="footer_pinterest"><a class="nav_item" target="_blank" href="https://pin.it/7Dg12ajk3"><img src="/images/icon/pinterest-131.png" width="30" alt="Pinterest"></a></div>
              <div class="footer_instagram"><a class="nav_item" target="_blank" href="https://www.instagram.com/rezeptexperte?igsh=eDMzdjF6ZzA2bGF6"><img src="/images/icon/instagram.png" width="30" alt="Instagram"></a></div>
              <div class="footer_facebook"><a class="nav_item" target="_blank" href="https://www.facebook.com/profile.php?id=61566584611271"><img src="/images/icon/Facebook_Logo_Primary.png" width="30" alt="Facebook"></a></div>
              <div class="footer_threads"><a class="nav_item" target="_blank" href="https://www.threads.net/@rezeptexperte"><img src="/images/icon/threads-logo.png" width="30" alt="Threads"></a></div>
              <div class="footer_youtube"><a class="nav_item" target="_blank" href="https://www.youtube.com/@rezeptexperte"><img src="/images/icon/yt.png" width="30" alt="YouTube"></a></div>
 			</div>
 			
  </footer>
  </body>
</html>