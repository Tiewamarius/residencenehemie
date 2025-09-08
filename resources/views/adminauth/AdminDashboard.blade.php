<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('../Admin-Dashboard/assets/img/apple-icon.png') }}" />
  <link rel="icon" type="image/png" href="{{ asset('../Admin-Dashboard/assets/img/favicon.png') }}" />

  <title>DASHBOARD</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />


  <!-- bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Font Awesome (CDN) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer" />

  <!-- Nucleo Icons -->
  <!-- <link href="{{ asset('../Admin-Dashboard/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('../Admin-Dashboard/assets/css/nucleo-svg.css') }}" rel="stylesheet" /> -->

  <!-- Main Styling -->
  <link href="{{ asset('../Admin-Dashboard/assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5') }}" rel="stylesheet" />
</head>


<body class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500">
  <!-- sidenav  -->
  @include('adminauth.layouts.sidebar')

  <!-- end sidenav -->

  <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
    @include('adminauth.layouts.navbar')
    <!-- end Navbar -->

    @yield('content')
    <footer class="bg-gray-900 text-white text-center py-4">
      @include('adminauth.layouts.footer')
    </footer>
  </main>

</body>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('Admin-Dashboard/assets/js/dashboard.js?v=1.0.5') }}" async></script>
<script>

</script>

</html>