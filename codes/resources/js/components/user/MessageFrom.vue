<script setup lang="ts">
import { ref } from 'vue'
import { Quote, User, Mail, Phone, MapPin, ExternalLink, ChevronRight } from 'lucide-vue-next'

// Chairman's information and message
const chairmanData = {
  name: 'Prof. Dr. Mohammad Rahman Ahmed',
  title: 'Chairman & Professor',
  department: 'Department of Computer Science and Engineering',
  university: 'Mawlana Bhashani Science and Technology University',
  photo: '/images/faculty/chairman.jpg',
  fallbackGradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)',
  email: 'chairman.cse@mbstu.ac.bd',
  phone: '+880-1234-567890',
  office: 'Room 301, CSE Building',
  qualifications: [
    'Ph.D. in Computer Science, University of Dhaka',
    'M.Sc. in Software Engineering, BUET',
    'B.Sc. in Computer Science and Engineering, BUET'
  ],
  specializations: [
    'Artificial Intelligence',
    'Machine Learning',
    'Software Engineering',
    'Database Systems'
  ],
  experience: '15+ years',
  message: {
    greeting: 'Welcome to the Department of Computer Science and Engineering',
    content: `It gives me immense pleasure to welcome you to the Department of Computer Science and Engineering at Mawlana Bhashani Science and Technology University. Our department has been at the forefront of technological innovation and academic excellence since its establishment.

We are committed to providing world-class education that combines theoretical knowledge with practical skills, preparing our students to become leaders in the rapidly evolving field of computer science and engineering. Our dedicated faculty members, state-of-the-art laboratories, and comprehensive curriculum ensure that our graduates are well-equipped to meet the challenges of the modern technology industry.

The department offers both undergraduate and graduate programs designed to foster critical thinking, creativity, and innovation. We emphasize research-oriented learning and encourage our students to participate in cutting-edge research projects that address real-world problems.

Our strong industry partnerships provide excellent opportunities for internships, collaborative projects, and career placements. We take pride in our alumni who are making significant contributions in leading technology companies, research institutions, and entrepreneurial ventures across the globe.

I invite you to explore our programs, research opportunities, and the vibrant academic community that makes our department a center of excellence in computer science education.`,
    closing: 'Together, let us shape the future of technology and innovation.',
    signature: 'Prof. Dr. Mohammad Rahman Ahmed'
  },
  achievements: [
    'Published 50+ research papers in international journals',
    'Received Best Teacher Award 2023',
    'Led 15+ funded research projects',
    'Supervised 25+ Ph.D. and M.Sc. students'
  ]
}

// Expandable message state
const isExpanded = ref(false)

const toggleMessage = () => {
  isExpanded.value = !isExpanded.value
}
</script>

<template>
  <section class="container pt-24">
    <!-- Section Header -->
    <div class="text-center mb-12">
    <h2 class="text-3xl md:text-4xl font-bold text-[hsl(var(--secondary))] mb-4">
        Message from Chairman
    </h2>
    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
        A warm welcome from the leadership of our Computer Science department.
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
                backgroundImage: `url(${chairmanData.photo})`,
                }"
            >
                <!-- Fallback icon when photo is not available -->
                <User class="w-16 h-16 text-gray-500" />
            </div>
            </div>
        </div>

        <!-- Chairman Details -->
        <div class="text-center">
            <h3 class="text-xl font-bold mb-2 text-[hsl(var(--secondary))]">{{ chairmanData.name }}</h3>
            <p class="text-gray-600 text-lg font-medium mb-1">{{ chairmanData.title }}</p>
            <p class="text-gray-500 text-sm mb-6">{{ chairmanData.department }}</p>
            
            <!-- Contact Information -->
            <div class="space-y-2 text-sm text-gray-600">
            <div class="flex items-center justify-center">
                <Mail class="w-4 h-4 mr-2 text-gray-400" />
                <span>{{ chairmanData.email }}</span>
            </div>
            <div class="flex items-center justify-center">
                <Phone class="w-4 h-4 mr-2 text-gray-400" />
                <span>{{ chairmanData.phone }}</span>
            </div>
            <div class="flex items-center justify-center">
                <MapPin class="w-4 h-4 mr-2 text-gray-400" />
                <span>{{ chairmanData.office }}</span>
            </div>
            </div>

            <!-- Experience Badge -->
            <div class="mt-6 inline-block bg-[hsl(var(--tertiary))] text-[hsl(var(--primary))] px-4 py-2 rounded-full text-sm font-medium">
            {{ chairmanData.experience }} Experience
            </div>
        </div>
        </div>

        <!-- Right Side - Message Content -->
        <div class="lg:w-2/3 p-8 lg:p-10 bg-white">
        <!-- Greeting -->
        <div class="mb-6">
            <h4 class="text-2xl font-bold text-[hsl(var(--secondary))] mb-4">
            {{ chairmanData.message.greeting }}
            </h4>
        </div>

        <!-- Message Content -->
        <div class="prose prose-lg max-w-none">
            <div class="text-gray-600 leading-relaxed space-y-4">
            <p class="text-lg">
                {{ chairmanData.message.content.split('\n\n')[0] }}
            </p>
            
            <!-- Expandable content -->
            <div 
                v-if="isExpanded"
                class="space-y-4 transition-all duration-500 ease-in-out"
            >
                <p v-for="(paragraph, index) in chairmanData.message.content.split('\n\n').slice(1)" 
                    :key="index"
                    class="text-gray-600"
                >
                {{ paragraph }}
                </p>
            </div>
            
            <!-- Read More/Less Button -->
            <button
                @click="toggleMessage"
                class="inline-flex items-center text-[hsl(var(--secondary))] hover:text-[hsl(var(--primary))] font-medium transition-colors duration-200 text-sm"
            >
                {{ isExpanded ? 'Read Less' : 'Read More' }}
                <ChevronRight 
                :class="[
                    'w-4 h-4 ml-1 transition-transform duration-200',
                    isExpanded ? 'rotate-90' : ''
                ]" 
                />
            </button>
            </div>
        </div>

        <!-- Closing Message -->
        <div v-if="isExpanded" class="mt-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-300">
            <p class="text-gray-700 font-medium italic mb-3">
            "{{ chairmanData.message.closing }}"
            </p>
            <p class="text-gray-600 font-semibold text-sm">
            — {{ chairmanData.message.signature }}
            </p>
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

    <!-- Additional Information Cards -->
    <div v-if="isExpanded" class="grid md:grid-cols-2 gap-6 mt-8">
    
    <!-- Qualifications -->
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
        <h4 class="text-lg font-bold text-[hsl(var(--secondary))] mb-4 flex items-center">
        <div class="w-8 h-8 bg-blue-50 text-[hsl(var(--secondary))]  rounded-lg flex items-center justify-center mr-3 text-sm">
            🎓
        </div>
        Academic Qualifications
        </h4>
        <ul class="space-y-2">
        <li v-for="qualification in chairmanData.qualifications" :key="qualification" 
            class="text-gray-600 flex items-start text-sm">
            <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
            {{ qualification }}
        </li>
        </ul>
    </div>

    <!-- Specializations -->
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
        <h4 class="text-lg font-bold text-[hsl(var(--secondary))] mb-4 flex items-center">
        <div class="w-8 h-8 bg-green-50 text-green-600 rounded-lg flex items-center justify-center mr-3 text-sm">
            🔬
        </div>
        Research Specializations
        </h4>
        <div class="flex flex-wrap gap-2 mb-6">
        <span v-for="specialization in chairmanData.specializations" :key="specialization"
                class="px-3 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full border border-green-200">
            {{ specialization }}
        </span>
        </div>
        
        <!-- Achievements -->
        <div>
        <h5 class="font-semibold text-[hsl(var(--secondary))] mb-3 text-sm">Key Achievements:</h5>
        <ul class="space-y-1 text-xs text-gray-600">
            <li v-for="achievement in chairmanData.achievements" :key="achievement"
                class="flex items-start">
            <span class="text-green-500 mr-2">✓</span>
            {{ achievement }}
            </li>
        </ul>
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