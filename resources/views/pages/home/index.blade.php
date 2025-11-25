@extends('layouts.app')
@section('full-width')
      
    
    <!-- hero section -->
    <section class="hero">
      <div
        class="flex flex-col justify-center h-[500px] md:h-[700px] md:px-20 px-5 bg-[#00000021]"
      >
        <div
          class="w-[204px] gap-2 py-1 h-[20px] px-2 text-[#9810FA] bg-white rounded-md flex items-center"
        >
          <i class="ri-sparkling-line"></i>
          <h2 class="text-xs font-thin">#1 Social Community Platform</h2>
        </div>

        <!-- heading -->
        <div>
          <h1 class="md:text-[5.6vw] text-[11vw] text-[#101828]">Connect,</h1>
          <h1
            class="gradient-text md:w-[22%] w-[50%] text-[11vw] -mt-4 md:-mt-8 md:text-[5.6vw]"
          >
            Explore,
          </h1>
          <h1 class="md:text-[5.6vw] text-[11vw] -mt-4 md:-mt-8 text-[#101828]">
            Belong
          </h1>
        </div>

        <!-- paragraph -->
        <p class="text-white text-[13px] md:text-base w-[80%] px-1 md:w-[33%]">
          Join 50,000+ members in the most advanced social community platform
          designed for meaningful connections and authentic experiences.
        </p>

        <!-- buttons -->
        <div class="flex items-center mt-6 gap-3 flex-wrap">
          <button
            class="px-4 py-3 bg-[linear-gradient(90deg,#9810FA_0%,#E60076_100%)] rounded-3xl text-white"
          >
            Start Free Today <i class="ri-arrow-right-line"></i>
          </button>
          <button class="bg-white px-3 md:px-4 py-3 rounded-3xl flex gap-3">
            <i class="ri-play-large-line"></i> Watch Demo
          </button>
        </div>

        <!-- numbers <i class="ri-star-fill"></i>-->
        <div class="flex gap-4 md:gap-24 mt-6 flex-wrap">
          <div class="text-white">
            <h1 class="md:text-4xl text-xl">50K<i class="ri-add-line"></i></h1>
            <h2 class="text-xs md:text-sm md:mt-1">Active Members</h2>
          </div>

          <div class="text-white">
            <h1 class="md:text-4xl text-xl">1M<i class="ri-add-line"></i></h1>
            <h2 class="text-xs md:text-sm md:mt-1">Connection Made</h2>
          </div>

          <div class="text-white">
            <h1 class="md:text-4xl text-xl">500<i class="ri-add-line"></i></h1>
            <h2 class="text-xs md:text-sm md:mt-1">Events Monthly</h2>
          </div>

          <div class="text-white">
            <h1 class="md:text-4xl text-xl">
              4.8 <i class="ri-star-fill"></i>
            </h1>
            <h2 class="text-xs md:text-sm md:mt-1">User Rating</h2>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION: Choose Your Path -->
    <section class="py-20 sm:px-0 px-5">
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
      <div
        class="flex sm:flex-row flex-col gap-[34px] sm:max-w-[1024px] max-w-full mx-auto"
      >
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
    </section>

    <!-- SECTION: Everything You Need to Connect -->
    <section class="py-[32px] sm:px-0 px-5 bg-[#F9FAFB]">
      <div class="pb-[64px] mx-auto sm:max-w-[700px] max-w-full">
        <div
          class="bg-[rgba(243,232,255,1)] py-[2px] max-w-[62px] mx-auto mb-6 px-[7px] text-[rgba(130,0,219,1)] rounded-xl"
        >
          <h2 class="text-xs font-thin leading-[16px]">Features</h2>
        </div>
        <h2
          class="pb-6 text-[rgba(16,24,40,1)] font-arimo font-normal text-4xl leading-none tracking-normal text-center"
        >
          Everything You Need to Connect
        </h2>

        <p
          class="font-arimo font-normal text-xl leading-7 tracking-normal text-center text-[rgba(74,85,101,1)]"
        >
          Powerful features designed to help you build meaningful relationships
          safely and authentically
        </p>
      </div>

      <!-- Grid 6 columns -->
      <div
        class="sm:max-w-[1280px] max-w-full grid md:grid-cols-3 grid-cols-1 gap-8 mx-auto"
      >
        <div
          class="bg-white flex flex-col gap-[48px] p-8 border-[#E5E7EB] border rounded-2xl"
        >
          <img src="assets/heart.png" class="size-[56px]" alt="heart" />
          <h3 class="text-[#101828] text-xl">Smart Matching</h3>
          <p class="text-[#4A5565] leading-[24px]">
            Advanced algorithms connect you with compatible people based on
            interests, preferences, and location.
          </p>
        </div>

        <div
          class="bg-white flex flex-col gap-[48px] p-8 border-[#E5E7EB] border rounded-2xl"
        >
          <img src="assets/Safe.png" class="size-[56px]" alt="heart" />
          <h3 class="text-[#101828] text-xl">Verified & Safe</h3>
          <p class="text-[#4A5565] leading-[24px]">
            Identity verification, photo verification, and strict community
            guidelines ensure a secure environment.
          </p>
        </div>

        <div
          class="bg-white flex flex-col gap-[48px] p-8 border-[#E5E7EB] border rounded-2xl"
        >
          <img src="assets/Videocall.png" class="size-[56px]" alt="heart" />
          <h3 class="text-[#101828] text-xl">Video Calls</h3>
          <p class="text-[#4A5565] leading-[24px]">
            Built-in video calling and messaging features let you connect safely
            before meeting in person.
          </p>
        </div>

        <div
          class="bg-white flex flex-col gap-[48px] p-8 border-[#E5E7EB] border rounded-2xl"
        >
          <img src="assets/Events.png" class="size-[56px]" alt="heart" />
          <h3 class="text-[#101828] text-xl">Events & Travel</h3>
          <p class="text-[#4A5565] leading-[24px]">
            Discover local events, plan travel meetups, and join exclusive
            community gatherings.
          </p>
        </div>

        <div
          class="bg-white flex flex-col gap-[48px] p-8 border-[#E5E7EB] border rounded-2xl"
        >
          <img src="assets/Chats.png" class="size-[56px]" alt="heart" />
          <h3 class="text-[#101828] text-xl">Real-time Chat</h3>
          <p class="text-[#4A5565] leading-[24px]">
            Instant messaging with media sharing, voice messages, and read
            receipts for seamless communication.
          </p>
        </div>

        <div
          class="bg-white flex flex-col gap-[48px] p-8 border-[#E5E7EB] border rounded-2xl"
        >
          <img src="assets/Global.png" class="size-[56px]" alt="heart" />
          <h3 class="text-[#101828] text-xl">Global Community</h3>
          <p class="text-[#4A5565] leading-[24px]">
            Connect locally or internationally with members from over 150
            countries worldwide.
          </p>
        </div>
      </div>
    </section>

    <!-- How it works -->
    <section class="py-[32px]">
      <div class="pb-[40px] md:pb-[64px] mx-auto sm:max-w-[700px] max-w-full">
        <div
          class="bg-[rgba(243,232,255,1)] py-[2px] max-w-[90px] mx-auto mb-6 px-[7px] text-[rgba(130,0,219,1)] rounded-xl"
        >
          <h2 class="text-xs font-thin leading-[16px]">How It Works</h2>
        </div>
        <h2
          class="pb-6 text-[rgba(16,24,40,1)] font-arimo font-normal text-4xl leading-none tracking-normal text-center"
        >
          Your Journey Starts Here
        </h2>

        <p
          class="font-arimo font-normal text-xl leading-7 tracking-normal text-center text-[rgba(74,85,101,1)]"
        >
          Four simple steps to start making meaningful connections
        </p>
      </div>

      <!-- Steps -->
      <div class="py-2 items-center flex flex-col gap-7 md:gap-10">
        <!-- step 1 -->
        <div
          class="md:w-[1024px] w-[90%] rounded-xl items-center flex gap-5 py-3 px-5 border-2 border-[#E5E7EB]"
        >
          <img src="./assets/Frame 2.png" class="w-[60px] md:w-[80px]" alt="" />

          <div>
            <h1 class="md:text-2xl text-[#101828] text-xl">
              Create Your Profile
            </h1>
            <p class="text-[#4A5565] text-xs md:text-sm mt-1">
              Sign up in minutes with our easy 9-step process. Add photos,
              preferences, and what you're looking for.
            </p>
          </div>
        </div>

        <!-- step 2 -->
        <div
          class="md:w-[1024px] w-[90%] rounded-xl items-center flex gap-5 py-3 px-5 border-2 border-[#E5E7EB]"
        >
          <img src="./assets/Frame 1.png" class="w-[60px] md:w-[80px]" alt="" />

          <div>
            <h1 class="md:text-2xl text-[#101828] text-xl">Get Verified</h1>
            <p class="text-[#4A5565] text-xs md:text-sm mt-1">
              Complete identity and photo verification to gain trust and access
              premium features.
            </p>
          </div>
        </div>

        <!-- step 3 -->
        <div
          class="md:w-[1024px] w-[90%] rounded-xl items-center flex gap-5 py-3 px-5 border-2 border-[#E5E7EB]"
        >
          <img src="./assets/Frame 3.png" class="w-[60px] md:w-[80px]" alt="" />

          <div>
            <h1 class="md:text-2xl text-[#101828] text-xl">Discover Matches</h1>
            <p class="text-[#4A5565] text-xs md:text-sm mt-1">
              Browse members, receive smart suggestions, and use advanced
              filters to find your perfect connection.
            </p>
          </div>
        </div>

        <!-- step 4 -->
        <div
          class="md:w-[1024px] w-[90%] rounded-xl items-center flex gap-5 py-3 px-5 border-2 border-[#E5E7EB]"
        >
          <img src="./assets/Frame 4.png" class="w-[60px] md:w-[80px]" alt="" />

          <div>
            <h1 class="md:text-2xl text-[#101828] text-xl">Connect & Meet</h1>
            <p class="text-[#4A5565] text-xs md:text-sm mt-1">
              Chat, video call, and arrange meetups at events or private
              locations. Stay safe with our community guidelines.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION: Everything You Need to Connect -->
    <section
      class="bg-[linear-gradient(135deg,#FAF5FF_0%,#FDF2F8_100%)] py-[32px] sm:px-0 px-5 bg-[#F9FAFB]"
    >
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
            We've met amazing people through ConnectHub! The verification system
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
            ConnectHub has been incredible for our business. We host monthly
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
    </section>

    <section class="py-20 sm:px-0 px-5">
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
    </section>

    <!-- Section: FAQ -->
    <section class="py-[32px] bg-[#F9FAFB]">
      <div class="pb-[40px] md:pb-[64px] mx-auto sm:max-w-[700px] max-w-full">
        <div
          class="bg-[rgba(243,232,255,1)] py-[2px] max-w-[40px] mx-auto mb-6 px-[7px] text-[rgba(130,0,219,1)] rounded-xl"
        >
          <h2 class="text-xs font-thin leading-[16px]">FAQ</h2>
        </div>
        <h2
          class="pb-6 text-[rgba(16,24,40,1)] font-arimo font-normal text-4xl leading-none tracking-normal text-center"
        >
          Frequently Asked Questions
        </h2>

        <p
          class="font-arimo font-normal text-xl leading-7 tracking-normal text-center text-[rgba(74,85,101,1)]"
        >
          Everything you need to know about ConnectHub
        </p>
      </div>

      <div class="py-2 items-center flex flex-col gap-7 md:gap-10">
        <!-- faq 1 -->
        <div
          class="md:w-[896px] flex-col bg-white w-[90%] rounded-xl flex md:gap-[40px] gap-[20px] p-[32px] border-2 border-[#E5E7EB]"
        >
          <div>
            <h1 class="md:text-2xl text-[#101828] text-[18px]">
              Is ConnectHub safe and secure?
            </h1>
          </div>
          <p
            class="text-[#4A5565] text-[14px] md:text-[16px] mt-1 leading-5 md:leading-7"
          >
            Yes! We use industry-leading security measures including identity
            verification, photo verification, encrypted communications, and
            strict community guidelines. Our dedicated moderation team reviews
            all content and responds to reports within 24 hours.
          </p>
        </div>

        <!-- faq 2 -->
        <div
          class="md:w-[896px] flex-col bg-white w-[90%] rounded-xl flex md:gap-[40px] gap-[20px] p-[32px] border-2 border-[#E5E7EB]"
        >
          <div>
            <h1 class="md:text-2xl text-[#101828] text-[18px]">
              Is ConnectHub safe and secure?
            </h1>
          </div>
          <p
            class="text-[#4A5565] text-[14px] md:text-[16px] mt-1 leading-5 md:leading-7"
          >
            Yes! We use industry-leading security measures including identity
            verification, photo verification, encrypted communications, and
            strict community guidelines. Our dedicated moderation team reviews
            all content and responds to reports within 24 hours.
          </p>
        </div>

        <!-- faq 3 -->
        <div
          class="md:w-[896px] flex-col bg-white w-[90%] rounded-xl flex md:gap-[40px] gap-[20px] p-[32px] border-2 border-[#E5E7EB]"
        >
          <div>
            <h1 class="md:text-2xl text-[#101828] text-[18px]">
              Is ConnectHub safe and secure?
            </h1>
          </div>
          <p
            class="text-[#4A5565] text-[14px] md:text-[16px] mt-1 leading-5 md:leading-7"
          >
            Yes! We use industry-leading security measures including identity
            verification, photo verification, encrypted communications, and
            strict community guidelines. Our dedicated moderation team reviews
            all content and responds to reports within 24 hours.
          </p>
        </div>

        <!-- faq 4 -->
        <div
          class="md:w-[896px] flex-col bg-white w-[90%] rounded-xl flex md:gap-[40px] gap-[20px] p-[32px] border-2 border-[#E5E7EB]"
        >
          <div>
            <h1 class="md:text-2xl text-[#101828] text-[18px]">
              Is ConnectHub safe and secure?
            </h1>
          </div>
          <p
            class="text-[#4A5565] text-[14px] md:text-[16px] mt-1 leading-5 md:leading-7"
          >
            Yes! We use industry-leading security measures including identity
            verification, photo verification, encrypted communications, and
            strict community guidelines. Our dedicated moderation team reviews
            all content and responds to reports within 24 hours.
          </p>
        </div>

        <!-- faq 5 -->
        <div
          class="md:w-[896px] flex-col bg-white w-[90%] rounded-xl flex md:gap-[40px] gap-[20px] p-[32px] border-2 border-[#E5E7EB]"
        >
          <div>
            <h1 class="md:text-2xl text-[#101828] text-[18px]">
              Is ConnectHub safe and secure?
            </h1>
          </div>
          <p
            class="text-[#4A5565] text-[14px] md:text-[16px] mt-1 leading-5 md:leading-7"
          >
            Yes! We use industry-leading security measures including identity
            verification, photo verification, encrypted communications, and
            strict community guidelines. Our dedicated moderation team reviews
            all content and responds to reports within 24 hours.
          </p>
        </div>

        <!-- faq 6 -->
        <div
          class="md:w-[896px] flex-col bg-white w-[90%] rounded-xl flex md:gap-[40px] gap-[20px] p-[32px] border-2 border-[#E5E7EB]"
        >
          <div>
            <h1 class="md:text-2xl text-[#101828] text-[18px]">
              Is ConnectHub safe and secure?
            </h1>
          </div>
          <p
            class="text-[#4A5565] text-[14px] md:text-[16px] mt-1 leading-5 md:leading-7"
          >
            Yes! We use industry-leading security measures including identity
            verification, photo verification, encrypted communications, and
            strict community guidelines. Our dedicated moderation team reviews
            all content and responds to reports within 24 hours.
          </p>
        </div>
      </div>
    </section>

    <!-- SECTION: CTA -->
    <section
      class="px-5 sm:px-0 md:py-[80px] py-10 flex flex-col md:gap-8 gap-4 items-center bg-gradient-to-r from-[#9810FA] to-[#E60076]"
    >
      <h1 class="text-white text-2xl md:text-[48px]">Ready to Start Your Journey?</h1>
      <p
        class="max-w-[637px] text-center text-[#F3E8FF] text-[11px] md:text-[20px] text-white font-normal"
      >
        Join thousands of members who have found meaningful connections on ConnectHub
      </p>
      <div class="flex gap-5">
        <button class="bg-[#FFFFFF] text-xs md:text-base text-[#9810FA] px-3 py-3 rounded-full md:py-4 md:px-3">
          Create Free Account <i class="ri-arrow-right-line"></i>
        </button>
        <button class="bg-white text-[#484747] text-xs md:text-base rounded-full px-4 py-3 md:px-7 md:py-4">Learn More</button>
      </div>
    </section>

      

       
@endsection

