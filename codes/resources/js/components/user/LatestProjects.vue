<template>
  <section v-if="props.latestProjectsData.isVisible" class="pt-24 container">
    <!-- Section Header -->
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold text-[hsl(var(--secondary))] mb-4">
        {{ props.latestProjectsData.sectionTitle }}
      </h2>
      <p class="text-lg text-gray-600 max-w-3xl mx-auto">
        {{ props.latestProjectsData.sectionSubtitle }}
      </p>
    </div>

    <!-- Projects Carousel -->
    <Carousel
      v-if="props.latestProjectsData.showCarousel"
      :opts="{
        align: 'start',
        loop: true,
      }"
      class="relative w-full max-w-7xl mx-auto"
    >
      <CarouselContent class="-ml-2 md:-ml-4">
        <CarouselItem
          v-for="project in displayProjects"
          :key="project.id"
          class="pl-2 md:pl-4 md:basis-1/2 lg:basis-1/3"
        >
          <Card class="group h-full bg-white hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border-2 border-gray-100 hover:border-blue-200">
            <!-- Project Image/Preview -->
            <div class="relative h-48 overflow-hidden rounded-t-lg">
              <div
                class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-transform duration-500 group-hover:scale-110"
                :style="{ 
                  backgroundImage: `url(${project.image}), ${project.fallbackGradient}`,
                  background: project.fallbackGradient
                }"
              >
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-all duration-300"></div>
              </div>
              
              <!-- Category Badge -->
              <div class="absolute top-4 left-4">
                <Badge class="bg-white/90 text-[hsl(var(--secondary))] hover:bg-white">
                  {{ getCategoryIcon(project.category) }} {{ project.category }}
                </Badge>
              </div>
              
              <!-- Status Badge -->
              <div class="absolute top-4 right-4">
                <Badge :class="getStatusColor(project.status)">
                  {{ project.status }}
                </Badge>
              </div>

              <!-- Project Year -->
              <div class="absolute bottom-4 right-4">
                <div class="bg-[hsl(var(--tertiary))] text-white px-3 py-1 rounded-full text-sm font-semibold">
                  {{ project.year }}
                </div>
              </div>
            </div>

            <CardHeader class="pb-3">
              <div class="flex items-start justify-between mb-2">
                <CardTitle class="text-xl font-bold text-[hsl(var(--secondary))] line-clamp-2 group-hover:text-[hsl(var(--primary))] transition-colors">
                  {{ project.title }}
                </CardTitle>
              </div>
              
              <CardDescription class="text-gray-600 line-clamp-3 leading-relaxed">
                {{ project.description }}
              </CardDescription>
            </CardHeader>

            <CardContent class="pt-0">
              <!-- Project Details -->
              <div class="space-y-3 mb-4">
                <div class="flex items-center text-sm text-gray-600">
                  <Calendar class="w-4 h-4 mr-2 text-[hsl(var(--secondary))]" />
                  <span>Duration: {{ project.duration }}</span>
                </div>
                
                <div class="flex items-center text-sm text-gray-600">
                  <Users class="w-4 h-4 mr-2 text-green-600" />
                  <span>Team: {{ project.team.length }} members</span>
                </div>

                <div class="flex items-start text-sm text-gray-600">
                  <Code2 class="w-4 h-4 mr-2 mt-0.5 text-purple-600 flex-shrink-0" />
                  <div class="flex flex-wrap gap-1">
                    <Badge 
                      v-for="tech in project.technologies.slice(0, 3)" 
                      :key="tech"
                      variant="outline" 
                      class="text-xs"
                    >
                      {{ tech }}
                    </Badge>
                    <Badge 
                      v-if="project.technologies.length > 3"
                      variant="outline" 
                      class="text-xs"
                    >
                      +{{ project.technologies.length - 3 }}
                    </Badge>
                  </div>
                </div>
              </div>

              <!-- Achievements -->
              <div v-if="project.achievements && project.achievements.length > 0" class="mb-4">
                <div class="flex items-center text-sm font-medium text-gray-700 mb-2">
                  <Award class="w-4 h-4 mr-2 text-yellow-600" />
                  Achievements
                </div>
                <div class="space-y-1">
                  <Badge 
                    v-for="achievement in project.achievements" 
                    :key="achievement"
                    class="bg-yellow-100 text-yellow-800 hover:bg-yellow-200 text-xs mr-1 mb-1"
                  >
                    🏆 {{ achievement }}
                  </Badge>
                </div>
              </div>

              <!-- Supervisor -->
              <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                <div class="text-xs text-gray-500 mb-1">Supervised by</div>
                <div class="font-medium text-[hsl(var(--secondary))]">{{ project.supervisor }}</div>
              </div>
            </CardContent>
          </Card>
        </CarouselItem>
      </CarouselContent>
      
      <CarouselPrevious class="hidden sm:flex -left-12 lg:-left-16" />
      <CarouselNext class="hidden sm:flex -right-12 lg:-right-16" />
    </Carousel>

    <!-- Projects Grid (when not using carousel) -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <Card v-for="project in displayProjects" :key="project.id" class="group h-full bg-white hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border-2 border-gray-100 hover:border-blue-200">
        <!-- Project Image/Preview -->
        <div class="relative h-48 overflow-hidden rounded-t-lg">
          <div
            class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-transform duration-500 group-hover:scale-110"
            :style="{ 
              backgroundImage: `url(${project.image}), ${project.fallbackGradient}`,
              background: project.fallbackGradient
            }"
          >
            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-all duration-300"></div>
          </div>
          
          <!-- Category Badge -->
          <div class="absolute top-4 left-4">
            <Badge class="bg-white/90 text-[hsl(var(--secondary))] hover:bg-white">
              {{ getCategoryIcon(project.category) }} {{ project.category }}
            </Badge>
          </div>
          
          <!-- Status Badge -->
          <div class="absolute top-4 right-4">
            <Badge :class="getStatusColor(project.status)">
              {{ project.status }}
            </Badge>
          </div>

          <!-- Project Year -->
          <div class="absolute bottom-4 right-4">
            <div class="bg-[hsl(var(--tertiary))] text-white px-3 py-1 rounded-full text-sm font-semibold">
              {{ project.year }}
            </div>
          </div>
        </div>

        <CardHeader class="pb-3">
          <div class="flex items-start justify-between mb-2">
            <CardTitle class="text-xl font-bold text-[hsl(var(--secondary))] line-clamp-2 group-hover:text-[hsl(var(--primary))] transition-colors">
              {{ project.title }}
            </CardTitle>
          </div>
          
          <CardDescription class="text-gray-600 line-clamp-3 leading-relaxed">
            {{ project.description }}
          </CardDescription>
        </CardHeader>

        <CardContent class="pt-0">
          <!-- Project Details -->
          <div class="space-y-3 mb-4">
            <div class="flex items-center text-sm text-gray-600">
              <Calendar class="w-4 h-4 mr-2 text-[hsl(var(--secondary))]" />
              <span>Duration: {{ project.duration }}</span>
            </div>
            
            <div class="flex items-center text-sm text-gray-600">
              <Users class="w-4 h-4 mr-2 text-green-600" />
              <span>Team: {{ project.team.length }} members</span>
            </div>

            <div class="flex items-start text-sm text-gray-600">
              <Code2 class="w-4 h-4 mr-2 mt-0.5 text-purple-600 flex-shrink-0" />
              <div class="flex flex-wrap gap-1">
                <Badge 
                  v-for="tech in project.technologies.slice(0, 3)" 
                  :key="tech"
                  variant="outline" 
                  class="text-xs"
                >
                  {{ tech }}
                </Badge>
                <Badge 
                  v-if="project.technologies.length > 3"
                  variant="outline" 
                  class="text-xs"
                >
                  +{{ project.technologies.length - 3 }}
                </Badge>
              </div>
            </div>
          </div>

          <!-- Achievements -->
          <div v-if="project.achievements && project.achievements.length > 0" class="mb-4">
            <div class="flex items-center text-sm font-medium text-gray-700 mb-2">
              <Award class="w-4 h-4 mr-2 text-yellow-600" />
              Achievements
            </div>
            <div class="space-y-1">
              <Badge 
                v-for="achievement in project.achievements" 
                :key="achievement"
                class="bg-yellow-100 text-yellow-800 hover:bg-yellow-200 text-xs mr-1 mb-1"
              >
                🏆 {{ achievement }}
              </Badge>
            </div>
          </div>

          <!-- Supervisor -->
          <div class="mb-4 p-3 bg-gray-50 rounded-lg">
            <div class="text-xs text-gray-500 mb-1">Supervised by</div>
            <div class="font-medium text-[hsl(var(--secondary))]">{{ project.supervisor }}</div>
          </div>
        </CardContent>
      </Card>
    </div>
  </section>
</template>

<script setup lang="ts">
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from './ui/card'
import { Badge } from './ui/badge'
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from './ui/carousel'
import { Calendar, Users, Code2, Award } from 'lucide-vue-next'
import { computed } from 'vue'

// Define interfaces
interface Project {
  id: number
  title: string
  description: string
  category: string
  status: string
  year: number
  duration: string
  supervisor: string
  team: string[]
  technologies: string[]
  achievements: string[]
  image: string
  fallbackGradient: string
  isActive: boolean
}

interface LatestProjectsData {
  isVisible: boolean
  sectionTitle: string
  sectionSubtitle: string
  showCarousel: boolean
  sortBy: 'title' | 'year' | 'status'
  sortOrder: 'asc' | 'desc'
  projects: Project[]
}

interface Props {
  latestProjectsData?: LatestProjectsData
}

// Props with defaults
const props = withDefaults(defineProps<Props>(), {
  latestProjectsData: () => ({
    isVisible: true,
    sectionTitle: 'Latest Projects',
    sectionSubtitle: 'Discover the innovative projects developed by our talented students under expert faculty supervision',
    showCarousel: true,
    sortBy: 'year',
    sortOrder: 'desc',
    projects: [
      {
        id: 1,
        title: "E-Learning Platform",
        description: "A comprehensive online learning management system with real-time collaboration features, video conferencing, and advanced analytics for tracking student progress.",
        category: "Web Development",
        status: "Completed",
        year: 2024,
        duration: "6 months",
        supervisor: "Dr. Ahmed Rahman",
        team: [
          "Sakib Ahmed",
          "Rashida Khan", 
          "Fahim Hasan"
        ],
        technologies: ["Vue.js", "Laravel", "MySQL", "WebRTC"],
        achievements: ["Best Project Award 2024", "Innovation Excellence"],
        image: "/images/project/elearning.jpg",
        fallbackGradient: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
        isActive: true
      },
      {
        id: 2,
        title: "Smart Campus IoT System",
        description: "An intelligent campus management system using IoT sensors for monitoring energy consumption, security, and environmental conditions across university facilities.",
        category: "IoT",
        status: "In Progress",
        year: 2024,
        duration: "8 months",
        supervisor: "Prof. Sultana Begum",
        team: [
          "Mahmud Hassan",
          "Nafisa Rahman",
          "Karim Ahmed",
          "Fatema Khatun"
        ],
        technologies: ["Arduino", "Raspberry Pi", "Node.js", "MongoDB", "MQTT"],
        achievements: ["Research Excellence Grant"],
        image: "/images/project/iot-campus.jpg",
        fallbackGradient: "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)",
        isActive: true
      },
      {
        id: 3,
        title: "Mobile Health Tracker",
        description: "A cross-platform mobile application for health monitoring with AI-powered recommendations, medication reminders, and integration with wearable devices.",
        category: "Mobile App",
        status: "Completed",
        year: 2023,
        duration: "5 months",
        supervisor: "Dr. Mohammad Ali",
        team: [
          "Raihan Uddin",
          "Sumiya Akter"
        ],
        technologies: ["React Native", "Firebase", "TensorFlow", "SQLite"],
        achievements: ["Health Innovation Award", "Top Mobile App 2023"],
        image: "/images/project/health-app.jpg",
        fallbackGradient: "linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)",
        isActive: true
      },
      {
        id: 4,
        title: "Blockchain Voting System",
        description: "A secure and transparent digital voting platform using blockchain technology to ensure vote integrity and real-time result verification.",
        category: "Blockchain",
        status: "Under Review",
        year: 2024,
        duration: "7 months",
        supervisor: "Dr. Rashida Sultana",
        team: [
          "Tanvir Ahmed",
          "Samira Khatun",
          "Rafiq Islam"
        ],
        technologies: ["Solidity", "Web3.js", "Ethereum", "React", "Node.js"],
        achievements: ["Security Excellence Recognition"],
        image: "/images/project/blockchain-voting.jpg",
        fallbackGradient: "linear-gradient(135deg, #fa709a 0%, #fee140 100%)",
        isActive: true
      },
      {
        id: 5,
        title: "AI-Powered Study Assistant",
        description: "An intelligent study companion that uses natural language processing to help students with research, note-taking, and personalized learning recommendations.",
        category: "AI/ML",
        status: "Planning",
        year: 2024,
        duration: "4 months",
        supervisor: "Prof. Khalid Rahman",
        team: [
          "Nusrat Jahan",
          "Sabbir Hasan",
          "Ayesha Rahman"
        ],
        technologies: ["Python", "TensorFlow", "NLTK", "FastAPI", "PostgreSQL"],
        achievements: [],
        image: "/images/project/ai-assistant.jpg",
        fallbackGradient: "linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)",
        isActive: false
      },
      {
        id: 6,
        title: "Green Energy Monitor",
        description: "A comprehensive system for monitoring and optimizing renewable energy sources in smart grids with predictive analytics and automated control systems.",
        category: "Embedded Systems",
        status: "Completed",
        year: 2023,
        duration: "9 months",
        supervisor: "Dr. Mahmuda Khatun",
        team: [
          "Sharif Ahmed",
          "Ruma Begum",
          "Habib Rahman",
          "Salma Akter"
        ],
        technologies: ["C++", "Arduino", "Python", "InfluxDB", "Grafana"],
        achievements: ["Green Technology Award 2023", "Research Publication"],
        image: "/images/project/green-energy.jpg",
        fallbackGradient: "linear-gradient(135deg, #96fbc4 0%, #f9f586 100%)",
        isActive: true
      }
    ]
  })
})

// Computed property to filter and sort projects
const displayProjects = computed(() => {
  let projects = props.latestProjectsData.projects.filter(project => project.isActive)
  
  // Sort projects
  projects.sort((a, b) => {
    const sortBy = props.latestProjectsData.sortBy
    const order = props.latestProjectsData.sortOrder === 'asc' ? 1 : -1
    
    if (sortBy === 'year') {
      return (a.year - b.year) * order
    } else if (sortBy === 'title') {
      return a.title.localeCompare(b.title) * order
    } else if (sortBy === 'status') {
      return a.status.localeCompare(b.status) * order
    }
    
    return 0
  })
  
  return projects
})

// Helper functions
const getCategoryIcon = (category: string): string => {
  const icons: Record<string, string> = {
    'Web Development': '🌐',
    'Mobile App': '📱',
    'IoT': '🔗',
    'AI/ML': '🤖',
    'Blockchain': '⛓️',
    'Embedded Systems': '🔧'
  }
  return icons[category] || '💻'
}

const getStatusColor = (status: string): string => {
  const colors: Record<string, string> = {
    'Completed': 'bg-green-100 text-green-800 hover:bg-green-200',
    'In Progress': 'bg-blue-100 text-blue-800 hover:bg-blue-200',
    'Under Review': 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200',
    'Planning': 'bg-gray-100 text-gray-800 hover:bg-gray-200'
  }
  return colors[status] || 'bg-gray-100 text-gray-800 hover:bg-gray-200'
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Image hover effect */
.group:hover .absolute.inset-0 {
  transform: scale(1.05);
}

/* Carousel button styling */
:deep(.carousel-previous),
:deep(.carousel-next) {
  width: 3rem;
  height: 3rem;
}

/* Card hover animation */
@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-5px);
  }
}

.group:hover {
  animation: float 3s ease-in-out infinite;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
  :deep(.carousel-previous),
  :deep(.carousel-next) {
    display: none;
  }
}
</style>