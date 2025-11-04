<script setup lang="ts">
import { Facebook, Globe, Linkedin, Mail, MapPin, Phone, Twitter, Youtube } from 'lucide-vue-next';
</script>

<template>
    <footer id="footer" class="mt-24 border-t border-gray-200 bg-gradient-to-b from-gray-50 to-gray-100">
        <div class="container py-16">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                <!-- University Logo & Info -->
                <div class="lg:col-span-2">
                    <div class="mb-6 flex items-center gap-2">
                        <img src="/images/university/logo/MBSTU_logo.png" alt="MBSTU Logo" class="h-10 w-10 object-contain" />
                        <div>
                            <h3 class="text-xl font-bold text-[hsl(var(--secondary))]">MBSTU</h3>
                            <p class="text-sm text-gray-600">Mawlana Bhashani Science and Technology University</p>
                        </div>
                    </div>

                    <p class="mb-6 leading-relaxed text-gray-600">
                        Mawlana Bhashani Science and Technology University is aimed to provide its students with the knowledge of proper combination
                        of science and technology to get them prepared for the challenges of the 21st century.
                    </p>

                    <!-- Contact Info -->
                    <div class="space-y-3">
                        <div class="flex items-center text-gray-600">
                            <MapPin class="mr-3 h-4 w-4 text-[hsl(var(--secondary))]" />
                            <span class="text-sm">Santosh, Tangail-1902, Bangladesh</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <Phone class="mr-3 h-4 w-4 text-[hsl(var(--secondary))]" />
                            <span class="text-sm">+880-921-55399</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <Mail class="mr-3 h-4 w-4 text-[hsl(var(--secondary))]" />
                            <span class="text-sm">registrar@mbstu.ac.bd</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="mb-4 text-lg font-bold text-[hsl(var(--secondary))]">Academics</h4>
                    <div class="space-y-2">
                        <a href="/academics" class="block text-sm text-gray-600 transition-colors duration-200 hover:text-[hsl(var(--secondary))]">
                            Academic Programs
                        </a>
                        <a
                            href="/faculty-of-engineering"
                            class="block text-sm text-gray-600 transition-colors duration-200 hover:text-[hsl(var(--secondary))]"
                        >
                            Faculty of Engineering
                        </a>
                        <a
                            href="/faculty-of-life-science"
                            class="block text-sm text-gray-600 transition-colors duration-200 hover:text-[hsl(var(--secondary))]"
                        >
                            Faculty of Life Science
                        </a>
                        <a
                            href="/faculty-of-science"
                            class="block text-sm text-gray-600 transition-colors duration-200 hover:text-[hsl(var(--secondary))]"
                        >
                            Faculty of Science
                        </a>
                        <a
                            href="/faculty-of-business-studies"
                            class="block text-sm text-gray-600 transition-colors duration-200 hover:text-[hsl(var(--secondary))]"
                        >
                            Faculty of Business Studies
                        </a>
                        <a
                            href="/faculty-of-social-science"
                            class="block text-sm text-gray-600 transition-colors duration-200 hover:text-[hsl(var(--secondary))]"
                        >
                            Faculty of Social Science
                        </a>
                        <a
                            href="/faculty-of-arts"
                            class="block text-sm text-gray-600 transition-colors duration-200 hover:text-[hsl(var(--secondary))]"
                        >
                            Faculty of Arts
                        </a>
                    </div>
                </div>

                <!-- Resources -->
                <div>
                    <h4 class="mb-4 text-lg font-bold text-[hsl(var(--secondary))]">Useful Links</h4>
                    <div class="space-y-2">
                        <a href="/ict-cell" class="block text-sm text-gray-600 transition-colors duration-200 hover:text-[hsl(var(--secondary))]">
                            ICT Cell
                        </a>
                        <a href="/bncc" class="block text-sm text-gray-600 transition-colors duration-200 hover:text-[hsl(var(--secondary))]">
                            BNCC
                        </a>
                        <a
                            href="/central-library"
                            class="block text-sm text-gray-600 transition-colors duration-200 hover:text-[hsl(var(--secondary))]"
                        >
                            Central Library
                        </a>
                        <a
                            href="/residential-halls"
                            class="block text-sm text-gray-600 transition-colors duration-200 hover:text-[hsl(var(--secondary))]"
                        >
                            Residential Halls
                        </a>
                        <a
                            href="/students-welfare-and-counseling"
                            class="block text-sm text-gray-600 transition-colors duration-200 hover:text-[hsl(var(--secondary))]"
                        >
                            Students' Welfare
                        </a>
                        <a
                            href="/registrar-office"
                            class="block text-sm text-gray-600 transition-colors duration-200 hover:text-[hsl(var(--secondary))]"
                        >
                            Registrar Office
                        </a>
                        <a
                            href="/research-cell"
                            class="block text-sm text-gray-600 transition-colors duration-200 hover:text-[hsl(var(--secondary))]"
                        >
                            Research Cell
                        </a>
                    </div>
                </div>
            </div>

            <!-- Social Media & Copyright -->
            <div class="mt-12 border-t border-gray-300 pt-8">
                <div class="flex flex-col items-center justify-between md:flex-row">
                    <!-- Social Media Links -->
                    <div class="mb-6 flex items-center space-x-4 md:mb-0">
                        <span class="text-sm font-medium text-gray-600">Follow Us:</span>
                        <div class="flex space-x-3">
                            <a
                                href="https://facebook.com/mbstu"
                                target="_blank"
                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-[hsl(var(--secondary))] transition-colors duration-200 hover:bg-[hsl(var(--primary))]"
                            >
                                <Facebook class="h-5 w-5 text-white" />
                            </a>
                            <a
                                href="https://twitter.com/mbstu"
                                target="_blank"
                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-500 transition-colors duration-200 hover:bg-sky-600"
                            >
                                <Twitter class="h-5 w-5 text-white" />
                            </a>
                            <a
                                href="https://linkedin.com/company/mbstu"
                                target="_blank"
                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-700 transition-colors duration-200 hover:bg-blue-800"
                            >
                                <Linkedin class="h-5 w-5 text-white" />
                            </a>
                            <a
                                href="https://youtube.com/@mbstu"
                                target="_blank"
                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-600 transition-colors duration-200 hover:bg-red-700"
                            >
                                <Youtube class="h-5 w-5 text-white" />
                            </a>
                            <a
                                href="https://mbstu.ac.bd"
                                target="_blank"
                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-600 transition-colors duration-200 hover:bg-green-700"
                            >
                                <Globe class="h-5 w-5 text-white" />
                            </a>
                        </div>
                    </div>

                    <!-- Copyright & Links -->
                    <div class="text-center md:text-right">
                        <p class="mb-2 text-sm text-gray-600">© 2025 Mawlana Bhashani Science and Technology University</p>
                        <div class="flex items-center justify-center space-x-4 text-xs text-gray-500 md:justify-end">
                            <span class="text-sm">Dhaka Liaison Office:</span>
                        </div>
                        <div class="mt-1 text-xs text-gray-500">
                            <span>Alamin Apon Heights, Flat No. C-12, D-12, 2/1/B Road 03, Shaymoli, Dhaka</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>
