<x-layouts.app>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>Create new account</h2>
                <form action="{{route('register')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Name</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Your name" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Email</label>
                        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="Your email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Password</label>
                        <input type="password" class="form-control" id="exampleFormControlInput1" placeholder="Strong password here" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="exampleFormControlInput1" placeholder="Repeat the password again" name="password_confirmation">
                    </div>
                    <button type="submit" class="btn btn-success">Register</button> or <a href="{{route('login')}}">Login</a>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>