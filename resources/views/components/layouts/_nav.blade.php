<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{route('home')}}">CSN</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{route('home')}}">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Menu
            </a>
            <ul class="dropdown-menu">
              @guest
              <li><a class="dropdown-item" href="/login">Login</a></li>
              <li><a class="dropdown-item" href="/register">Register</a></li>
              @endguest
              @auth

              <li><a class="dropdown-item" href="{{route('profile')}}">Hi, {{auth()->user()->name}}</a></li>


              @if(auth()->user()->isAdmin())
              <li><a class="dropdown-item" href="{{route('dashboard')}}">Dashboard</a></li>
              @endif
              <li><hr class="dropdown-divider"></li>
              <form action="/logout" method="POST">
              @csrf
              <button type="submit" class="dropdown-item">Logout</button>
              </form>
              @endauth
            </ul>
          </li>
          <li class="nav-item">
            <a class="btn btn-outline-primary" href="{{route('articles.create')}}">Start Writing</a>
          </li>
        </ul>
        <form action="{{route('articles.search')}}" class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>