<script setup lang="ts">
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/user/ui/card";

import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from "@/components/user/ui/carousel";

import { Badge } from "@/components/user/ui/badge";
import { Button } from "@/components/user/ui/button";

import { Calendar, Users, GitBranch, ExternalLink, Github, Award, Code2 } from "lucide-vue-next";

interface ProjectProps {
  id: number;
  title: string;
  description: string;
  image: string;
  fallbackGradient: string;
  category: string;
  status: 'Completed' | 'Ongoing' | 'Research';
  duration: string;
  team: string[];
  technologies: string[];
  supervisor: string;
  achievements?: string[];
  githubUrl?: string;
  demoUrl?: string;
  year: string;
}

const latestProjects: ProjectProps[] = [
  {
    id: 1,
    title: "AI-Powered Student Performance Prediction System",
    description: "A machine learning system that analyzes student academic data to predict performance and recommend personalized learning paths. Uses ensemble learning techniques for improved accuracy.",
    image: "/images/projects/ai-prediction.jpg",
    fallbackGradient: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
    category: "Artificial Intelligence",
    status: "Completed",
    duration: "8 months",
    team: ["Aminul Islam", "Fatema Khatun", "Rohit Kumar"],
    technologies: ["Python", "TensorFlow", "Django", "PostgreSQL", "React"],
    supervisor: "Dr. Rahman Ahmed",
    achievements: ["Best Final Year Project 2024", "Published in IEEE Conference"],
    githubUrl: "https://github.com/cse-mbstu/ai-prediction",
    demoUrl: "https://ai-prediction-demo.mbstu.edu",
    year: "2024"
  },
  {
    id: 2,
    title: "Smart Campus Management System",
    description: "IoT-based campus management platform integrating attendance tracking, resource monitoring, and security systems with real-time analytics dashboard.",
    image: "/images/projects/smart-campus.jpg",
    fallbackGradient: "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)",
    category: "Internet of Things",
    status: "Ongoing",
    duration: "6 months",
    team: ["Md. Karim", "Rashida Begum", "Arif Hassan", "Nusrat Jahan"],
    technologies: ["Arduino", "Node.js", "MongoDB", "Vue.js", "MQTT"],
    supervisor: "Prof. Sarah Khan",
    achievements: ["Innovation Award 2024"],
    githubUrl: "https://github.com/cse-mbstu/smart-campus",
    year: "2024"
  },
  {
    id: 3,
    title: "Blockchain-Based Academic Credential Verification",
    description: "Decentralized system for issuing and verifying academic certificates using blockchain technology, ensuring tamper-proof credential management.",
    image: "/images/projects/blockchain-credentials.jpg",
    fallbackGradient: "linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)",
    category: "Blockchain",
    status: "Research",
    duration: "12 months",
    team: ["Tanvir Ahmed", "Sadia Rahman"],
    technologies: ["Ethereum", "Solidity", "Web3.js", "IPFS", "Next.js"],
    supervisor: "Dr. Mohammad Ali",
    achievements: ["Research Grant Recipient"],
    githubUrl: "https://github.com/cse-mbstu/blockchain-credentials",
    year: "2024"
  },
  {
    id: 4,
    title: "Real-time Bengali Speech Recognition System",
    description: "Deep learning-based speech recognition system specifically trained for Bengali language with noise reduction and accent adaptation capabilities.",
    image: "/images/projects/speech-recognition.jpg",
    fallbackGradient: "linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)",
    category: "Natural Language Processing",
    status: "Completed",
    duration: "10 months",
    team: ["Rafiul Islam", "Marium Sultana", "Jakir Hossain"],
    technologies: ["Python", "PyTorch", "FastAPI", "React Native", "Docker"],
    supervisor: "Dr. Nasir Uddin",
    achievements: ["Best Innovation Project 2023", "Patent Application Filed"],
    githubUrl: "https://github.com/cse-mbstu/bengali-speech",
    demoUrl: "https://bengali-speech-demo.mbstu.edu",
    year: "2023"
  },
  {
    id: 5,
    title: "Automated Code Review and Bug Detection Tool",
    description: "ML-powered static code analysis tool that automatically reviews code quality, detects potential bugs, and suggests improvements for multiple programming languages.",
    image: "/images/projects/code-review.jpg",
    fallbackGradient: "linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)",
    category: "Software Engineering",
    status: "Ongoing",
    duration: "7 months",
    team: ["Habibur Rahman", "Ashraful Alam", "Nazma Khatun"],
    technologies: ["Python", "AST", "Machine Learning", "Docker", "GraphQL"],
    supervisor: "Prof. Kamrul Islam",
    achievements: ["Industry Collaboration Award"],
    githubUrl: "https://github.com/cse-mbstu/code-review-tool",
    year: "2024"
  },
  {
    id: 6,
    title: "Augmented Reality Campus Navigation App",
    description: "Mobile AR application that provides interactive navigation assistance for new students and visitors using computer vision and GPS technology.",
    image: "/images/projects/ar-navigation.jpg",
    fallbackGradient: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
    category: "Augmented Reality",
    status: "Completed",
    duration: "9 months",
    team: ["Salim Reza", "Ruma Akter", "Fahim Ahmed"],
    technologies: ["Unity", "ARCore", "Firebase", "C#", "Google Maps API"],
    supervisor: "Dr. Ashik Rahman",
    achievements: ["Mobile App Development Excellence"],
    githubUrl: "https://github.com/cse-mbstu/ar-navigation",
    demoUrl: "https://play.google.com/store/apps/details?id=mbstu.ar.nav",
    year: "2023"
  }
];

// Get status color classes
const getStatusColor = (status: string) => {
  switch (status) {
    case 'Completed':
      return 'bg-green-100 text-green-800 hover:bg-green-200'
    case 'Ongoing':
      return 'bg-blue-100 text-blue-800 hover:bg-blue-200'
    case 'Research':
      return 'bg-purple-100 text-purple-800 hover:bg-purple-200'
    default:
      return 'bg-gray-100 text-[hsl(var(--secondary))] hover:bg-gray-200'
  }
}

// Get category icon
const getCategoryIcon = (category: string) => {
  if (category.includes('AI') || category.includes('Intelligence')) return '🤖'
  if (category.includes('IoT')) return '📡'
  if (category.includes('Blockchain')) return '⛓️'
  if (category.includes('Language')) return '🗣️'
  if (category.includes('Software')) return '💻'
  if (category.includes('AR') || category.includes('Augmented')) return '👓'
  return '🔬'
}
</script>

<template>
  <section class="pt-24 container">
    <!-- Section Header -->
    <div class="text-center mb-12">
    <h2 class="text-3xl md:text-4xl font-bold text-[hsl(var(--secondary))] mb-4">
        Latest Projects
    </h2>
    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
        Discover the innovative projects developed by our talented students under expert faculty supervision
    </p>
    </div>

    <!-- Projects Carousel -->
    <Carousel
    :opts="{
        align: 'start',
        loop: true,
    }"
    class="relative w-full max-w-7xl mx-auto"
    >
    <CarouselContent class="-ml-2 md:-ml-4">
        <CarouselItem
        v-for="project in latestProjects"
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
                <Calendar class="w-4 h-4 mr-2 text-[hsl(var(--secondary))] " />
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

    <!-- View All Projects Button -->
    <div class="text-center mt-12">
    <Button size="lg" class="bg-[hsl(var(--secondary))] hover:bg-[hsl(var(--primary))] text-white px-8 py-3">
        <GitBranch class="w-5 h-5 mr-2" />
        View All Projects
    </Button>
    </div>
  </section>
</template>

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

/* Custom hover effects */
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