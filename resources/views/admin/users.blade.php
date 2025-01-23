<x-layouts.app>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2">
                <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Is Admin</th>
                        <th scope="col">Actions</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            
                      <tr>
                        <th scope="row">{{$user->id}}</th>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            @if($user->isAdmin())
                            yes
                            @else
                            no
                            @endif
                        </td>
                        <td>
                          {{-- UNSECURE --}}
                            <a href="{{route('admin.users.toggle',$user->id)}}">
                                @if($user->isAdmin())
                                Revoke admin
                                @else
                                Set admin
                                @endif
                            </a>

                            {{-- SECURE --}}
                            {{-- <form action="{{route('admin.users.toggle',$user->id)}}" method="post">
                                @csrf
                                @if($user->isAdmin())
                                <button type="submit" class="btn btn-link">Revoke admin</button>
                                @else
                                <button type="submit" class="btn btn-link">Set admin</button>
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