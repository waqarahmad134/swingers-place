@extends('layouts.app')
@section('full-width')
    
    <!-- hero section -->
    <section class="hero relative overflow-hidden">
      <div
        class="flex flex-col md:flex-row items-center justify-between md:px-20 px-5 py-12 md:py-0 bg-gradient-to-br from-[#8B1538] via-[#A41F4D] to-[#8B1538] relative"
        style="height: 650px; background-image: url('{{ asset('assets/banner.png') }}'); background-size: cover; background-position: right center; background-repeat: no-repeat;"
      >
        
        <!-- Left Content -->
        <div class="w-full md:w-1/2 z-10 relative">
          <!-- heading -->
          <div class="text-center md:text-left">
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-4" style="font-weight: 700; line-height: 1.2;">
              Dating for Naughty<br>Adults
            </h1>
          </div>

          <!-- paragraph -->
          <p class="text-white text-base md:text-lg text-center md:text-left mb-6">
            Meet real people nearby who are ready for genuine connections
          </p>

          <!-- Free Sign Up Label -->
          <h3 class="text-white text-center md:text-left text-lg font-semibold mb-4">
            Free Sign Up
          </h3>

          <!-- Sign Up Buttons Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
            <a
              href="{{ route('register') }}?type=couple"
              class="px-6 py-4 bg-white rounded-lg text-[#8B1538] font-semibold flex items-center justify-center gap-2 hover:bg-gray-100 transition"
            >
              <i class="ri-team-line text-xl"></i> We are a Couple
            </a>
            <a
              href="{{ route('register') }}?type=single_female"
              class="px-6 py-4 bg-white rounded-lg text-[#E60076] font-semibold flex items-center justify-center gap-2 hover:bg-gray-100 transition"
            >
              <i class="ri-women-line text-xl"></i> I'm a Single Female
            </a>
            <a
              href="{{ route('register') }}?type=single_male"
              class="px-6 py-4 bg-white rounded-lg text-[#8B1538] font-semibold flex items-center justify-center gap-2 hover:bg-gray-100 transition"
            >
              <i class="ri-user-3-line text-xl"></i> I'm a Single Male
            </a>
            <a
              href="{{ route('register') }}?type=non_binary"
              class="px-6 py-4 bg-white rounded-lg text-[#E60076] font-semibold flex items-center justify-center gap-2 hover:bg-gray-100 transition"
            >
              <i class="ri-genderless-line text-xl"></i> Non-binary
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION: Choose Your Path -->
    {{--<section class="py-20 sm:px-0 px-5">
      <div class="pb-[64px] mx-auto sm:max-w-[730px] max-w-full">
        <h2
          class="pb-6 text-[rgba(16,24,40,1)] font-arimo font-normal text-4xl leading-none tracking-normal text-center"
        >
          Choose Your Path
        </h2>

        <p
          class="font-arimo font-normal text-xl leading-7 tracking-normal text-center mx-auto text-[rgba(74,85,101,1)]"
        >
          Whether you're an individual, couple, or business, we have the perfect
          profile type for you
        </p>
      </div>

      <!-- CARDS -->
      <div class="flex sm:flex-row flex-col gap-[34px] sm:max-w-[1024px] max-w-full mx-auto">
        <div class="border-2 border-gray-200 rounded-3xl p-8">
          <img class="size-[64px] mb-6" src="assets/person.png" alt="person" />
          <h3 class="font-arimo font-normal text-3xl leading-9 tracking-normal">
            Personal Profile
          </h3>
          <p class="mt-3 text-[rgba(74,85,101,1)] leading-[24px]">
            Join as an individual or couple to connect with like-minded people.
            Perfect for dating, friendships, and social connections.
          </p>

          <div class="mt-6 flex flex-col gap-4">
            <div class="flex gap-2">
              <i
                class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"
              ></i>
              <p class="text-[rgba(54,65,83,1)]">Smart matchings</p>
            </div>

            <div class="flex gap-2">
              <i
                class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"
              ></i>
              <p class="text-[rgba(54,65,83,1)]">Event access</p>
            </div>

            <div class="flex gap-2">
              <i
                class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"
              ></i>
              <p class="text-[rgba(54,65,83,1)]">Private messaging</p>
            </div>

            <div class="flex gap-2">
              <i
                class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"
              ></i>
              <p class="text-[rgba(54,65,83,1)]">Video calls</p>
            </div>
          </div>

          <button
            class="mt-8 flex items-center justify-center h-10 px-4 rounded-full w-full text-white font-arimo font-normal text-sm leading-5 tracking-normal text-center bg-[linear-gradient(90deg,#9810FA_0%,#E60076_100%)]"
          >
            Create Personal Profile
          </button>
        </div>

        <div class="border-2 border-gray-200 rounded-3xl p-8">
          <img
            class="size-[64px] mb-6"
            src="assets/business.png"
            alt="person"
          />
          <h3 class="font-arimo font-normal text-3xl leading-9 tracking-normal">
            Business Profile
          </h3>
          <p class="mt-3 text-[rgba(74,85,101,1)] leading-[24px]">
            Promote your venue, events, or services to our engaged community.
            Perfect for clubs, resorts, and service providers.
          </p>

          <div class="mt-6 flex flex-col gap-4">
            <div class="flex gap-2">
              <i class="text-[rgba(230,0,118,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Event promotion</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(230,0,118,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Business analytics</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(230,0,118,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Direct bookings</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(230,0,118,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Featured listings</p>
            </div>
          </div>

          <button
            class="mt-8 flex items-center justify-center h-10 px-4 rounded-full w-full text-white font-arimo font-normal text-sm leading-5 tracking-normal text-center bg-[linear-gradient(90deg,#9810FA_0%,#E60076_100%)]"
          >
            Create Business Profile
          </button>
        </div>
      </div>
    </section>--}}

    <!-- SECTION: Why Choose Us -->
    <section class="py-16 sm:py-20 px-5 bg-gradient-to-b from-white to-[#F9FAFB]">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
          <div class="inline-block bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 px-4 py-1.5 rounded-full mb-4">
            <span class="text-[#9810FA] dark:text-purple-400 text-sm font-semibold">Why Choose Us</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
            Find Your Perfect Match
          </h2>
          <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Join a community where genuine connections happen. Experience dating reimagined with safety, authenticity, and excitement.
          </p>
        </div>

        <!-- Feature Cards Grid -->
        <div class="grid md:grid-cols-3 grid-cols-1 gap-6 mb-12">
          <!-- Card 1 -->
          <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:border-[#9810FA] dark:hover:border-purple-500">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-bl-3xl rounded-tr-2xl opacity-50"></div>
            <div class="relative">
              <div class="w-16 h-16 bg-gradient-to-br from-[#9810FA] to-[#E60076] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <i class="ri-heart-3-fill text-white text-2xl"></i>
              </div>
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Smart Matching</h3>
              <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                Our advanced algorithm learns your preferences and connects you with people who share your interests, values, and lifestyle.
              </p>
            </div>
          </div>

          <!-- Card 2 -->
          <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:border-[#9810FA] dark:hover:border-purple-500">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 rounded-bl-3xl rounded-tr-2xl opacity-50"></div>
            <div class="relative">
              <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <i class="ri-shield-check-fill text-white text-2xl"></i>
              </div>
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">100% Verified</h3>
              <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                Every member is verified through identity and photo verification. Your safety and privacy are our top priorities.
              </p>
            </div>
          </div>

          <!-- Card 3 -->
          <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:border-[#9810FA] dark:hover:border-purple-500">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-pink-100 to-rose-100 dark:from-pink-900/30 dark:to-rose-900/30 rounded-bl-3xl rounded-tr-2xl opacity-50"></div>
            <div class="relative">
              <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <i class="ri-video-chat-fill text-white text-2xl"></i>
              </div>
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Video Chat</h3>
              <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                Connect face-to-face before meeting in person. Built-in video calls let you build trust and chemistry safely.
              </p>
            </div>
          </div>

          <!-- Card 4 -->
          <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:border-[#9810FA] dark:hover:border-purple-500">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-orange-100 to-amber-100 dark:from-orange-900/30 dark:to-amber-900/30 rounded-bl-3xl rounded-tr-2xl opacity-50"></div>
            <div class="relative">
              <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-amber-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <i class="ri-calendar-event-fill text-white text-2xl"></i>
              </div>
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Exclusive Events</h3>
              <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                Join curated events, parties, and meetups. Plan travel adventures and connect with members worldwide.
              </p>
            </div>
          </div>

          <!-- Card 5 -->
          <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:border-[#9810FA] dark:hover:border-purple-500">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 rounded-bl-3xl rounded-tr-2xl opacity-50"></div>
            <div class="relative">
              <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <i class="ri-message-3-fill text-white text-2xl"></i>
              </div>
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Instant Messaging</h3>
              <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                Real-time chat with photos, voice messages, and read receipts. Stay connected wherever you are.
              </p>
            </div>
          </div>

          <!-- Card 6 -->
          <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:border-[#9810FA] dark:hover:border-purple-500">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 rounded-bl-3xl rounded-tr-2xl opacity-50"></div>
            <div class="relative">
              <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <i class="ri-global-line text-white text-2xl"></i>
              </div>
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Global Network</h3>
              <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                Connect with thousands of verified members worldwide. Find local matches or plan international meetups.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- How it works -->
    <section class="py-16 sm:py-20 px-5 bg-white dark:bg-gray-900">
      <div class="max-w-6xl mx-auto">
        <div class="text-center mb-12">
          <div class="inline-block bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 px-4 py-1.5 rounded-full mb-4">
            <span class="text-[#9810FA] dark:text-purple-400 text-sm font-semibold">Simple Steps</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
            How It Works
          </h2>
          <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Start your journey to meaningful connections in just four easy steps
          </p>
        </div>

        <!-- Steps Timeline -->
        <div class="relative">
          <!-- Connection Line (hidden on mobile) -->
          <div class="hidden md:block absolute left-1/2 top-0 bottom-0 w-0.5 bg-gradient-to-b from-[#9810FA] via-[#E60076] to-[#9810FA] transform -translate-x-1/2" style="height: calc(100% - 80px); margin-top: 40px;"></div>

          <div class="space-y-12 md:space-y-16">
            <!-- Step 1 -->
            <div class="relative flex flex-col md:flex-row items-center gap-6 md:gap-8">
              <div class="flex-shrink-0 w-full md:w-auto">
                <div class="bg-gradient-to-br from-[#9810FA] to-[#E60076] rounded-2xl p-6 md:p-8 shadow-xl w-full md:w-64 h-48 md:h-56 flex items-center justify-center group hover:scale-105 transition-transform">
                  <div class="text-center">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-4 group-hover:rotate-12 transition-transform">
                      <i class="ri-user-add-line text-white text-3xl"></i>
                    </div>
                    <span class="text-white text-sm font-semibold">Step 1</span>
                  </div>
                </div>
              </div>
              <div class="flex-1 bg-white dark:bg-gray-800 rounded-2xl p-6 md:p-8 shadow-lg border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-3">
                  <span class="w-8 h-8 bg-gradient-to-br from-[#9810FA] to-[#E60076] rounded-full flex items-center justify-center text-white font-bold">1</span>
                  <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Create Your Profile</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                  Sign up in minutes with our simple onboarding process. Add your photos, share your interests, and tell us what you're looking for. Your profile is your first impression - make it count!
                </p>
              </div>
            </div>

            <!-- Step 2 -->
            <div class="relative flex flex-col md:flex-row-reverse items-center gap-6 md:gap-8">
              <div class="flex-shrink-0 w-full md:w-auto">
                <div class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl p-6 md:p-8 shadow-xl w-full md:w-64 h-48 md:h-56 flex items-center justify-center group hover:scale-105 transition-transform">
                  <div class="text-center">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-4 group-hover:rotate-12 transition-transform">
                      <i class="ri-shield-check-line text-white text-3xl"></i>
                    </div>
                    <span class="text-white text-sm font-semibold">Step 2</span>
                  </div>
                </div>
              </div>
              <div class="flex-1 bg-white dark:bg-gray-800 rounded-2xl p-6 md:p-8 shadow-lg border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-3">
                  <span class="w-8 h-8 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold">2</span>
                  <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Get Verified</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                  Complete our quick verification process to build trust with other members. Verified profiles get more matches and access to premium features. Safety first, always.
                </p>
              </div>
            </div>

            <!-- Step 3 -->
            <div class="relative flex flex-col md:flex-row items-center gap-6 md:gap-8">
              <div class="flex-shrink-0 w-full md:w-auto">
                <div class="bg-gradient-to-br from-pink-500 to-rose-500 rounded-2xl p-6 md:p-8 shadow-xl w-full md:w-64 h-48 md:h-56 flex items-center justify-center group hover:scale-105 transition-transform">
                  <div class="text-center">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-4 group-hover:rotate-12 transition-transform">
                      <i class="ri-search-heart-line text-white text-3xl"></i>
                    </div>
                    <span class="text-white text-sm font-semibold">Step 3</span>
                  </div>
                </div>
              </div>
              <div class="flex-1 bg-white dark:bg-gray-800 rounded-2xl p-6 md:p-8 shadow-lg border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-3">
                  <span class="w-8 h-8 bg-gradient-to-br from-pink-500 to-rose-500 rounded-full flex items-center justify-center text-white font-bold">3</span>
                  <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Discover Matches</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                  Browse through verified profiles, receive personalized match suggestions, and use advanced filters to find exactly what you're looking for. Your perfect match is just a swipe away.
                </p>
              </div>
            </div>

            <!-- Step 4 -->
            <div class="relative flex flex-col md:flex-row-reverse items-center gap-6 md:gap-8">
              <div class="flex-shrink-0 w-full md:w-auto">
                <div class="bg-gradient-to-br from-orange-500 to-amber-500 rounded-2xl p-6 md:p-8 shadow-xl w-full md:w-64 h-48 md:h-56 flex items-center justify-center group hover:scale-105 transition-transform">
                  <div class="text-center">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-4 group-hover:rotate-12 transition-transform">
                      <i class="ri-hearts-line text-white text-3xl"></i>
                    </div>
                    <span class="text-white text-sm font-semibold">Step 4</span>
                  </div>
                </div>
              </div>
              <div class="flex-1 bg-white dark:bg-gray-800 rounded-2xl p-6 md:p-8 shadow-lg border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-3">
                  <span class="w-8 h-8 bg-gradient-to-br from-orange-500 to-amber-500 rounded-full flex items-center justify-center text-white font-bold">4</span>
                  <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Connect & Meet</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                  Start conversations, video chat to build chemistry, and arrange meetups at events or private locations. We provide the tools - you create the magic. Remember to always meet safely!
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION: Everything You Need to Connect -->
    {{--<section class="bg-[linear-gradient(135deg,#FAF5FF_0%,#FDF2F8_100%)] py-[32px] sm:px-0 px-5 bg-[#F9FAFB]">
      <div class="pb-[64px] mx-auto sm:max-w-[700px] max-w-full">
        <div
          class="bg-[rgba(243,232,255,1)] py-[2px] max-w-[62px] mx-auto mb-6 px-[7px] text-[rgba(130,0,219,1)] rounded-xl"
        >
          <h2 class="text-xs font-thin leading-[16px]">Testimonials</h2>
        </div>
        <h2
          class="pb-6 text-[rgba(16,24,40,1)] font-arimo font-normal text-4xl leading-none tracking-normal text-center"
        >
          Loved by Our Community
        </h2>

        <p
          class="font-arimo font-normal text-xl leading-7 tracking-normal text-center text-[rgba(74,85,101,1)]"
        >
          See what our members have to say about their experience
        </p>
      </div>

      <div
        class="sm:max-w-[1280px] max-w-full grid md:grid-cols-3 grid-cols-1 gap-8 mx-auto"
      >
        <div
          class="bg-white flex flex-col gap-[48px] p-8 border-[#E5E7EB] border rounded-2xl"
        >
          <img src="assets/quote.png" class="size-[40px]" alt="heart" />
          <p class="text-[#4A5565] leading-[24px]">
            We've met amazing people through swingers place! The verification system
            makes us feel safe, and the events are fantastic. Best platform
            we've tried!
          </p>
          <img
            src="assets/stars.png"
            class="h-auto object-cover md:h-5"
            alt="heart"
          />

          <div class="flex gap-4 items-center">
            <img src="assets/sm-name.png" class="size-[48px]" alt="heart" />
            <div>
              <h6 class="text-[#101828] leading-[24px]">Sarah & Mike</h6>
              <p class="text-[#4A5565] text-sm">Couple Member</p>
            </div>
          </div>
        </div>

        <div
          class="bg-white flex flex-col gap-[48px] p-8 border-[#E5E7EB] border rounded-2xl"
        >
          <img src="assets/quote.png" class="size-[40px]" alt="heart" />
          <p class="text-[#4A5565] leading-[24px]">
            The community here is genuine and respectful. I love the travel
            planning features - I've made connections in 5 different countries!
          </p>
          <img
            src="assets/stars.png"
            class="h-auto object-cover md:h-5"
            alt="heart"
          />

          <div class="flex gap-4 items-center">
            <img src="assets/jm name.png" class="size-[48px]" alt="heart" />
            <div>
              <h6 class="text-[#101828] leading-[24px]">Jessica Moore</h6>
              <p class="text-[#4A5565] text-sm">Premium Member</p>
            </div>
          </div>
        </div>

        <div
          class="bg-white flex flex-col gap-[48px] p-8 border-[#E5E7EB] border rounded-2xl"
        >
          <img src="assets/quote.png" class="size-[40px]" alt="heart" />
          <p class="text-[#4A5565] leading-[24px]">
            swingers place has been incredible for our business. We host monthly
            events and the platform makes it easy to reach engaged members.
          </p>
          <img
            src="assets/stars.png"
            class="h-auto object-cover md:h-5"
            alt="heart"
          />

          <div class="flex gap-4 items-center">
            <img src="assets/bp name.png" class="size-[48px]" alt="heart" />
            <div>
              <h6 class="text-[#101828] leading-[24px]">
                Beach Paradise Resort
              </h6>
              <p class="text-[#4A5565] text-sm">Business Member</p>
            </div>
          </div>
        </div>
      </div>
    </section>--}}

    {{--<section class="py-20 sm:px-0 px-5">
      <div class="pb-[64px] mx-auto sm:max-w-[700px] max-w-full">
        <div class="bg-[rgba(243,232,255,1)] py-[2px] max-w-[62px] mx-auto mb-6 px-[7px] text-[rgba(130,0,219,1)] rounded-xl">
          <h2 class="text-xs font-thin leading-[16px] text-center">Pricing</h2>
        </div>
        <h2 class="pb-6 text-[rgba(16,24,40,1)] font-arimo font-normal text-4xl leading-none tracking-normal text-center">
          Choose Your Plan
        </h2>

        <p class="font-arimo font-normal text-xl leading-7 tracking-normal text-center text-[rgba(74,85,101,1)]">
          Start free, upgrade when you're ready. Cancel anytime.
        </p>
      </div>

      <!-- CARDS -->
      <div class="sm:max-w-[1280px] max-w-full grid md:grid-cols-3 grid-cols-1 gap-8 mx-auto">
        <!-- free -->
        <div class="bg-white flex flex-col p-8 border-[#E5E7EB] border rounded-2xl">
          <h3 class="text-[#101828] text-center text-xl pb-2">Basic</h3>
          <p class="text-[#4A5565] text-center leading-[24px]">
            Perfect for getting started
          </p>

          <p class="text-[#101828] text-center pb-2 text-[48px]">Free</p>
          <p class="text-[#4A5565] text-center leading-[24px]">forever</p>

          <div class="flex flex-col gap-4 pt-[56px]">
            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Smart matchings</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Create profile</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Create profile</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Join public events</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Basic search filters</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Community forum access</p>
            </div>
          </div>

          <button class="bg-[#F3F4F6] h-[48px] rounded-full mt-[56px] text-black">
            Get Started
          </button>
        </div>

        <!-- Premium -->
        <div class="scale-[1.05] relatives border-2 border-[#9810FA] bg-white flex flex-col p-8 rounded-2xl shadow-[0px_25px_50px_-12px_#00000040]">
          <span class="-mt-[41px] mb-[21px] text-white bg-[linear-gradient(90deg,#9810FA_0%,#E60076_100%)] py-0.5 text-xs w-[96px] rounded-xl text-center mx-auto">Most Popular</span>
          <h3 class="text-[#101828] text-center text-xl pb-2">Premium</h3>
          <p class="text-[#4A5565] text-center leading-[D24px]">
            Most popular choice
          </p>

          <p class="text-[#101828] text-center pb-2 text-[48px]">$29</p>
          <p class="text-[#4A5565] text-center leading-[24px]">per month</p>

          <div class="flex flex-col gap-4 pt-[56px]">
            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Everything in Basic</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Unlimited messaging</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Advanced search filters</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">See who viewed you</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Priority in search results</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Video calls included</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Exclusive events access</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Verified badge</p>
            </div>
          </div>

          <button class="bg-[linear-gradient(90deg,#9810FA_0%,#E60076_100%)] h-[48px] rounded-full mt-[56px] text-white">
            Upgrade Now
          </button>
        </div>

        <!-- VIP -->
        <div class="bg-white flex flex-col p-8 border-[#E5E7EB] border rounded-2xl">
          <h3 class="text-[#101828] text-center text-xl pb-2">VIP</h3>
          <p class="text-[#4A5565] text-center leading-[24px]">
            The ultimate experience
          </p>

          <p class="text-[#101828] text-center pb-2 text-[48px]">$99</p>
          <p class="text-[#4A5565] text-center leading-[24px]">per month</p>

          <div class="flex flex-col gap-4 pt-[56px]">
            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Smart matchings</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Create profile</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Create profile</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Join public events</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Basic search filters</p>
            </div>

            <div class="flex gap-2">
              <i class="text-[rgba(152,16,250,1)] text-normal ri-check-fill"></i>
              <p class="text-[rgba(54,65,83,1)]">Community forum access</p>
            </div>
          </div>

          <button class="bg-[#F3F4F6] h-[48px] rounded-full mt-[56px] text-black">
            Upgrade Now
          </button>
        </div>
      </div>
    </section>--}}

    <!-- Section: Success Stories / Testimonials -->
    <section class="py-16 sm:py-20 px-5 bg-gradient-to-b from-[#F9FAFB] to-white dark:from-gray-900 dark:to-gray-800">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
          <div class="inline-block bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 px-4 py-1.5 rounded-full mb-4">
            <span class="text-[#9810FA] dark:text-purple-400 text-sm font-semibold">Success Stories</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
            Real Connections, Real Stories
          </h2>
          <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            See how our members are finding meaningful relationships and unforgettable experiences
          </p>
        </div>

        <div class="grid md:grid-cols-3 grid-cols-1 gap-6">
          <!-- Testimonial 1 -->
          <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-2xl transition-all">
            <div class="flex items-center gap-1 mb-4">
              <i class="ri-star-fill text-yellow-400"></i>
              <i class="ri-star-fill text-yellow-400"></i>
              <i class="ri-star-fill text-yellow-400"></i>
              <i class="ri-star-fill text-yellow-400"></i>
              <i class="ri-star-fill text-yellow-400"></i>
            </div>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-6">
              "We've met incredible people through this platform! The verification system gives us confidence, and the events are always amazing. Best decision we made!"
            </p>
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-gradient-to-br from-pink-400 to-rose-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                SM
              </div>
              <div>
                <h4 class="font-semibold text-gray-900 dark:text-white">Sarah & Mike</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">Verified Couple</p>
              </div>
            </div>
          </div>

          <!-- Testimonial 2 -->
          <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-2xl transition-all">
            <div class="flex items-center gap-1 mb-4">
              <i class="ri-star-fill text-yellow-400"></i>
              <i class="ri-star-fill text-yellow-400"></i>
              <i class="ri-star-fill text-yellow-400"></i>
              <i class="ri-star-fill text-yellow-400"></i>
              <i class="ri-star-fill text-yellow-400"></i>
            </div>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-6">
              "The community here is genuine and respectful. I love how easy it is to connect with people who share my interests. Made friends in 5 different countries!"
            </p>
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                JM
              </div>
              <div>
                <h4 class="font-semibold text-gray-900 dark:text-white">Jessica M.</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">Premium Member</p>
              </div>
            </div>
          </div>

          <!-- Testimonial 3 -->
          <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-2xl transition-all">
            <div class="flex items-center gap-1 mb-4">
              <i class="ri-star-fill text-yellow-400"></i>
              <i class="ri-star-fill text-yellow-400"></i>
              <i class="ri-star-fill text-yellow-400"></i>
              <i class="ri-star-fill text-yellow-400"></i>
              <i class="ri-star-fill text-yellow-400"></i>
            </div>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-6">
              "As someone new to this lifestyle, I was nervous at first. But the platform made it so easy to meet like-minded people. The video chat feature helped me feel comfortable before meeting in person."
            </p>
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                AL
              </div>
              <div>
                <h4 class="font-semibold text-gray-900 dark:text-white">Alex L.</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">New Member</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section: FAQ -->
    <section class="py-16 sm:py-20 px-5 bg-white dark:bg-gray-900">
      <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
          <div class="inline-block bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 px-4 py-1.5 rounded-full mb-4">
            <span class="text-[#9810FA] dark:text-purple-400 text-sm font-semibold">Got Questions?</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
            Frequently Asked Questions
          </h2>
          <p class="text-lg text-gray-600 dark:text-gray-400">
            Everything you need to know to get started
          </p>
        </div>

        <div class="space-y-4">
          <!-- FAQ 1 -->
          <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border-2 border-gray-100 dark:border-gray-700 hover:border-[#9810FA] dark:hover:border-purple-500 transition-all shadow-sm">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
              <i class="ri-shield-check-line text-[#9810FA]"></i>
              Is the platform safe and secure?
            </h3>
            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
              Absolutely! We use industry-leading security measures including identity verification, photo verification, encrypted communications, and strict community guidelines. Our dedicated moderation team reviews all content and responds to reports within 24 hours. Your privacy and safety are our top priorities.
            </p>
          </div>

          <!-- FAQ 2 -->
          <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border-2 border-gray-100 dark:border-gray-700 hover:border-[#9810FA] dark:hover:border-purple-500 transition-all shadow-sm">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
              <i class="ri-user-line text-[#9810FA]"></i>
              How do I create a profile?
            </h3>
            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
              Creating a profile is quick and easy! Simply click "Sign Up" and choose your profile type (Couple, Single Female, Single Male, or Non-binary). Follow our guided 9-step onboarding process to add photos, preferences, and information about what you're looking for. The whole process takes just a few minutes.
            </p>
          </div>

          <!-- FAQ 3 -->
          <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border-2 border-gray-100 dark:border-gray-700 hover:border-[#9810FA] dark:hover:border-purple-500 transition-all shadow-sm">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
              <i class="ri-search-line text-[#9810FA]"></i>
              How does matching work?
            </h3>
            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
              Our smart matching algorithm learns from your preferences, interests, and behavior to suggest compatible members. You can also browse profiles manually using advanced filters for location, age, interests, and more. The more complete your profile, the better your matches will be!
            </p>
          </div>

          <!-- FAQ 4 -->
          <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border-2 border-gray-100 dark:border-gray-700 hover:border-[#9810FA] dark:hover:border-purple-500 transition-all shadow-sm">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
              <i class="ri-money-dollar-circle-line text-[#9810FA]"></i>
              Is it really free to join?
            </h3>
            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
              Yes! Basic membership is completely free forever. You can create a profile, browse members, send messages, and attend public events at no cost. We also offer premium memberships with additional features like advanced search filters, priority in search results, and exclusive event access.
            </p>
          </div>

          <!-- FAQ 5 -->
          <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border-2 border-gray-100 dark:border-gray-700 hover:border-[#9810FA] dark:hover:border-purple-500 transition-all shadow-sm">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
              <i class="ri-calendar-event-line text-[#9810FA]"></i>
              What kind of events are available?
            </h3>
            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
              We host a variety of events including local meetups, parties, travel adventures, and exclusive gatherings. Events range from casual social mixers to themed parties and international travel meetups. Premium members get early access to exclusive events and special pricing.
            </p>
          </div>

          <!-- FAQ 6 -->
          <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border-2 border-gray-100 dark:border-gray-700 hover:border-[#9810FA] dark:hover:border-purple-500 transition-all shadow-sm">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
              <i class="ri-eye-off-line text-[#9810FA]"></i>
              Can I keep my profile private?
            </h3>
            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
              Yes! You have full control over your privacy settings. You can choose who can see your profile, photos, and information. You can also block users, control who can message you, and adjust visibility settings for different aspects of your profile. Your privacy is in your hands.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION: CTA -->
    <section class="relative px-5 sm:px-0 py-16 md:py-24 overflow-hidden">
      <!-- Background with gradient -->
      <div class="absolute inset-0 bg-gradient-to-r from-[#9810FA] via-[#E60076] to-[#9810FA]"></div>
      <!-- Decorative elements -->
      <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
      <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
      
      <div class="relative max-w-4xl mx-auto text-center">
        <h1 class="text-white text-3xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
          Ready to Find Your<br class="hidden md:block"> Perfect Match?
        </h1>
        <p class="text-white/90 text-lg md:text-xl mb-8 max-w-2xl mx-auto leading-relaxed">
          Join thousands of verified members who are already making meaningful connections. Your journey to exciting new experiences starts here.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
          <a href="{{ route('register') }}" class="group bg-white text-[#9810FA] px-8 py-4 rounded-full font-semibold text-base md:text-lg hover:bg-gray-100 hover:scale-105 transition-all duration-200 shadow-xl flex items-center gap-2">
            <span>Create Free Account</span>
            <i class="ri-arrow-right-line group-hover:translate-x-1 transition-transform"></i>
          </a>
          <a href="#features" class="bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 px-8 py-4 rounded-full font-semibold text-base md:text-lg hover:bg-white/20 hover:scale-105 transition-all duration-200">
            Learn More
          </a>
        </div>
        <p class="text-white/80 text-sm mt-6">
          <i class="ri-checkbox-circle-line"></i> Free to join • No credit card required • Cancel anytime
        </p>
      </div>
    </section>

@endsection

