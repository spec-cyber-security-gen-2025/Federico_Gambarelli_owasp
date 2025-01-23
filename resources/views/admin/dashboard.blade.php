<x-layouts.app>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-4 offset-md-1 m-5 d-flex align-items-center justify-content-center" style="height: 200px; background-color:lightgray">
                <div class="display-5"><a href="{{route('admin.users')}}" class="text-white">Users</a></div>
            </div>
            <div class="col-12 col-md-4 offset-md-1 m-5 d-flex align-items-center justify-content-center" style="height: 200px; background-color:lightgray">
                <div class="display-5"><a href="{{route('admin.articles')}}" class="text-white">Articles</a></div>
            </div>
            <div class="col-12 col-md-4 offset-md-1 m-5 d-flex align-items-center justify-content-center" style="height: 200px; background-color:lightgray">
                <div class="display-5"><a href="{{route('admin.financialData')}}" class="text-white">Financial Data</a></div>
            </div>
            <div class="col-12 col-md-4 offset-md-1 m-5 d-flex align-items-center justify-content-center" style="height: 200px; background-color:lightgray">
                <div class="display-5"><a href="{{route('admin.articles')}}" class="text-white">Settings</a></div>
            </div>
        </div>
    </div>
</x-layouts.app>