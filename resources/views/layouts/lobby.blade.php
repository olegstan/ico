<!DOCTYPE html>
<head>
    @include('lobby.parts.head')
</head>
<body class="home-page bp-legacy home page-template-default page page-id-202 language-en group-blog wpb-js-composer js-comp-ver-5.4.5 vc_responsive js"
      data-start="background-position: 50% 500px;" data-end="background-position: 50% 0px;">

<!-- Preloader -->
@include('lobby.parts.preloader')
<!-- /Preloader -->

<!-- Navbar -->
@include('lobby.parts.navbar')
<!-- /Navbar -->


@yield('content')

@include('lobby.parts.search')

@include('lobby.parts.javascript')


@include('lobby.parts.modal')


</body>
</html>