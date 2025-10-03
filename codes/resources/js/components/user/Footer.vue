<script setup lang="ts">
import { 
  Mail, 
  Phone, 
  MapPin, 
  Facebook, 
  Twitter, 
  Linkedin, 
  Youtube,
  Globe,
  GraduationCap,
  BookOpen,
  Users,
  Award,
  ExternalLink
} from "lucide-vue-next";
import Separator from "@/components/user/ui/separator/Separator.vue";
</script>

<template>
  <footer
    id="footer"
    class="mt-24 bg-gradient-to-b from-gray-50 to-gray-100 border-t border-gray-200"
  >
    <div class="container py-16">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        
        <!-- University Logo & Info -->
        <div class="lg:col-span-2">
          <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center mr-4">
              <GraduationCap class="w-7 h-7 text-white" />
            </div>
            <div>
              <h3 class="text-xl font-bold text-gray-800">MBSTU CSE</h3>
              <p class="text-sm text-gray-600">Department of Computer Science & Engineering</p>
            </div>
          </div>
          
          <p class="text-gray-600 mb-6 leading-relaxed">
            Mawlana Bhashani Science and Technology University - Leading innovation in computer science 
            education and research, preparing students for the future of technology.
          </p>
          
          <!-- Contact Info -->
          <div class="space-y-3">
            <div class="flex items-center text-gray-600">
              <MapPin class="w-4 h-4 mr-3 text-blue-600" />
              <span class="text-sm">Santosh, Tangail-1902, Bangladesh</span>
            </div>
            <div class="flex items-center text-gray-600">
              <Phone class="w-4 h-4 mr-3 text-blue-600" />
              <span class="text-sm">+880-921-55399</span>
            </div>
            <div class="flex items-center text-gray-600">
              <Mail class="w-4 h-4 mr-3 text-blue-600" />
              <span class="text-sm">info.cse@mbstu.ac.bd</span>
            </div>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h4 class="font-bold text-lg text-gray-800 mb-4">Quick Links</h4>
          <div class="space-y-2">
            <a href="/about" class="block text-gray-600 hover:text-blue-600 transition-colors duration-200 text-sm">
              About Department
            </a>
            <a href="/faculty" class="block text-gray-600 hover:text-blue-600 transition-colors duration-200 text-sm">
              Faculty Members
            </a>
            <a href="/programs" class="block text-gray-600 hover:text-blue-600 transition-colors duration-200 text-sm">
              Academic Programs
            </a>
            <a href="/research" class="block text-gray-600 hover:text-blue-600 transition-colors duration-200 text-sm">
              Research Areas
            </a>
            <a href="/labs" class="block text-gray-600 hover:text-blue-600 transition-colors duration-200 text-sm">
              Laboratories
            </a>
            <a href="/admissions" class="block text-gray-600 hover:text-blue-600 transition-colors duration-200 text-sm">
              Admissions
            </a>
            <a href="/publications" class="block text-gray-600 hover:text-blue-600 transition-colors duration-200 text-sm">
              Publications
            </a>
          </div>
        </div>

        <!-- Resources -->
        <div>
          <h4 class="font-bold text-lg text-gray-800 mb-4">Resources</h4>
          <div class="space-y-2">
            <a href="/library" class="block text-gray-600 hover:text-blue-600 transition-colors duration-200 text-sm">
              Digital Library
            </a>
            <a href="/curriculum" class="block text-gray-600 hover:text-blue-600 transition-colors duration-200 text-sm">
              Course Curriculum
            </a>
            <a href="/thesis" class="block text-gray-600 hover:text-blue-600 transition-colors duration-200 text-sm">
              Thesis Repository
            </a>
            <a href="/conferences" class="block text-gray-600 hover:text-blue-600 transition-colors duration-200 text-sm">
              Conferences
            </a>
            <a href="/journals" class="block text-gray-600 hover:text-blue-600 transition-colors duration-200 text-sm">
              Academic Journals
            </a>
            <a href="/events" class="block text-gray-600 hover:text-blue-600 transition-colors duration-200 text-sm">
              Events & Seminars
            </a>
            <a href="/career" class="block text-gray-600 hover:text-blue-600 transition-colors duration-200 text-sm">
              Career Services
            </a>
          </div>
        </div>
      </div>

      <!-- Social Media & Copyright -->
      <div class="mt-12 pt-8 border-t border-gray-300">
        <div class="flex flex-col md:flex-row justify-between items-center">
          
          <!-- Social Media Links -->
          <div class="flex items-center space-x-4 mb-6 md:mb-0">
            <span class="text-gray-600 text-sm font-medium">Follow Us:</span>
            <div class="flex space-x-3">
              <a href="#" class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center justify-center transition-colors duration-200">
                <Facebook class="w-5 h-5 text-white" />
              </a>
              <a href="#" class="w-10 h-10 bg-sky-500 hover:bg-sky-600 rounded-lg flex items-center justify-center transition-colors duration-200">
                <Twitter class="w-5 h-5 text-white" />
              </a>
              <a href="#" class="w-10 h-10 bg-blue-700 hover:bg-blue-800 rounded-lg flex items-center justify-center transition-colors duration-200">
                <Linkedin class="w-5 h-5 text-white" />
              </a>
              <a href="#" class="w-10 h-10 bg-red-600 hover:bg-red-700 rounded-lg flex items-center justify-center transition-colors duration-200">
                <Youtube class="w-5 h-5 text-white" />
              </a>
              <a href="https://mbstu.ac.bd" target="_blank" class="w-10 h-10 bg-green-600 hover:bg-green-700 rounded-lg flex items-center justify-center transition-colors duration-200">
                <Globe class="w-5 h-5 text-white" />
              </a>
            </div>
          </div>

          <!-- Copyright & Links -->
          <div class="text-center md:text-right">
            <p class="text-gray-600 text-sm mb-2">
              © 2024 Department of Computer Science & Engineering, MBSTU
            </p>
            <div class="flex items-center justify-center md:justify-end space-x-4 text-xs text-gray-500">
              <a href="/privacy" class="hover:text-blue-600 transition-colors duration-200">Privacy Policy</a>
              <span>•</span>
              <a href="/terms" class="hover:text-blue-600 transition-colors duration-200">Terms of Service</a>
              <span>•</span>
              <a href="/sitemap" class="hover:text-blue-600 transition-colors duration-200">Sitemap</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>
