<!DOCTYPE html>
<html lang="en">

@include('layouts.header')

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
    @include('sweetalert::alert')

    <div class="wrapper">
        <div id="loader"></div>
        @include('layouts.navbar')
        @include('layouts.sidebar')
        <div class="content-wrapper">
            <div class="container-full">
                @yield('content-header')
                <section class="content">
                    @yield('content')
                </section>
            </div>
        </div>
        @include('layouts.footer')
        @include('layouts.control-sidebar')
    </div>
    @include('layouts.component')
    @include('layouts.scripts')
</body>

</html>
