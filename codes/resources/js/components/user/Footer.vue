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

// Define interfaces for the footer data
interface FooterLink {
    text: string;
    href: string;
}

interface SocialLink {
    platform: 'facebook' | 'twitter' | 'linkedin' | 'youtube' | 'website';
    href: string;
}

interface FooterData {
    departmentName: string;
    departmentFullName: string;
    departmentDescription?: string;
    logoUrl: string;
    universityName: string;
    address: string;
    phone: string;
    email: string;
    website?: string;
    quickLinksTitle: string;
    quickLinks: FooterLink[];
    resourcesTitle: string;
    resourcesLinks: FooterLink[];
    academicLinksTitle?: string;
    academicLinks?: FooterLink[];
    socialLinks: SocialLink[];
    copyrightText: string;
    privacyPolicyUrl?: string;
    termsOfServiceUrl?: string;
    sitemapUrl?: string;
    showUniversityLink?: boolean;
    universityWebsite?: string;
}

interface Props {
    footerData?: FooterData;
}

const props = withDefaults(defineProps<Props>(), {
    footerData: () => ({
        departmentName: 'CSE',
        departmentFullName: 'Department of Computer Science & Engineering',
        departmentDescription: 'Leading innovation in computer science education and research, preparing students for the future of technology.',
        logoUrl: '/images/university/logo/MBSTU_logo.png',
        universityName: 'MBSTU',
        address: 'Santosh, Tangail-1902, Bangladesh',
        phone: '+880-921-55399',
        email: 'info.cse@mbstu.ac.bd',
        website: '',
        quickLinksTitle: 'Quick Links',
        quickLinks: [
            { text: 'About Department', href: '/about' },
            { text: 'Faculty Members', href: '/faculty' },
            { text: 'Academic Programs', href: '/programs' },
            { text: 'Research Areas', href: '/research' },
            { text: 'Laboratories', href: '/labs' },
            { text: 'Admissions', href: '/admissions' },
            { text: 'Publications', href: '/publications' }
        ],
        resourcesTitle: 'Resources',
        resourcesLinks: [
            { text: 'Digital Library', href: '/library' },
            { text: 'Course Curriculum', href: '/curriculum' },
            { text: 'Thesis Repository', href: '/thesis' },
            { text: 'Conferences', href: '/conferences' },
            { text: 'Academic Journals', href: '/journals' },
            { text: 'Events & Seminars', href: '/events' },
            { text: 'Career Services', href: '/career' }
        ],
        academicLinksTitle: 'Academic',
        academicLinks: [],
        socialLinks: [
            { platform: 'facebook', href: '#' },
            { platform: 'twitter', href: '#' },
            { platform: 'linkedin', href: '#' },
            { platform: 'youtube', href: '#' },
            { platform: 'website', href: 'https://mbstu.ac.bd' }
        ],
        copyrightText: `© ${new Date().getFullYear()} Department of Computer Science & Engineering, MBSTU`,
        privacyPolicyUrl: '/privacy',
        termsOfServiceUrl: '/terms',
        sitemapUrl: '/sitemap',
        showUniversityLink: true,
        universityWebsite: 'https://mbstu.ac.bd'
    })
});

// Helper function to get social icon component
const getSocialIcon = (platform: string) => {
    switch (platform) {
        case 'facebook': return Facebook;
        case 'twitter': return Twitter;
        case 'linkedin': return Linkedin;
        case 'youtube': return Youtube;
        case 'website': return Globe;
        default: return Globe;
    }
};

// Helper function to get social platform color
const getSocialColor = (platform: string) => {
    switch (platform) {
        case 'facebook': return 'bg-[hsl(var(--secondary))] hover:bg-[hsl(var(--primary))]';
        case 'twitter': return 'bg-sky-500 hover:bg-sky-600';
        case 'linkedin': return 'bg-blue-700 hover:bg-blue-800';
        case 'youtube': return 'bg-red-600 hover:bg-red-700';
        case 'website': return 'bg-green-600 hover:bg-green-700';
        default: return 'bg-gray-600 hover:bg-gray-700';
    }
};
</script>

<template>
  <footer
    id="footer"
    class="mt-24 bg-gradient-to-b from-gray-50 to-gray-100 border-t border-gray-200"
  >
    <div class="container py-16">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        
        <!-- Department Logo & Info -->
        <div class="lg:col-span-2">
          <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-[hsl(var(--primary))] rounded-lg flex items-center justify-center mr-4">
              <img v-if="footerData.logoUrl" :src="footerData.logoUrl" :alt="footerData.departmentName" class="w-8 h-8 object-contain" />
              <GraduationCap v-else class="w-7 h-7 text-white" />
            </div>
            <div>
              <h3 class="text-xl font-bold text-[hsl(var(--secondary))]">{{ footerData.universityName }} {{ footerData.departmentName }}</h3>
              <p class="text-sm text-gray-600">{{ footerData.departmentFullName }}</p>
            </div>
          </div>
          
          <p v-if="footerData.departmentDescription" class="text-gray-600 mb-6 leading-relaxed">
            {{ footerData.departmentDescription }}
          </p>
          
          <!-- Contact Info -->
          <div class="space-y-3">
            <div v-if="footerData.address" class="flex items-center text-gray-600">
              <MapPin class="w-4 h-4 mr-3 text-[hsl(var(--secondary))]" />
              <span class="text-sm">{{ footerData.address }}</span>
            </div>
            <div v-if="footerData.phone" class="flex items-center text-gray-600">
              <Phone class="w-4 h-4 mr-3 text-[hsl(var(--secondary))]" />
              <span class="text-sm">{{ footerData.phone }}</span>
            </div>
            <div v-if="footerData.email" class="flex items-center text-gray-600">
              <Mail class="w-4 h-4 mr-3 text-[hsl(var(--secondary))]" />
              <span class="text-sm">{{ footerData.email }}</span>
            </div>
            <div v-if="footerData.website" class="flex items-center text-gray-600">
              <Globe class="w-4 h-4 mr-3 text-[hsl(var(--secondary))]" />
              <a :href="footerData.website" target="_blank" class="text-sm hover:text-[hsl(var(--secondary))] transition-colors duration-200">
                {{ footerData.website }}
              </a>
            </div>
          </div>
        </div>

        <!-- Quick Links -->
        <div v-if="footerData.quickLinks && footerData.quickLinks.length > 0">
          <h4 class="font-bold text-lg text-[hsl(var(--secondary))] mb-4">{{ footerData.quickLinksTitle }}</h4>
          <div class="space-y-2">
            <a 
              v-for="link in footerData.quickLinks" 
              :key="link.href" 
              :href="link.href" 
              class="block text-gray-600 hover:text-[hsl(var(--secondary))] transition-colors duration-200 text-sm"
            >
              {{ link.text }}
            </a>
          </div>
        </div>

        <!-- Resources -->
        <div v-if="footerData.resourcesLinks && footerData.resourcesLinks.length > 0">
          <h4 class="font-bold text-lg text-[hsl(var(--secondary))] mb-4">{{ footerData.resourcesTitle }}</h4>
          <div class="space-y-2">
            <a 
              v-for="link in footerData.resourcesLinks" 
              :key="link.href" 
              :href="link.href" 
              class="block text-gray-600 hover:text-[hsl(var(--secondary))] transition-colors duration-200 text-sm"
            >
              {{ link.text }}
            </a>
          </div>
        </div>

        <!-- Additional Academic Links (if exists and different from resources) -->
        <div v-if="footerData.academicLinks && footerData.academicLinks.length > 0 && footerData.academicLinksTitle" class="lg:col-span-1">
          <h4 class="font-bold text-lg text-[hsl(var(--secondary))] mb-4">{{ footerData.academicLinksTitle }}</h4>
          <div class="space-y-2">
            <a 
              v-for="link in footerData.academicLinks" 
              :key="link.href" 
              :href="link.href" 
              class="block text-gray-600 hover:text-[hsl(var(--secondary))] transition-colors duration-200 text-sm"
            >
              {{ link.text }}
            </a>
          </div>
        </div>
      </div>

      <!-- Social Media & Copyright -->
      <div class="mt-12 pt-8 border-t border-gray-300">
        <div class="flex flex-col md:flex-row justify-between items-center">
          
          <!-- Social Media Links -->
          <div v-if="footerData.socialLinks && footerData.socialLinks.length > 0" class="flex items-center space-x-4 mb-6 md:mb-0">
            <span class="text-gray-600 text-sm font-medium">Follow Us:</span>
            <div class="flex space-x-3">
              <a 
                v-for="social in footerData.socialLinks" 
                :key="social.platform"
                :href="social.href" 
                :target="social.platform === 'website' ? '_blank' : '_self'"
                :class="getSocialColor(social.platform)"
                class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors duration-200"
              >
                <component :is="getSocialIcon(social.platform)" class="w-5 h-5 text-white" />
              </a>
            </div>
          </div>

          <!-- Copyright & Links -->
          <div class="text-center md:text-right">
            <p class="text-gray-600 text-sm mb-2">
              {{ footerData.copyrightText }}
            </p>
            <div class="flex items-center justify-center md:justify-end space-x-4 text-xs text-gray-500">
              <a 
                v-if="footerData.privacyPolicyUrl" 
                :href="footerData.privacyPolicyUrl" 
                class="hover:text-[hsl(var(--secondary))] transition-colors duration-200"
              >
                Privacy Policy
              </a>
              <span v-if="footerData.privacyPolicyUrl && footerData.termsOfServiceUrl">•</span>
              <a 
                v-if="footerData.termsOfServiceUrl" 
                :href="footerData.termsOfServiceUrl" 
                class="hover:text-[hsl(var(--secondary))] transition-colors duration-200"
              >
                Terms of Service
              </a>
              <span v-if="(footerData.privacyPolicyUrl || footerData.termsOfServiceUrl) && footerData.sitemapUrl">•</span>
              <a 
                v-if="footerData.sitemapUrl" 
                :href="footerData.sitemapUrl" 
                class="hover:text-[hsl(var(--secondary))] transition-colors duration-200"
              >
                Sitemap
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>
