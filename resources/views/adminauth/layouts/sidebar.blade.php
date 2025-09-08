<!-- sidenav -->
<aside id="sidenav" class="max-w-62.5 ease-nav-brand z-990 fixed inset-y-0 my-4 ml-4 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 bg-white p-0 antialiased shadow-none transition-transform duration-200 xl:left-0 xl:translate-x-0 xl:bg-transparent">

  <!-- Logo / Dashboard link -->
  <div class="h-19.5">
    <i
      class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden"
      sidenav-close></i>
    <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap text-slate-700" href="{{ route('admin.dashboard') }}">
      <img src="../Admin-Dashboard/assets/img/Logo.png"
        class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-8"
        alt="main_logo" />
      <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">Dashboard</span>
    </a>
  </div>

  <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />

  <!-- Sidebar items -->
  <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full">
    <ul class="flex flex-col pl-0 mb-0">

      {{-- Dashboard --}}
      <li class="mt-0.5 w-full">
        <a href="{{ route('admin.dashboard') }}"
          class="py-2.7 my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors rounded-lg
           {{ request()->routeIs('admin.dashboard') 
              ? 'bg-gradient-to-tl from-purple-700 to-pink-500 text-white shadow-soft-xl font-semibold' 
              : 'text-slate-700' }}">
          <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg xl:p-2.5
               {{ request()->routeIs('admin.dashboard') ? 'bg-white text-pink-500' : 'bg-white text-slate-800' }}">
            <i class="fas fa-home text-sm"></i>
          </div>
          <span class="ml-1">Dashboard</span>
        </a>
      </li>

      {{-- Bookings --}}
      <li class="mt-0.5 w-full">
        <a href="{{ route('admin.bookings.index') }}"
          class="py-2.7 my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors rounded-lg
           {{ request()->routeIs('admin.bookings.*') 
              ? 'bg-gradient-to-tl from-purple-700 to-pink-500 text-white shadow-soft-xl font-semibold' 
              : 'text-slate-700' }}">
          <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg xl:p-2.5
               {{ request()->routeIs('admin.bookings.*') ? 'bg-white text-pink-500' : 'bg-white text-slate-800' }}">
            <i class="fas fa-calendar-check text-sm"></i>
          </div>
          <span class="ml-1">Bookings</span>
        </a>
      </li>

      {{-- Residences --}}
      <li class="mt-0.5 w-full">
        <a href="{{ route('admin.residences.index') }}"
          class="py-2.7 my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors rounded-lg
           {{ request()->routeIs('admin.residences.*') 
              ? 'bg-gradient-to-tl from-purple-700 to-pink-500 text-white shadow-soft-xl font-semibold' 
              : 'text-slate-700' }}">
          <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg xl:p-2.5
               {{ request()->routeIs('admin.residences.*') ? 'bg-white text-pink-500' : 'bg-white text-slate-800' }}">
            <i class="fas fa-building text-sm"></i>
          </div>
          <span class="ml-1">Appartements</span>
        </a>
      </li>

      {{-- Clients --}}
      <li class="mt-0.5 w-full">
        <a href="{{ route('admin.clients.index') }}"
          class="py-2.7 my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors rounded-lg
           {{ request()->routeIs('admin.clients.*') 
              ? 'bg-gradient-to-tl from-purple-700 to-pink-500 text-white shadow-soft-xl font-semibold' 
              : 'text-slate-700' }}">
          <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg xl:p-2.5
               {{ request()->routeIs('admin.clients.*') ? 'bg-white text-pink-500' : 'bg-white text-slate-800' }}">
            <i class="fas fa-users text-sm"></i>
          </div>
          <span class="ml-1">Clients</span>
        </a>
      </li>

      {{-- Paiements --}}
      <li class="mt-0.5 w-full">
        <a href="{{ route('admin.payments.index') }}"
          class="py-2.7 my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors rounded-lg
           {{ request()->routeIs('admin.payments.*') 
              ? 'bg-gradient-to-tl from-purple-700 to-pink-500 text-white shadow-soft-xl font-semibold' 
              : 'text-slate-700' }}">
          <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg xl:p-2.5
               {{ request()->routeIs('admin.payments.*') ? 'bg-white text-pink-500' : 'bg-white text-slate-800' }}">
            <i class="fas fa-credit-card text-sm"></i>
          </div>
          <span class="ml-1">Paiements</span>
        </a>
      </li>

      {{-- Rapports --}}
      <!-- <li class="mt-0.5 w-full">
        <a href="{{ route('admin.reports.index') }}"
          class="py-2.7 my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors rounded-lg
           {{ request()->routeIs('admin.reports.*') 
              ? 'bg-gradient-to-tl from-purple-700 to-pink-500 text-white shadow-soft-xl font-semibold' 
              : 'text-slate-700' }}">
          <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg xl:p-2.5
               {{ request()->routeIs('admin.reports.*') ? 'bg-white text-pink-500' : 'bg-white text-slate-800' }}">
            <i class="fas fa-chart-line text-sm"></i>
          </div>
          <span class="ml-1">Rapports</span>
        </a>
      </li> -->

      {{-- Utilisateurs --}}
      <li class="mt-0.5 w-full">
        <a href="{{ route('admin.users.index') }}"
          class="py-2.7 my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors rounded-lg
           {{ request()->routeIs('admin.users.*') 
              ? 'bg-gradient-to-tl from-purple-700 to-pink-500 text-white shadow-soft-xl font-semibold' 
              : 'text-slate-700' }}">
          <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg xl:p-2.5
               {{ request()->routeIs('admin.users.*') ? 'bg-white text-pink-500' : 'bg-white text-slate-800' }}">
            <i class="fas fa-user-cog text-sm"></i>
          </div>
          <span class="ml-1">Utilisateurs</span>
        </a>
      </li>



    </ul>
  </div><br>
  <div class="mx-4">
    <!-- load phantom colors for card after: -->
    <p
      class="invisible hidden text-gray-800 text-red-500 text-red-600 after:bg-gradient-to-tl after:from-gray-900 after:to-slate-800 after:from-blue-600 after:to-cyan-400 after:from-red-500 after:to-yellow-400 after:from-green-600 after:to-lime-400 after:from-red-600 after:to-rose-400 after:from-slate-600 after:to-slate-300 text-lime-500 text-cyan-500 text-slate-400 text-fuchsia-500"></p>
    <div
      class="after:opacity-65 after:bg-gradient-to-tl after:from-slate-600 after:to-slate-300 relative flex min-w-0 flex-col items-center break-words rounded-2xl border-0 border-solid border-blue-900 bg-white bg-clip-border shadow-none after:absolute after:top-0 after:bottom-0 after:left-0 after:z-10 after:block after:h-full after:w-full after:rounded-2xl after:content-['']"
      sidenav-card>
      <div
        class="mb-7.5 absolute h-full w-full rounded-2xl bg-cover bg-center"
        style="
              background-image: url('./assets/img/curved-images/white-curved.jpeg');
            "></div>
      <div class="relative z-20 flex-auto w-full p-4 text-left text-white">
        <div
          class="flex items-center justify-center w-8 h-8 mb-4 text-center bg-white bg-center rounded-lg icon shadow-soft-2xl">
          <i
            class="top-0 z-10 text-lg leading-none text-transparent ni ni-diamond bg-gradient-to-tl from-slate-600 to-slate-300 bg-clip-text opacity-80"
            sidenav-card-icon></i>
        </div>
        <div class="transition-all duration-200 ease-nav-brand">
          <h6 class="mb-0 text-white">Need help?</h6>
          <p class="mt-0 mb-4 text-xs font-semibold leading-tight">
            Please check our docs
          </p>
          <a
            href="https://www.creative-tim.com/learning-lab/tailwind/html/quick-start/soft-ui-dashboard/"
            target="_blank"
            class="inline-block w-full px-8 py-2 mb-0 text-xs font-bold text-center text-black uppercase transition-all ease-in bg-white border-0 border-white rounded-lg shadow-soft-md bg-150 leading-pro hover:shadow-soft-2xl hover:scale-102">Documentation</a>
        </div>
      </div>
    </div>
  </div>
</aside>