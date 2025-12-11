
    <!-- Footer -->
    <footer class="mt-6 flex-col md:gap-10 flex py-[32px] bg-white dark:bg-gradient-to-b dark:from-gray-800 dark:to-gray-900 border-t border-gray-200 dark:border-gray-700">
      <div class="flex md:flex-row flex-col md:gap-0 gap-8 justify-between md:px-14 px-5 py-2">
        <div class="flex flex-col gap-6">
          <a href="{{ Auth::check() ? route('dashboard.members') : (Route::has('home') ? route('home') : url('/')) }}" class="group">
            <span class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white tracking-wide hover:text-[#9810FA] dark:hover:text-[#E60076] transition-colors duration-200">
              Swingers Nest
            </span>
          </a>
          <p class="text-gray-700 dark:text-gray-300 font-medium">Find your perfect match today.</p>
        </div>
        <div class="flex text-gray-700 dark:text-gray-300 gap-3 flex-col">
          <h2 class="font-semibold text-lg text-gray-900 dark:text-gray-300">Company</h2>
          <div>
            <h2 class="mb-2 font-normal text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white cursor-pointer opacity-90 hover:opacity-100 transition-opacity">About Us</h2>
            <h2 class="mb-2 font-normal text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white cursor-pointer opacity-90 hover:opacity-100 transition-opacity">Careers</h2>
            <h2 class="mb-2 font-normal text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white cursor-pointer opacity-90 hover:opacity-100 transition-opacity">Press</h2>
          </div>
        </div>
        <div class="flex text-gray-700 dark:text-gray-300 gap-3 flex-col">
          <h2 class="font-semibold text-lg text-gray-900 dark:text-gray-300">Legal</h2>
          <div>
            <h2 class="mb-2 font-normal text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white cursor-pointer opacity-90 hover:opacity-100 transition-opacity">Terms of Service</h2>
            <h2 class="mb-2 font-normal text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white cursor-pointer opacity-90 hover:opacity-100 transition-opacity">Privacy Policy</h2>
            <h2 class="mb-2 font-normal text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white cursor-pointer opacity-90 hover:opacity-100 transition-opacity">Cookie Policy</h2>
          </div>
        </div>
        <div class="flex text-gray-700 dark:text-gray-300 gap-3 flex-col">
          <h2 class="font-semibold text-lg text-gray-900 dark:text-gray-300">Support</h2>
          <div>
            <h2 class="mb-2 font-normal text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white cursor-pointer opacity-90 hover:opacity-100 transition-opacity">Help Center</h2>
            <h2 class="mb-2 font-normal text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white cursor-pointer opacity-90 hover:opacity-100 transition-opacity">Safety Tips</h2>
            <h2 class="mb-2 font-normal text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white cursor-pointer opacity-90 hover:opacity-100 transition-opacity">Contact Us</h2>
          </div>
        </div>
      </div>

      <!-- COPY  RIGHT -->
      <div class="text-gray-700 dark:text-gray-300 md:mt-1 mt-5 mx-auto text-center font-medium">
        © 2025 swingers nest. All rights reserved.
      </div>
    </footer>