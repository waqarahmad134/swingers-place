<aside class="md:min-w-[290px] min-w-[70px]" >
   <div class="items-center gap-5 flex flex-col bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
     <!-- Navigation Tabs -->
     <div class="flex gap-3 md:w-full w-[50px] flex-col flex-1 p-4">
        <!-- News Feed -->
        <a href="#" class="md:w-full w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 cursor-pointer md:rounded-3xl px-3 transition-all hover:bg-purple-600 text-gray-700 dark:text-gray-300">
            <div class="flex items-center gap-3">
                <i class="ri-home-line text-normal"></i>
                <h2 class="text-sm md:block hidden font-normal">News Feed</h2>
            </div>
        </a>

        <!-- Speed Dates -->
        <a href="#" class="md:w-full w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 cursor-pointer md:rounded-3xl px-3 transition-all hover:bg-purple-600 text-gray-700 dark:text-gray-300">
            <div class="flex items-center gap-3">
                <i class="ri-flashlight-line text-normal"></i>
                <h2 class="text-sm md:block hidden font-normal">Speed Dates</h2>
            </div>
        </a>

        <!-- Slow Dates -->
        <a href="#" class="md:w-full w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 cursor-pointer md:rounded-3xl px-3 transition-all hover:bg-purple-600 text-gray-700 dark:text-gray-300">
            <div class="flex items-center gap-3">
                <i class="ri-time-line text-normal"></i>
                <h2 class="text-sm md:block hidden font-normal">Slow Dates</h2>
            </div>
        </a>

        <!-- Visits -->
        <a href="#" class="md:w-full w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 cursor-pointer md:rounded-3xl px-3 transition-all hover:bg-purple-600 text-gray-700 dark:text-gray-300 relative">
            <div class="flex items-center gap-3">
                <i class="ri-eye-line text-normal"></i>
                <h2 class="text-sm md:block hidden font-normal">Visits</h2>
            </div>
            <span class="absolute right-2 top-1/2 -translate-y-1/2 bg-purple-600 text-white text-xs font-semibold px-2 py-0.5 rounded-lg md:block hidden">12</span>
            <span class="absolute -top-1 -right-1 bg-purple-600 text-white text-xs font-semibold w-5 h-5 rounded-full flex items-center justify-center md:hidden">12</span>
        </a>

        <!-- Members (Active) -->
        <a href="{{ route('dashboard.members') }}" class="md:w-full w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-1.5 cursor-pointer md:rounded-3xl px-3 transition-all hover:bg-purple-600 text-gray-700 dark:text-gray-300 relative">
            <div class="flex items-center gap-3">
                <i class="ri-group-line text-normal"></i>
                <h2 class="text-sm md:block hidden font-normal">Members</h2>
            </div>
        </a>

        <!-- Events & Travel -->
        <a href="#" class="md:w-full w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 cursor-pointer md:rounded-3xl px-3 transition-all hover:bg-purple-600 text-gray-700 dark:text-gray-300">
            <div class="flex items-center gap-3">
                <i class="ri-calendar-line text-normal"></i>
                <h2 class="text-sm md:block hidden font-normal">Events & Travel</h2>
            </div>
        </a>

        <!-- Photos & Videos -->
        <a href="#" class="md:w-full w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 cursor-pointer md:rounded-3xl px-3 transition-all hover:bg-purple-600 text-gray-700 dark:text-gray-300">
            <div class="flex items-center gap-3">
                <i class="ri-image-line text-normal"></i>
                <h2 class="text-sm md:block hidden font-normal">Photos & Videos</h2>
            </div>
        </a>

        <!-- Businesses -->
        <a href="#" class="md:w-full w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 cursor-pointer md:rounded-3xl px-3 transition-all hover:bg-purple-600 text-gray-700 dark:text-gray-300">
            <div class="flex items-center gap-3">
                <i class="ri-building-line text-normal"></i>
                <h2 class="text-sm md:block hidden font-normal">Businesses</h2>
            </div>
        </a>

        <!-- Forum -->
        <a href="#" class="md:w-full w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 cursor-pointer md:rounded-3xl px-3 transition-all hover:bg-purple-600 text-gray-700 dark:text-gray-300">
            <div class="flex items-center gap-3">
                <i class="ri-chat-3-line text-normal"></i>
                <h2 class="text-sm md:block hidden font-normal">Forum</h2>
            </div>
        </a>

        <!-- Upgrade -->
        <a href="#" class="md:bg-gradient-to-r from-purple-600 via-purple-500 to-pink-600 text-white md:w-full w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 cursor-pointer md:rounded-3xl px-3 transition-all hover:bg-purple-600 text-gray-700 dark:text-gray-300">
            <div class="flex items-center gap-3">
                <i class="ri-vip-crown-line text-normal"></i>
                <h2 class="text-sm md:block hidden font-normal">Upgrade</h2>
            </div>
        </a>

         
    </div>
   </div>

   <!-- Pending Tasks Section -->
   <div class="md:block hidden flex flex-col bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 md:w-full w-[50px] mt-5 mb-4 border-t border-gray-200 dark:border-gray-700 p-4">
        <div>
            <!-- Title -->
            <h3 class="text-gray-900 dark:text-white text-base mb-6">Pending Tasks</h3>
            
            <!-- Tasks List -->
            <div class="space-y-4">
                <!-- Task 1: Complete profile -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-700 dark:text-gray-300 text-sm font-normal">Complete profile</span>
                        <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">80%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-purple-600 via-purple-500 to-pink-600 rounded-full transition-all duration-300" style="width: 80%"></div>
                    </div>
                </div>

                <!-- Task 2: Add photos -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-700 dark:text-gray-300 text-sm font-normal">Add photos</span>
                        <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">60%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-purple-600 via-purple-500 to-pink-600 rounded-full transition-all duration-300" style="width: 60%"></div>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
</aside>

