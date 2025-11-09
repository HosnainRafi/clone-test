<script setup lang="ts">
import { computed } from 'vue'
import { User, Mail, Phone, MapPin, ExternalLink, ChevronRight } from 'lucide-vue-next'

// Props interface
interface MessageFromData {
  name?: string
  title?: string
  department?: string
  university?: string
  photo?: string
  email?: string
  phone?: string
  fax?: string
  office?: string
  experience?: string
  greeting?: string
  subtitle?: string
  messageTitle?: string
  content?: string
}

// Props
const props = defineProps<{
  messageData?: MessageFromData
  componentData?: any
}>()

// Default message data
const defaultMessageFrom = {
  name: 'Professor Dr. Md. Anwarul Azim Akhand',
  title: 'Vice-Chancellor',
  department: 'Mawlana Bhashani Science and Technology University',
  university: 'MBSTU',
  photo: '/images/faculty/vc.jpg',
  fallbackGradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)',
  email: 'vc@mbstu.ac.bd',
  phone: '+880921 55399',
  fax: '+880921 55400',
  office: 'Vice Chancellor Office, MBSTU',
  experience: '25+ years',
  greeting: 'Message from Chairman',
  subtitle: 'A warm welcome from the leadership of our Computer Science department.',
  messageTitle: 'Welcome to the Department of Computer Science and Engineering',
  content: `It gives me immense pleasure to welcome you to the Department of Computer Science and Engineering at Mawlana Bhashani Science and Technology University. Our department has been at the forefront of technological innovation and academic excellence since its establishment.`
}

// Computed property to merge props with default data
const messageFrom = computed(() => {
  // Check for component data from PageEditor preview
  if (props.componentData?.messageData) {
    return {
      ...defaultMessageFrom,
      ...props.componentData.messageData
    }
  }
  // Return default data
  return defaultMessageFrom
})
</script>

<template>
  <section class="container pt-24">
    <!-- Section Header -->
    <div class="text-center mb-12">
    <h2 class="text-3xl md:text-4xl font-bold text-[hsl(var(--secondary))] mb-4">
        {{ messageFrom.greeting }}
    </h2>
    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
        {{ messageFrom.subtitle }}
    </p>
    </div>

    <!-- Main Message Card -->
    <div class="bg-gray-50 rounded-2xl overflow-hidden border border-gray-200 shadow-sm">
    <div class="lg:flex">
        
        <!-- Left Side - Chairman Photo and Info -->
        <div class="lg:w-1/3 bg-white p-8 border-r border-gray-200">
        
        <!-- Chairman Photo -->
        <div class="text-center mb-6">
            <div class="w-32 h-32 mx-auto rounded-full overflow-hidden border-4 border-gray-200 shadow-md bg-gradient-to-br from-gray-100 to-gray-300">
            <!-- Fallback gradient background -->
            <div 
                class="w-full h-full bg-cover bg-center bg-no-repeat flex items-center justify-center"
                :style="{ 
                backgroundImage: `url(${messageFrom.photo})`,
                }"
            >
                <!-- Fallback icon when photo is not available -->
                <User class="w-16 h-16 text-gray-500" />
            </div>
            </div>
        </div>

        <!-- Chairman Details -->
        <div class="text-center">
            <h3 class="text-xl font-bold mb-2 text-[hsl(var(--secondary))]">{{ messageFrom.name }}</h3>
            <p class="text-gray-600 text-lg font-medium mb-1">{{ messageFrom.title }}</p>
            <p class="text-gray-500 text-sm mb-6">{{ messageFrom.department }}</p>
            
            <!-- Contact Information -->
            <div class="space-y-2 text-sm text-gray-600">
            <div class="flex items-center justify-center">
                <Mail class="w-4 h-4 mr-2 text-gray-400" />
                <span>{{ messageFrom.email }}</span>
            </div>
            <div class="flex items-center justify-center">
                <Phone class="w-4 h-4 mr-2 text-gray-400" />
                <span>{{ messageFrom.phone }}</span>
            </div>
            <div class="flex items-center justify-center">
                <MapPin class="w-4 h-4 mr-2 text-gray-400" />
                <span>{{ messageFrom.office }}</span>
            </div>
            </div>

            <!-- Experience Badge -->
            <div class="mt-6 inline-block bg-[hsl(var(--tertiary))] text-[hsl(var(--primary))] px-4 py-2 rounded-full text-sm font-medium">
            {{ messageFrom.experience }} Experience
            </div>
        </div>
        </div>

        <!-- Right Side - Message Content -->
        <div class="lg:w-2/3 p-8 lg:p-10 bg-white">
        <!-- Message Title -->
        <div class="mb-6">
            <h4 class="text-2xl font-bold text-[hsl(var(--secondary))] mb-4">
            {{ messageFrom.messageTitle }}
            </h4>
        </div>

        <!-- Message Content -->
        <div class="prose prose-lg max-w-none">
            <div class="text-gray-600 leading-relaxed space-y-4">
            <p class="text-lg">
                {{ messageFrom.content }}
            </p>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="mt-8 flex flex-col sm:flex-row gap-3">
            <a
            href="/about/chairman"
            class="inline-flex items-center justify-center px-5 py-2.5 bg-[hsl(var(--secondary))] hover:bg-[hsl(var(--primary))] text-white font-medium rounded-lg transition-colors duration-200"
            >
            Learn More
            <ExternalLink class="w-4 h-4 ml-2" />
            </a>
            <a
            href="/about/message-chairman"
            class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-lg transition-colors duration-200"
            >
            Full Message
            <ChevronRight class="w-4 h-4 ml-2" />
            </a>
        </div>
        </div>
    </div>
    </div>
  </section>
</template>

<style scoped>
/* Smooth transitions for expandable content */
.transition-all {
  transition: all 0.5s ease-in-out;
}

.prose p {
  margin-bottom: 1rem;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
  .lg\:flex {
    flex-direction: column;
  }
  
  .lg\:w-1\/3,
  .lg\:w-2\/3 {
    width: 100%;
  }
}
</style>