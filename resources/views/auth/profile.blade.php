<x-layouts.app>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h1 class="mb-5">{{$user->name}}'s Profile</h1>
                <div class="my-3">

                    <img src="{{$user->avatar ? Storage::url("public/images/users/$user->id/" . $user->avatar) : Storage::url('user_default.png')}}" class="img-fluid me-5" alt="" width="200">
                    <form method="POST" action="{{route('change.img')}}" class="my-3 py-3" enctype="multipart/form-data">
                        @csrf
                        <label for="">Change profile picture</label>
                        <div class="mb-3">
                            <input type="file" class="form-control" aria-label="file example" name="avatar" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
                <h2>Informations</h2>
                <form method="POST" action="{{route('users.update',$user->id)}}" class="mb-5">
                    @csrf
                    @method('PATCH')

                    <label for="">Name</label>
                    <input class="form-control" type="text" name="name" value="{{old('name') ?? $user->name}}" placeholder="You really change your name?!">

                    <label for="">Email</label>
                    <input class="form-control" type="text" name="email" placeholder="Your brand new email" value="{{old('email') ?? $user->email}}">

                    <button type="submit" class="btn btn-warning my-3">Change</button>
                </form>
                <hr>
                <h2>Articles</h2>
                <table class="table">
                <thead>
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">Title</th>
                      <th scope="col">Published</th>
                      <th scope="col">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($user->articles as $article)

                    <tr>
                      <th scope="row">{{$article->id}}</th>
                      <td><a href="{{route('articles.show',$article->id)}}">{{$article->title}}</a></td>
                      <td>
                          @if($article->published)
                          yes
                          @else
                          no
                          @endif
                      </td>
                      <td>
                        <a href="{{route('articles.edit',$article->id)}}" class="btn btn-warning">Edit</a>
                        <a href="{{route('articles.destroy',$article->id)}}" class="btn btn-danger">Delete</a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>

                <h2>Company Documents</h2>
                <ul>
                    <li>
                        <a href="{{route('privacyPolicy')}}">Privacy policy</a>
                    </li>
                    <li>
                        <a href="{{route('cookiePolicy')}}">Cookie policy</a>
                    </li>
                    <li>
                        <a href="{{route('serviceAgreements')}}">Service Agreements</a>
                    </li>
                </ul>

                <h2>User Documents</h2>
                <ul>
                    @forelse ($user->files as $file)
                        <li>
                            <a href="{{Storage::url("public/docs/users/$user->id/" . $file->name)}}">{{$file->name}}</a>
                        </li>
                    @empty
                        no files
                    @endforelse
                </ul>
                <form action="{{route('upload')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex">
                        <input type="file" name="file" class="form-control">
                        <button type="submit" class="btn btn-primary mx-3">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>