<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
</head>
<body>
    <header>
        <h1>My Website</h1>
    </header>
 <nav>
        <a href="/">Home</a>
            <a href="/about">About</a>
          
        
 </nav>
 <hr>
 <div>
    @yield('content')
 </div>

 <footer>
    <p>2026 My Website</p>
 </footer>


    
</body>
</html>