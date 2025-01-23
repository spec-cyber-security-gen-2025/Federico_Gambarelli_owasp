<x-layouts.app>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2">
                <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Author</th>
                        <th scope="col">Published</th>
                        <th scope="col">Actions</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($articles as $article)
                            
                      <tr>
                        <th scope="row">{{$article->id}}</th>
                        <td>{{$article->title}}</td>
                        <td>{{$article->user->name}}</td>
                        <td>
                            @if($article->published)
                            yes
                            @else
                            no
                            @endif
                        </td>
                        <td>
                            {{-- UNSECURE --}}
                            <a href="{{route('admin.articles.toggle',$article->id)}}">
                                @if($article->published)
                                Unpublish
                                @else
                                Publish
                                @endif
                            </a>
                            {{-- SECURE --}}
                            {{-- <form action="{{route('articles.toggle',$article->id)}}" method="post">
                                @csrf
                                @if($article->published)
                                <button type="submit">Unpublish</button>
                                @else
                                <button type="submit">Publish</button>
                                @endif
                            </form> --}}
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
</x-layouts.app>