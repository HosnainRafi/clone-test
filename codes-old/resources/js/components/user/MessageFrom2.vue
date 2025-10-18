<script setup lang="ts">
import { ref } from 'vue'
import { Quote, User, Mail, Phone, MapPin, ExternalLink, ChevronRight } from 'lucide-vue-next'

// Vice Chancellor's information and message
const vicechancellorData = {
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
  address: 'Santosh, Tangail - 1902',
  officeTime: 'Saturday - Wednesday: 9.00AM - 5.00PM',
  qualifications: [
    'Ph.D. in Computer Science and Engineering',
    'M.Sc. in Computer Science and Engineering',
    'B.Sc. in Computer Science and Engineering'
  ],
  specializations: [
    'Artificial Intelligence',
    'Machine Learning',
    'Computer Vision',
    'Pattern Recognition',
    'Evolutionary Computing'
  ],
  experience: '25+ years',
  message: {
    greeting: 'Message from the Vice-Chancellor',
    content: `Welcome to Mawlana Bhashani Science and Technology University (MBSTU), a leading center for academic excellence and research in Bangladesh. MBSTU is named after the great visionary and advocate for the underprivileged, Mawlana Abdul Hamid Khan Bhashani. His legacy of dedication to justice, education, and empowerment continues to inspire our mission to create future leaders through scientific innovation, academic rigor, and social commitment.

At MBSTU, we believe that education is not just a means to personal advancement, but a tool for transforming society. Our dynamic curriculum, state-of-the-art facilities, and dedicated faculty aim to nurture creativity, critical thinking, and problem-solving skills among our students. We strive to ensure that our graduates are not only equipped with technical knowledge but also imbued with a sense of responsibility towards the community and the nation.

Our focus on research and innovation encourages students to explore new horizons in science and technology, driving progress in key areas such as computer science, information technology, biotechnology, criminology, engineering, environmental sciences, and many more. Through our diverse programs, we aim to foster a spirit of inquiry leading to solutions for the challenges of tomorrow.

As we grow and evolve, we remain committed to our core values of inclusivity, integrity, and academic excellence. We are proud of our vibrant campus community, where students from different backgrounds come together to share ideas and experiences in an environment of mutual respect and collaboration.

I invite you to explore our website to learn more about the opportunities and achievements that make MBSTU a place where dreams are nurtured and futures are shaped. Whether you are a prospective student, a parent, or a visitor, I welcome you to be a part of our journey as we continue to make strides in science, technology, and societal development.`,
    closing: 'Together, let us build a brighter future through science and technology.',
    signature: 'Professor Dr. Md. Anwarul Azim Akhand'
  },
  achievements: [
    'Leading MBSTU as the 12th oldest public university in Bangladesh',
    'Pioneer in science and technology education',
    'Advocate for research and innovation',
    'Champion of inclusive and quality education'
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
        Message from Vice-Chancellor
    </h2>
    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
        A warm welcome from the leadership of Mawlana Bhashani Science and Technology University.
    </p>
    </div>

    <!-- Main Message Card -->
    <div class="bg-gray-50 rounded-2xl overflow-hidden border border-gray-200 shadow-sm">
    <div class="lg:flex">
        
        <!-- Left Side - Vice Chancellor Photo and Info -->
        <div class="lg:w-1/3 bg-white p-8 border-r border-gray-200">
        
        <!-- Vice Chancellor Photo -->
        <div class="text-center mb-6">
            <div class="w-32 h-32 mx-auto rounded-full overflow-hidden border-4 border-gray-200 shadow-md bg-gradient-to-br from-gray-100 to-gray-300">
            <!-- Fallback gradient background -->
            <div 
                class="w-full h-full bg-cover bg-center bg-no-repeat flex items-center justify-center"
                :style="{ 
                backgroundImage: `url(${vicechancellorData.photo})`,
                }"
            >
                <!-- Fallback icon when photo is not available -->
                <User class="w-16 h-16 text-gray-500" />
            </div>
            </div>
        </div>

        <!-- Vice Chancellor Details -->
        <div class="text-center">
            <h3 class="text-xl font-bold mb-2 text-[hsl(var(--secondary))]">{{ vicechancellorData.name }}</h3>
            <p class="text-gray-600 text-lg font-medium mb-1">{{ vicechancellorData.title }}</p>
            <p class="text-gray-500 text-sm mb-6">{{ vicechancellorData.department }}</p>
            
            <!-- Contact Information -->
            <div class="space-y-2 text-sm text-gray-600">
            <div class="flex items-center justify-center">
                <Mail class="w-4 h-4 mr-2 text-gray-400" />
                <span>{{ vicechancellorData.email }}</span>
            </div>
            <div class="flex items-center justify-center">
                <Phone class="w-4 h-4 mr-2 text-gray-400" />
                <span>{{ vicechancellorData.phone }}</span>
            </div>
            <div class="flex items-center justify-center">
                <MapPin class="w-4 h-4 mr-2 text-gray-400" />
                <span>{{ vicechancellorData.office }}</span>
            </div>
            </div>

            <!-- Experience Badge -->
            <div class="mt-6 inline-block bg-[hsl(var(--tertiary))] text-[hsl(var(--primary))] px-4 py-2 rounded-full text-sm font-medium">
            {{ vicechancellorData.experience }} Experience
            </div>
        </div>
        </div>

        <!-- Right Side - Message Content -->
        <div class="lg:w-2/3 p-8 lg:p-10 bg-white">
        <!-- Greeting -->
        <div class="mb-6">
            <h4 class="text-2xl font-bold text-[hsl(var(--secondary))] mb-4">
            {{ vicechancellorData.message.greeting }}
            </h4>
        </div>

        <!-- Message Content -->
        <div class="prose prose-lg max-w-none">
            <div class="text-gray-600 leading-relaxed space-y-4">
            <p class="text-lg">
                {{ vicechancellorData.message.content.split('\n\n')[0] }}
            </p>
            
            <!-- Expandable content -->
            <div 
                v-if="isExpanded"
                class="space-y-4 transition-all duration-500 ease-in-out"
            >
                <p v-for="(paragraph, index) in vicechancellorData.message.content.split('\n\n').slice(1)" 
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
            "{{ vicechancellorData.message.closing }}"
            </p>
            <p class="text-gray-600 font-semibold text-sm">
            — {{ vicechancellorData.message.signature }}
            </p>
        </div>

        <!-- Call to Action -->
        <div class="mt-8 flex flex-col sm:flex-row gap-3">
            <a
            href="/vice-chancellor"
            class="inline-flex items-center justify-center px-5 py-2.5 bg-[hsl(var(--secondary))] hover:bg-[hsl(var(--primary))] text-white font-medium rounded-lg transition-colors duration-200"
            >
            Learn More
            <ExternalLink class="w-4 h-4 ml-2" />
            </a>
            <a
            href="/about/message-vice-chancellor"
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
        <li v-for="qualification in vicechancellorData.qualifications" :key="qualification" 
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
        <span v-for="specialization in vicechancellorData.specializations" :key="specialization"
                class="px-3 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full border border-green-200">
            {{ specialization }}
        </span>
        </div>
        
        <!-- Achievements -->
        <div>
        <h5 class="font-semibold text-[hsl(var(--secondary))] mb-3 text-sm">Key Achievements:</h5>
        <ul class="space-y-1 text-xs text-gray-600">
            <li v-for="achievement in vicechancellorData.achievements" :key="achievement"
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