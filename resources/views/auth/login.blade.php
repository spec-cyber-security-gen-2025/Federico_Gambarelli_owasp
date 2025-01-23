<x-layouts.app>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>Welcome Back</h2>
                <form action="{{route('login')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Email</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="An interesting title here" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Password</label>
                        <input type="password" class="form-control" id="exampleFormControlInput1" placeholder="An interesting title here" name="password">
                        
                    </div>
                    <button type="submit" class="btn btn-success">Login</button> or <a href="{{route('register')}}">Register</a>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>