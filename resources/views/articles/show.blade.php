<x-layouts.app>
<div class="container">
    <div class="row my-3">
        <div class="col-12">
            <img src="https://picsum.photos/2000/300" class="img-fluid" alt="...">
        </div>
        <div class="col-md-8 offset-md-2">
            <h6 class="my-4">Written by: {{$article->user->name}}</h6>
            @auth

            @if (Auth::user()->id == $article->user_id)
            <a href="{{route('articles.edit',$article->id)}}" class="btn btn-warning">Edit</a>
            <a href="{{route('articles.destroy',$article->id)}}" class="btn btn-danger">Delete</a>
            @endif

            @endauth


            <h1 class="display-1">{{ $article->title }}</h1>

            <div class="text-justify mb-5">
                {!!$article->content !!}
            </div>
            <h3>Comments</h3>
            <form action="{{route('comments.store',$article->id)}}" method="post">
                @csrf
                <div class="d-flex">
                    <input type="text" placeholder="insert comment" class="form-control" name="content">
                    <button type="submit" class="btn btn-success">Send</button>
                </div>
            </form>
            <div class="my-3">
                @forelse ($article->comments as $comment)
                <div class="my-1 px-2" style="background-color: lightgray">
                    <b>{{$comment->user->name}}:</b>
                    <p>{{$comment->content}}</p>
                </div>
                @empty
                    <b>No comments.. write the first one!</b>
                @endforelse
            </div>
        </div>
    </div>
</div>
</x-layouts.app>