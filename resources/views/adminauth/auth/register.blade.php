
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
        rel="apple-touch-icon"
        sizes="76x76"
        href="./assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="./assets/img/favicon.png" />
    <title>AdminDashboar</title>
    <!--     Fonts and icons     -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"
        rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script
        src="https://kit.fontawesome.com/42d5adcbca.js"
        crossorigin="anonymous"></script>
    <!-- Nucleo Icons -->
    <link href="{{asset('../Admin-Dashboard/assets/css/nucleo-icons.css')}}" rel="stylesheet" />
    <link href="{{asset('.Admin-Dashboard/assets/css/nucleo-svg.css')}}" rel="stylesheet" />
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <!-- Main Styling -->
    <link
        href="{{asset('../Admin-Dashboard/assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5')}}" rel="stylesheet" />
    <!-- Nepcha Analytics (nepcha.com) -->
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    <script
        defer
        data-site="YOUR_DOMAIN_HERE"
        src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body
    class="m-0 font-sans antialiased font-normal bg-white text-start text-base leading-default text-slate-500">
    <div class="container sticky top-0 z-sticky">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 flex-0">
                <!-- Navbar -->
                <nav
                    class="absolute top-0 left-0 right-0 z-30 flex flex-wrap items-center px-4 py-2 mx-6 my-4 shadow-soft-2xl rounded-blur bg-white/80 backdrop-blur-2xl backdrop-saturate-200 lg:flex-nowrap lg:justify-start">
                    <div
                        class="flex items-center justify-between w-full p-0 pl-6 mx-auto flex-wrap-inherit">
                        <a
                            class="py-2.375 text-sm mr-4 ml-4 whitespace-nowrap font-bold text-slate-700 lg:ml-0"
                            href="../pages/dashboard.html">
                            Admin Login
                        </a>
                        <button
                            navbar-trigger
                            class="px-3 py-1 ml-2 leading-none transition-all bg-transparent border border-transparent border-solid rounded-lg shadow-none cursor-pointer text-lg ease-soft-in-out lg:hidden"
                            type="button"
                            aria-controls="navigation"
                            aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span
                                class="inline-block mt-2 align-middle bg-center bg-no-repeat bg-cover w-6 h-6 bg-none">
                                <span
                                    bar1
                                    class="w-5.5 rounded-xs relative my-0 mx-auto block h-px bg-gray-600 transition-all duration-300"></span>
                                <span
                                    bar2
                                    class="w-5.5 rounded-xs mt-1.75 relative my-0 mx-auto block h-px bg-gray-600 transition-all duration-300"></span>
                                <span
                                    bar3
                                    class="w-5.5 rounded-xs mt-1.75 relative my-0 mx-auto block h-px bg-gray-600 transition-all duration-300"></span>
                            </span>
                        </button>
                        <div
                            navbar-menu
                            class="items-center flex-grow overflow-hidden transition-all duration-500 ease-soft lg-max:max-h-0 basis-full lg:flex lg:basis-auto">
                            <ul
                                class="flex flex-col pl-0 mx-auto mb-0 list-none lg:flex-row xl:ml-auto">
                                <li>
                                    <a
                                        class="flex items-center px-4 py-2 mr-2 font-normal transition-all lg-max:opacity-0 duration-250 ease-soft-in-out text-sm text-slate-700 lg:px-2"
                                        aria-current="page"
                                        href="../pages/dashboard.html">
                                        <i class="mr-1 fa fa-chart-pie opacity-60"></i>
                                        Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a
                                        class="block px-4 py-2 mr-2 font-normal transition-all lg-max:opacity-0 duration-250 ease-soft-in-out text-sm text-slate-700 lg:px-2"
                                        href="../pages/profile.html">
                                        <i class="mr-1 fa fa-user opacity-60"></i>
                                        Profile
                                    </a>
                                </li>
                                <li>
                                    <a
                                        class="block px-4 py-2 mr-2 font-normal transition-all lg-max:opacity-0 duration-250 ease-soft-in-out text-sm text-slate-700 lg:px-2"
                                        href="{{ route('admin.login') }}">
                                        <i class="mr-1 fas fa-user-circle opacity-60"></i>
                                        Sign Up
                                    </a>
                                </li>
                                <li>
                                    <a
                                        class="block px-4 py-2 mr-2 font-normal transition-all lg-max:opacity-0 duration-250 ease-soft-in-out text-sm text-slate-700 lg:px-2"
                                        href="../pages/sign-in.html">
                                        <i class="mr-1 fas fa-key opacity-60"></i>
                                        Sign In
                                    </a>
                                </li>
                            </ul>
                            <!-- online builder btn  -->
                            <!-- <li class="flex items-center">
                  <a
                    class="leading-pro ease-soft-in text-fuchsia-500 border-fuchsia-500 text-xs tracking-tight-soft bg-150 bg-x-25 rounded-3.5xl hover:border-fuchsia-500 hover:scale-102 hover:text-fuchsia-500 active:hover:border-fuchsia-500 active:hover:scale-102 active:hover:text-fuchsia-500 active:opacity-85 active:shadow-soft-xs active:bg-fuchsia-500 active:border-fuchsia-500 mr-2 mb-0 inline-block cursor-pointer border border-solid bg-transparent py-2 px-8 text-center align-middle font-bold uppercase shadow-none transition-all hover:bg-transparent hover:opacity-75 hover:shadow-none active:scale-100 active:text-white active:hover:bg-transparent active:hover:opacity-75 active:hover:shadow-none"
                    target="_blank"
                    href="https://www.creative-tim.com/builder/soft-ui?ref=navbar-dashboard&amp;_ga=2.76518741.1192788655.1647724933-1242940210.1644448053"
                    >Online Builder</a
                  >
                </li> -->
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <main class="mt-0 transition-all duration-200 ease-soft-in-out">
        <section>
            <div class="relative flex items-center p-0 overflow-hidden bg-center bg-cover min-h-75-screen">
                <div class="container z-10">
                    <div class="flex flex-wrap mt-0 -mx-3">
                        <div class="flex flex-col w-full max-w-full px-3 mx-auto md:flex-0 shrink-0 md:w-6/12 lg:w-5/12 xl:w-4/12">
                            <div class="relative flex flex-col min-w-0 mt-32 break-words bg-transparent border-0 shadow-none rounded-2xl bg-clip-border">
                                <div class="p-6 pb-0 mb-0 bg-transparent border-b-0 rounded-t-2xl">
                                    <h3 class="relative z-10 font-bold text-transparent bg-gradient-to-tl from-blue-600 to-cyan-400 bg-clip-text">
                                        Welcome
                                    </h3>
                                    <p class="mb-0">Créer un nouveau compte</p>
                                </div>
                                <div class="flex-auto p-6">
                                    <form role="form text-left" method="POST" action="{{ route('admin.register') }}">
                                        @csrf

                                        <div class="mb-4">
                                            <input
                                                type="text"
                                                class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:bg-white focus:text-gray-700 focus:outline-none focus:transition-shadow"
                                                placeholder="Nom"
                                                aria-label="Name"
                                                aria-describedby="email-addon"
                                                name="name"
                                                value="{{ old('name') }}"
                                                required autofocus autocomplete="name" />
                                            @error('name')
                                            <div class="text-sm text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <input
                                                type="text"
                                                class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:bg-white focus:text-gray-700 focus:outline-none focus:transition-shadow"
                                                placeholder="N° Telephone"
                                                aria-label="Phone"
                                                name="phone_number"
                                                value="{{ old('phone_number') }}"
                                                autocomplete="tel" />
                                            @error('phone_number')
                                            <div class="text-sm text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <input
                                                type="email"
                                                class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:bg-white focus:text-gray-700 focus:outline-none focus:transition-shadow"
                                                placeholder="Email"
                                                aria-label="Email"
                                                aria-describedby="email-addon"
                                                name="email"
                                                value="{{ old('email') }}"
                                                required autocomplete="email" />
                                            @error('email')
                                            <div class="text-sm text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <input
                                                type="password"
                                                class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:bg-white focus:text-gray-700 focus:outline-none focus:transition-shadow"
                                                placeholder="Mot de passe"
                                                aria-label="Password"
                                                aria-describedby="password-addon"
                                                name="password"
                                                required autocomplete="new-password" />
                                            @error('password')
                                            <div class="text-sm text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <input
                                                type="password"
                                                class="text-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:bg-white focus:text-gray-700 focus:outline-none focus:transition-shadow"
                                                placeholder="Confirmer le mot de passe"
                                                aria-label="Confirm Password"
                                                name="password_confirmation"
                                                required autocomplete="new-password" />
                                        </div>

                                        <div class="min-h-6 pl-6.92 mb-0.5 block">
                                            <input
                                                id="terms"
                                                class="w-4.92 h-4.92 ease-soft -ml-6.92 rounded-1.4 checked:bg-gradient-to-tl checked:from-gray-900 checked:to-slate-800 after:text-xxs after:font-awesome after:duration-250 after:ease-soft-in-out duration-250 relative float-left mt-1 cursor-pointer appearance-none border border-solid border-slate-200 bg-white bg-contain bg-center bg-no-repeat align-top transition-all after:absolute after:flex after:h-full after:w-full after:items-center after:justify-center after:text-white after:opacity-0 after:transition-all after:content-['\f00c'] checked:border-0 checked:border-transparent checked:bg-transparent checked:after:opacity-100"
                                                type="checkbox"
                                                name="terms"
                                                required
                                                value="1" />
                                            <label
                                                class="mb-2 ml-1 font-normal cursor-pointer select-none text-sm text-slate-700"
                                                for="terms">
                                                I agree the
                                                <a href="javascript:;" class="font-bold text-slate-700">Terms and Conditions</a>
                                            </label>
                                            @error('terms')
                                            <div class="text-sm text-red-500">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="text-center">
                                            <button
                                                type="submit"
                                                class="inline-block w-full px-6 py-3 mt-6 mb-2 font-bold text-center text-white uppercase align-middle transition-all bg-transparent border-0 rounded-lg cursor-pointer active:opacity-85 hover:scale-102 hover:shadow-soft-xs leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 bg-gradient-to-tl from-gray-900 to-slate-800 hover:border-slate-700 hover:bg-slate-700 hover:text-white">
                                                Sign up
                                            </button>
                                        </div>
                                        <p class="mt-4 mb-0 leading-normal text-sm">
                                            Already have an account?
                                            <a href="{{ route('admin.login') }}" class="font-bold text-slate-700">Sign in</a>
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="w-full max-w-full px-3 lg:flex-0 shrink-0 md:w-6/12">
                            <div class="absolute top-0 hidden w-3/5 h-full -mr-32 overflow-hidden -skew-x-10 -right-40 rounded-bl-xl md:block">
                                <div class="absolute inset-x-0 top-0 z-0 h-full -ml-16 bg-cover skew-x-10" style="background-image: url('../Admin-Dashboard/assets/img/curved-images/curved6.jpg');"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="py-12">
        <div class="container">
            <div class="flex flex-wrap -mx-3">
                <div
                    class="flex-shrink-0 w-full max-w-full mx-auto mb-6 text-center lg:flex-0 lg:w-8/12">
                    <a
                        href="javascript:;"
                        target="_blank"
                        class="mb-2 mr-4 text-slate-400 sm:mb-0 xl:mr-12">
                        Odedis
                    </a>
                    <a
                        href="javascript:;"
                        target="_blank"
                        class="mb-2 mr-4 text-slate-400 sm:mb-0 xl:mr-12">
                        A propos
                    </a>
                    <a
                        href="javascript:;"
                        target="_blank"
                        class="mb-2 mr-4 text-slate-400 sm:mb-0 xl:mr-12">
                        Team
                    </a>
                    <a
                        href="javascript:;"
                        target="_blank"
                        class="mb-2 mr-4 text-slate-400 sm:mb-0 xl:mr-12">
                        Products
                    </a>
                    <a
                        href="javascript:;"
                        target="_blank"
                        class="mb-2 mr-4 text-slate-400 sm:mb-0 xl:mr-12">
                        Blog
                    </a>
                    <a
                        href="javascript:;"
                        target="_blank"
                        class="mb-2 mr-4 text-slate-400 sm:mb-0 xl:mr-12">
                        Pricing
                    </a>
                </div>
                <div
                    class="flex-shrink-0 w-full max-w-full mx-auto mt-2 mb-6 text-center lg:flex-0 lg:w-8/12">
                    <a href="javascript:;" target="_blank" class="mr-6 text-slate-400">
                        <span class="text-lg fab fa-dribbble"></span>
                    </a>
                    <a href="javascript:;" target="_blank" class="mr-6 text-slate-400">
                        <span class="text-lg fab fa-twitter"></span>
                    </a>
                    <a href="javascript:;" target="_blank" class="mr-6 text-slate-400">
                        <span class="text-lg fab fa-instagram"></span>
                    </a>
                    <a href="javascript:;" target="_blank" class="mr-6 text-slate-400">
                        <span class="text-lg fab fa-pinterest"></span>
                    </a>
                    <a href="javascript:;" target="_blank" class="mr-6 text-slate-400">
                        <span class="text-lg fab fa-github"></span>
                    </a>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3">
                <div class="w-8/12 max-w-full px-3 mx-auto mt-1 text-center flex-0">
                    <p class="mb-0 text-slate-400">
                        Copyright ©
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                        Soft by Creative Tim.
                        <span class="w-full"> Distributed by ❤️ Odedis </span>
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>
<!-- plugin for charts  -->
<script src="{{asset('../Admin-Dashboard/assets/js/plugins/chartjs.min.js')}}" async></script>
<!-- plugin for scrollbar  -->
<script src="{{asset('../Admin-Dashboard/assets/js/plugins/perfect-scrollbar.min.js')}}" async></script>
<!-- github button -->
<script async defer src="https://buttons.github.io/buttons.js'}}"></script>
<!-- main script file  -->
<script src="{{asset('../Admin-Dashboard/assets/js/soft-ui-dashboard-tailwind.js?v=1.0.5')}}" async></script>

</html>