<!doctype html>
<html lang="en">
<head>
    @include('elements.head')
</head>
<body>
@include('elements.header')

<div class="container">

    @yield('content')

</div>

@include('elements.scripts')

</body>
</html>