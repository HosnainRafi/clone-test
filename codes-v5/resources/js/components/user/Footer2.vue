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
          <div class="flex items-center mb-6 gap-2">
            <img 
                src="/images/university/logo/MBSTU_logo.png" 
                alt="MBSTU Logo" 
                class="h-10 w-10 object-contain"
              />
            <div>
              <h3 class="text-xl font-bold text-[hsl(var(--secondary))]">MBSTU</h3>
              <p class="text-sm text-gray-600">Mawlana Bhashani Science and Technology University</p>
            </div>
          </div>
          
          <p class="text-gray-600 mb-6 leading-relaxed">
            Mawlana Bhashani Science and Technology University is aimed to provide its students with 
            the knowledge of proper combination of science and technology to get them prepared for 
            the challenges of the 21st century.
          </p>
          
          <!-- Contact Info -->
          <div class="space-y-3">
            <div class="flex items-center text-gray-600">
              <MapPin class="w-4 h-4 mr-3 text-[hsl(var(--secondary))] " />
              <span class="text-sm">Santosh, Tangail-1902, Bangladesh</span>
            </div>
            <div class="flex items-center text-gray-600">
              <Phone class="w-4 h-4 mr-3 text-[hsl(var(--secondary))] " />
              <span class="text-sm">+880-921-55399</span>
            </div>
            <div class="flex items-center text-gray-600">
              <Mail class="w-4 h-4 mr-3 text-[hsl(var(--secondary))] " />
              <span class="text-sm">registrar@mbstu.ac.bd</span>
            </div>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h4 class="font-bold text-lg text-[hsl(var(--secondary))] mb-4">Academics</h4>
          <div class="space-y-2">
            <a href="/academics" class="block text-gray-600 hover:text-[hsl(var(--secondary))]  transition-colors duration-200 text-sm">
              Academic Programs
            </a>
            <a href="/faculty-of-engineering" class="block text-gray-600 hover:text-[hsl(var(--secondary))]  transition-colors duration-200 text-sm">
              Faculty of Engineering
            </a>
            <a href="/faculty-of-life-science" class="block text-gray-600 hover:text-[hsl(var(--secondary))]  transition-colors duration-200 text-sm">
              Faculty of Life Science
            </a>
            <a href="/faculty-of-science" class="block text-gray-600 hover:text-[hsl(var(--secondary))]  transition-colors duration-200 text-sm">
              Faculty of Science
            </a>
            <a href="/faculty-of-business-studies" class="block text-gray-600 hover:text-[hsl(var(--secondary))]  transition-colors duration-200 text-sm">
              Faculty of Business Studies
            </a>
            <a href="/faculty-of-social-science" class="block text-gray-600 hover:text-[hsl(var(--secondary))]  transition-colors duration-200 text-sm">
              Faculty of Social Science
            </a>
            <a href="/faculty-of-arts" class="block text-gray-600 hover:text-[hsl(var(--secondary))]  transition-colors duration-200 text-sm">
              Faculty of Arts
            </a>
          </div>
        </div>

        <!-- Resources -->
        <div>
          <h4 class="font-bold text-lg text-[hsl(var(--secondary))] mb-4">Useful Links</h4>
          <div class="space-y-2">
            <a href="/ict-cell" class="block text-gray-600 hover:text-[hsl(var(--secondary))]  transition-colors duration-200 text-sm">
              ICT Cell
            </a>
            <a href="/bncc" class="block text-gray-600 hover:text-[hsl(var(--secondary))]  transition-colors duration-200 text-sm">
              BNCC
            </a>
            <a href="/central-library" class="block text-gray-600 hover:text-[hsl(var(--secondary))]  transition-colors duration-200 text-sm">
              Central Library
            </a>
            <a href="/residential-halls" class="block text-gray-600 hover:text-[hsl(var(--secondary))]  transition-colors duration-200 text-sm">
              Residential Halls
            </a>
            <a href="/students-welfare-and-counseling" class="block text-gray-600 hover:text-[hsl(var(--secondary))]  transition-colors duration-200 text-sm">
              Students' Welfare
            </a>
            <a href="/registrar-office" class="block text-gray-600 hover:text-[hsl(var(--secondary))]  transition-colors duration-200 text-sm">
              Registrar Office
            </a>
            <a href="/research-cell" class="block text-gray-600 hover:text-[hsl(var(--secondary))]  transition-colors duration-200 text-sm">
              Research Cell
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
              <a href="https://facebook.com/mbstu" target="_blank" class="w-10 h-10 bg-[hsl(var(--secondary))] hover:bg-[hsl(var(--primary))] rounded-lg flex items-center justify-center transition-colors duration-200">
                <Facebook class="w-5 h-5 text-white" />
              </a>
              <a href="https://twitter.com/mbstu" target="_blank" class="w-10 h-10 bg-sky-500 hover:bg-sky-600 rounded-lg flex items-center justify-center transition-colors duration-200">
                <Twitter class="w-5 h-5 text-white" />
              </a>
              <a href="https://linkedin.com/company/mbstu" target="_blank" class="w-10 h-10 bg-blue-700 hover:bg-blue-800 rounded-lg flex items-center justify-center transition-colors duration-200">
                <Linkedin class="w-5 h-5 text-white" />
              </a>
              <a href="https://youtube.com/@mbstu" target="_blank" class="w-10 h-10 bg-red-600 hover:bg-red-700 rounded-lg flex items-center justify-center transition-colors duration-200">
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
              © 2025 Mawlana Bhashani Science and Technology University
            </p>
            <div class="flex items-center justify-center md:justify-end space-x-4 text-xs text-gray-500">
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
