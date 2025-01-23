<x-layouts.app>
  <header class="py-5" style="background-image: url('wallpaper.webp'); background-position: center;">
    <div class="container d-flex align-items-center justify-content-center" style="height: 40vh">
        <div class="row vh-50">
            <div class="col-12 d-flex flex-column p-5" style="background-color: rgba(20, 19, 19, 0.365)">
              
                <h1 class="display-1" style="color: white; ">Cyber Security News</h1>
                <a class="btn btn-outline-info text-white" href="{{route('articles.create')}}"><b class="h2">Start Writing</b></a>
            </div>
        </div>
    </div>
</header>
    <div class="container">
        <div class="row">
          <div class="col-12">
            @if (isset($searchTerm))
            <h2>Results for: {!!$searchTerm!!}</h2>
            @else
            <h2 class="mt-5">Recent Articles</h2>
            @endif
          </div>
            @forelse ($articles as $article)
            <div class="card col-md-4 my-5 p-0 mx-2" style="max-width: 25rem">
                <img src="https://picsum.photos/300?a={{$loop->index}}" class="img-fluid" alt="...">
                <div class="card-body">
                  <h5 class="card-title">{{$article->title}}</h5>
                  <h6 class="card-title">Author: {{$article->user->name}}</h6>
                  <a href="{{route('articles.show',$article)}}" class="btn btn-primary">Read</a>
                </div>
              </div>
            @empty
                
            @endforelse
        </div>
    </div>
    
    </x-layouts.app>