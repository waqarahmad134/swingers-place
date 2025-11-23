<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about',
                'content' => '<p class="text-lg text-gray-700 dark:text-gray-300">
                    Welcome to ' . config('app.name') . '! We are committed to providing you with the highest quality products and exceptional service.
                </p>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Our Mission</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    Our mission is to deliver fresh, quality products to our customers while maintaining the highest standards of service and integrity.
                </p>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Who We Are</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    We are a dedicated team passionate about providing the best products and services to our community. With years of experience, we understand what our customers need and work tirelessly to exceed their expectations.
                </p>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Our Values</h2>
                <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 space-y-2">
                    <li><strong>Quality:</strong> We never compromise on the quality of our products.</li>
                    <li><strong>Integrity:</strong> We conduct our business with honesty and transparency.</li>
                    <li><strong>Customer Focus:</strong> Our customers are at the heart of everything we do.</li>
                    <li><strong>Innovation:</strong> We continuously improve our services and offerings.</li>
                </ul>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Contact Us</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    Have questions or want to learn more? Feel free to <a href="/contact" class="text-primary hover:underline">contact us</a>.
                </p>',
                'meta_title' => 'About Us - ' . config('app.name'),
                'meta_description' => 'Learn more about ' . config('app.name') . ' and our mission to provide quality products and exceptional service.',
                'is_active' => true,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'content' => '<p class="text-gray-700 dark:text-gray-300 mb-6">
                    We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.
                </p>

                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">Email</h3>
                            <a href="mailto:info@example.com" class="text-gray-600 hover:text-primary dark:text-gray-400">info@example.com</a>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">Phone</h3>
                            <a href="tel:+923039345647" class="text-gray-600 hover:text-primary dark:text-gray-400">0303-9345647</a>
                        </div>
                    </div>
                </div>',
                'meta_title' => 'Contact Us - ' . config('app.name'),
                'meta_description' => 'Get in touch with ' . config('app.name') . '. Send us a message and we\'ll respond as soon as possible.',
                'is_active' => true,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy',
                'content' => '<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    Last updated: ' . now()->format('F d, Y') . '
                </p>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Introduction</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    At ' . config('app.name') . ', we respect your privacy and are committed to protecting your personal data. This privacy policy explains how we collect, use, and safeguard your information when you visit our website.
                </p>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Information We Collect</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    We may collect the following types of information:
                </p>
                <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 space-y-2">
                    <li>Personal identification information (name, email address, phone number)</li>
                    <li>Account information (username, password, profile preferences)</li>
                    <li>Usage data (how you interact with our website)</li>
                    <li>Technical data (IP address, browser type, device information)</li>
                </ul>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">How We Use Your Information</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    We use the information we collect to:
                </p>
                <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 space-y-2">
                    <li>Provide and maintain our services</li>
                    <li>Process your requests and transactions</li>
                    <li>Send you important updates and notifications</li>
                    <li>Improve our website and services</li>
                    <li>Comply with legal obligations</li>
                </ul>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Data Security</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    We implement appropriate technical and organizational measures to protect your personal data against unauthorized access, alteration, disclosure, or destruction.
                </p>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Your Rights</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    You have the right to:
                </p>
                <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 space-y-2">
                    <li>Access your personal data</li>
                    <li>Correct inaccurate data</li>
                    <li>Request deletion of your data</li>
                    <li>Object to processing of your data</li>
                    <li>Request data portability</li>
                </ul>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Cookies</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    We use cookies to enhance your experience on our website. You can control cookie preferences through your browser settings.
                </p>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Contact Us</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    If you have questions about this privacy policy, please <a href="/contact" class="text-primary hover:underline">contact us</a>.
                </p>',
                'meta_title' => 'Privacy Policy - ' . config('app.name'),
                'meta_description' => 'Read our privacy policy to understand how ' . config('app.name') . ' collects, uses, and protects your personal information.',
                'is_active' => true,
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms',
                'content' => '<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    Last updated: ' . now()->format('F d, Y') . '
                </p>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Agreement to Terms</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    By accessing and using ' . config('app.name') . ', you accept and agree to be bound by the terms and provision of this agreement.
                </p>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Use License</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    Permission is granted to temporarily access the materials on our website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:
                </p>
                <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 space-y-2">
                    <li>Modify or copy the materials</li>
                    <li>Use the materials for any commercial purpose or for any public display</li>
                    <li>Attempt to reverse engineer any software contained on the website</li>
                    <li>Remove any copyright or other proprietary notations from the materials</li>
                </ul>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">User Accounts</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    When you create an account with us, you must provide accurate, complete, and current information. You are responsible for safeguarding your account credentials and for all activities that occur under your account.
                </p>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Prohibited Uses</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    You may not use our website:
                </p>
                <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 space-y-2">
                    <li>In any way that violates any applicable law or regulation</li>
                    <li>To transmit any malicious code or viruses</li>
                    <li>To collect or track personal information of others</li>
                    <li>To spam, phish, or engage in any fraudulent activity</li>
                    <li>To interfere with or disrupt the website or servers</li>
                </ul>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Intellectual Property</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    The website and its original content, features, and functionality are owned by ' . config('app.name') . ' and are protected by international copyright, trademark, patent, trade secret, and other intellectual property laws.
                </p>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Limitation of Liability</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    In no event shall ' . config('app.name') . ' or its suppliers be liable for any damages arising out of the use or inability to use the materials on our website.
                </p>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Changes to Terms</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    We reserve the right to modify these terms at any time. We will notify users of any changes by updating the "Last updated" date of this policy.
                </p>

                <h2 class="mt-8 text-2xl font-bold text-gray-900 dark:text-gray-100">Contact Us</h2>
                <p class="text-gray-700 dark:text-gray-300">
                    If you have questions about these terms, please <a href="/contact" class="text-primary hover:underline">contact us</a>.
                </p>',
                'meta_title' => 'Terms of Service - ' . config('app.name'),
                'meta_description' => 'Read our terms of service to understand the rules and regulations for using ' . config('app.name') . '.',
                'is_active' => true,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }

        $this->command->info('Pages seeded successfully!');
        $this->command->info('Created/Updated: About, Contact, Privacy, Terms');
    }
}
