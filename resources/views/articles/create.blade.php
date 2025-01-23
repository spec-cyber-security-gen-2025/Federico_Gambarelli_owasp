<x-layouts.app>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>New Article</h2>
                <form action="{{route('articles.store')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Title</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="An interesting title here" name="title">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Content</label>
                        <textarea id="editor" class="form-control" id="exampleFormControlTextarea1" rows="6" name="content" placeholder="Tell something amazing"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Save</button>
                </form>
                or
                <h2>Import XML Article</h2>
                <form action="{{route('articles.importXml')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="xml_file" required class="form-control">
                    <button type="submit" class="btn btn-primary my-3">Import</button>
                </form>
            </div>
        </div>
    </div>
    <x-slot:scripts>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    
    <script>
        const quill = new Quill('#editor', {
            theme: 'snow'
        });
    </script>
</x-slot>
</x-layouts.app>
