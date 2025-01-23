<x-layouts.app>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>Update Article</h2>
            <form action="{{route('articles.update',$article)}}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Title</label>
                    <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="An interesting title here" name="title" value="{{old('title') ?? $article->title}}">
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Content</label>
                    <textarea id="editor" class="form-control" id="exampleFormControlTextarea1" rows="6" name="content" placeholder="Tell something amazing">{!!old('title') ?? $article->title!!}</textarea>
                </div>
                <button type="submit" class="btn btn-success">Save</button>
            </form>
            <h2>Export XML</h2>
            <a href="{{route('articles.exportXml',$article)}}" class="btn btn-primary">Export</a>
            </div>
        </div>
    </div>
    <x-slot:scripts>
        <script>
            tinymce.init({
              selector: '#editor',
              xss_sanitization: false
            });
          </script>
    </x-slot>
</x-layouts.app>