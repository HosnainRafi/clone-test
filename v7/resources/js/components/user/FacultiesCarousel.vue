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

import { 
  ExternalLink, 
  Users, 
  BookOpen, 
  GraduationCap, 
  Star,
  Building,
  Award,
  TrendingUp,
  Calendar,
  MapPin,
  Phone,
  Mail,
  Microscope,
  Calculator,
  Globe,
  Briefcase,
  Heart,
  BookText,
  Stethoscope
} from 'lucide-vue-next'

interface FacultyProps {
  id: number;
  name: string;
  shortName: string;
  description: string;
  image: string;
  fallbackGradient: string;
  link: string;
  departments: string[];
  stats: {
    departments: number;
    students: string;
    faculty: string;
    programs: number;
  };
  highlights: string[];
  featured?: boolean;
  established?: string;
  ranking?: string;
  dean?: {
    name: string;
    title: string;
    email?: string;
  };
  researchAreas: string[];
  achievements: string[];
  icon: any;
}

// Faculty data based on MBSTU website - Enhanced with comprehensive information
const faculties: FacultyProps[] = [
  {
    id: 1,
    name: 'Faculty of Engineering',
    shortName: 'Engineering',
    description: 'Leading innovation in technology and engineering with cutting-edge research, industry partnerships, and world-class laboratories preparing students for the digital future.',
    image: '/images/faculties/engineering.jpg',
    fallbackGradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)',
    link: '/faculty-of-engineering',
    departments: [
      'Computer Science and Engineering (CSE)',
      'Information and Communication Technology (ICT)',
      'Electrical and Electronic Engineering (EEE)',
      'Civil Engineering (CE)'
    ],
    stats: {
      departments: 4,
      students: '1,250+',
      faculty: '48+',
      programs: 12
    },
    highlights: [
      'State-of-the-art laboratories',
      'Industry collaboration',
      'Research excellence',
      'International partnerships'
    ],
    researchAreas: [
      'Artificial Intelligence & Machine Learning',
      'Robotics & Automation',
      'Renewable Energy Systems',
      'Smart Infrastructure'
    ],
    achievements: [
      '15+ International Research Publications (2024)',
      'IEEE Student Branch Chapter',
      'Industry Collaboration with 20+ Companies',
      '90% Graduate Employment Rate'
    ],
    featured: true,
    established: '2002',
    ranking: 'Top Rated',
    dean: {
      name: 'Prof. Dr. Mohammad Motiur Rahman',
      title: 'Dean, Faculty of Engineering',
      email: 'dean.engineering@mbstu.ac.bd'
    },
    icon: Calculator
  },
  {
    id: 2,
    name: 'Faculty of Life Science',
    shortName: 'Life Science',
    description: 'Exploring life sciences through advanced research in biotechnology, environmental science, and molecular biology with modern laboratories and experienced faculty.',
    image: '/images/faculties/life-science.jpg',
    fallbackGradient: 'linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%)',
    link: '/faculty-of-life-science',
    departments: [
      'Biotechnology and Genetic Engineering (BGE)',
      'Microbiology (MB)',
      'Biochemistry and Molecular Biology (BMB)',
      'Environmental Science (ES)'
    ],
    stats: {
      departments: 4,
      students: '850+',
      faculty: '38+',
      programs: 10
    },
    highlights: [
      'Advanced biotechnology labs',
      'Environmental research center',
      'Genetic engineering facility',
      'Molecular biology research'
    ],
    researchAreas: [
      'Genetic Engineering & Biotechnology',
      'Environmental Monitoring',
      'Microbial Technology',
      'Biochemical Analysis'
    ],
    achievements: [
      'Bio-safety Level 2 Laboratory',
      '12+ Research Projects Funded',
      'Environmental Impact Studies',
      'International Collaboration Programs'
    ],
    featured: true,
    established: '2003',
    ranking: 'Excellence',
    dean: {
      name: 'Prof. Dr. Fatima Khatun',
      title: 'Dean, Faculty of Life Science',
      email: 'dean.lifescience@mbstu.ac.bd'
    },
    icon: Microscope
  },
  {
    id: 3,
    name: 'Faculty of Science',
    shortName: 'Science',
    description: 'Advancing fundamental sciences through research in mathematics, physics, chemistry, and statistics with emphasis on computational and analytical approaches.',
    image: '/images/faculties/science.jpg',
    fallbackGradient: 'linear-gradient(135deg, #7c3aed 0%, #8b5cf6 50%, #a78bfa 100%)',
    link: '/faculty-of-science',
    departments: [
      'Mathematics (Math)',
      'Physics (Phy)',
      'Chemistry (Chem)',
      'Statistics (Stat)'
    ],
    stats: {
      departments: 4,
      students: '650+',
      faculty: '32+',
      programs: 8
    },
    highlights: [
      'Research laboratories',
      'Mathematical modeling center',
      'Computational physics lab',
      'Analytical chemistry facility'
    ],
    researchAreas: [
      'Pure & Applied Mathematics',
      'Computational Physics',
      'Analytical Chemistry',
      'Statistical Modeling'
    ],
    achievements: [
      'National Mathematics Olympiad Host',
      '8+ Journal Publications (2024)',
      'Research Collaboration with BUET',
      'Scientific Calculator Development'
    ],
    established: '2002',
    ranking: 'Premier',
    dean: {
      name: 'Prof. Dr. Ahmed Hassan',
      title: 'Dean, Faculty of Science',
      email: 'dean.science@mbstu.ac.bd'
    },
    icon: Globe
  },
  {
    id: 4,
    name: 'Faculty of Business Studies',
    shortName: 'Business',
    description: 'Developing future business leaders through comprehensive management education, entrepreneurship programs, and strong industry connections.',
    image: '/images/faculties/business.jpg',
    fallbackGradient: 'linear-gradient(135deg, #dc2626 0%, #ef4444 50%, #f87171 100%)',
    link: '/faculty-of-business-studies',
    departments: [
      'Business Administration (BBA)',
      'Accounting and Information Systems (AIS)',
      'Marketing (MKT)',
      'Finance and Banking (F&B)'
    ],
    stats: {
      departments: 4,
      students: '950+',
      faculty: '28+',
      programs: 9
    },
    highlights: [
      'Industry partnerships',
      'Entrepreneurship incubator',
      'Case study methodology',
      'Professional certification'
    ],
    researchAreas: [
      'Digital Marketing',
      'Financial Technology',
      'Sustainable Business',
      'Entrepreneurship Development'
    ],
    achievements: [
      'Business Incubation Center',
      '25+ Industry Partnerships',
      'Annual Business Fair',
      '85% Job Placement Rate'
    ],
    established: '2005',
    ranking: 'Growing',
    dean: {
      name: 'Prof. Dr. Nasreen Ahmed',
      title: 'Dean, Faculty of Business Studies',
      email: 'dean.business@mbstu.ac.bd'
    },
    icon: Briefcase
  },
  {
    id: 5,
    name: 'Faculty of Social Science',
    shortName: 'Social Science',
    description: 'Understanding society through comprehensive research in economics, sociology, anthropology, and political science with focus on sustainable development.',
    image: '/images/faculties/social-science.jpg',
    fallbackGradient: 'linear-gradient(135deg, #ea580c 0%, #f97316 50%, #fb923c 100%)',
    link: '/faculty-of-social-science',
    departments: [
      'Economics (Econ)',
      'Sociology (Soc)',
      'Anthropology (Anth)',
      'Political Science (PS)'
    ],
    stats: {
      departments: 4,
      students: '520+',
      faculty: '22+',
      programs: 7
    },
    highlights: [
      'Social research center',
      'Policy analysis unit',
      'Community engagement',
      'Development studies'
    ],
    researchAreas: [
      'Development Economics',
      'Social Policy Analysis',
      'Cultural Anthropology',
      'Political Governance'
    ],
    achievements: [
      'Community Development Projects',
      'Policy Research Publications',
      'NGO Collaboration Programs',
      'Social Impact Assessment'
    ],
    established: '2006',
    ranking: 'Emerging',
    dean: {
      name: 'Prof. Dr. Shahina Begum',
      title: 'Dean, Faculty of Social Science',
      email: 'dean.socialscience@mbstu.ac.bd'
    },
    icon: Users
  },
  {
    id: 6,
    name: 'Faculty of Arts',
    shortName: 'Arts',
    description: 'Preserving cultural heritage and promoting humanities through language, literature, philosophy, and historical studies with modern pedagogical approaches.',
    image: '/images/faculties/arts.jpg',
    fallbackGradient: 'linear-gradient(135deg, #0891b2 0%, #06b6d4 50%, #67e8f9 100%)',
    link: '/faculty-of-arts',
    departments: [
      'Bangla Language and Literature',
      'English Language and Literature',
      'Philosophy (Phil)',
      'History (Hist)'
    ],
    stats: {
      departments: 4,
      students: '420+',
      faculty: '20+',
      programs: 6
    },
    highlights: [
      'Literary research center',
      'Cultural studies program',
      'Language laboratory',
      'Creative writing workshop'
    ],
    researchAreas: [
      'Bengali Literature',
      'Comparative Literature',
      'Philosophical Studies',
      'Historical Research'
    ],
    achievements: [
      'Annual Literary Festival',
      'Cultural Heritage Documentation',
      'International Language Day Celebration',
      'Published Literary Journal'
    ],
    established: '2007',
    ranking: 'Cultural Hub',
    dean: {
      name: 'Prof. Dr. Rashida Sultana',
      title: 'Dean, Faculty of Arts',
      email: 'dean.arts@mbstu.ac.bd'
    },
    icon: BookText
  },
  {
    id: 7,
    name: 'Faculty of Veterinary Medicine and Animal Sciences',
    shortName: 'Veterinary',
    description: 'Advancing animal health and welfare through comprehensive veterinary education, clinical training, and livestock development research.',
    image: '/images/faculties/veterinary.jpg',
    fallbackGradient: 'linear-gradient(135deg, #be185d 0%, #ec4899 50%, #f9a8d4 100%)',
    link: '/faculty-of-veterinary-medicine-and-animal-sciences',
    departments: [
      'Veterinary Medicine (VM)',
      'Animal Science (AS)',
      'Dairy and Poultry Science (DPS)',
      'Animal Health and Management'
    ],
    stats: {
      departments: 4,
      students: '320+',
      faculty: '18+',
      programs: 5
    },
    highlights: [
      'Veterinary teaching hospital',
      'Animal research facility',
      'Clinical training center',
      'Livestock development'
    ],
    researchAreas: [
      'Veterinary Medicine',
      'Animal Nutrition',
      'Livestock Production',
      'Animal Disease Management'
    ],
    achievements: [
      'Veterinary Teaching Hospital',
      'Animal Disease Diagnostic Lab',
      'Livestock Extension Programs',
      'Rural Veterinary Services'
    ],
    established: '2010',
    ranking: 'Specialized',
    dean: {
      name: 'Prof. Dr. Kamrul Islam',
      title: 'Dean, Faculty of Veterinary Medicine',
      email: 'dean.veterinary@mbstu.ac.bd'
    },
    icon: Heart
  }
]

const getRankingColor = (ranking: string): string => {
  const colors: Record<string, string> = {
    'Top Rated': 'bg-emerald-100 text-emerald-800 border-emerald-300',
    'Excellence': 'bg-blue-100 text-blue-800 border-blue-300',
    'Premier': 'bg-purple-100 text-purple-800 border-purple-300',
    'Growing': 'bg-amber-100 text-amber-800 border-amber-300',
    'Emerging': 'bg-orange-100 text-orange-800 border-orange-300',
    'Cultural Hub': 'bg-cyan-100 text-cyan-800 border-cyan-300',
    'Specialized': 'bg-pink-100 text-pink-800 border-pink-300'
  };
  return colors[ranking] || 'bg-gray-100 text-[hsl(var(--secondary))] border-gray-300';
};
</script>

<template>
  <section 
    id="faculties-carousel"
    class="container pt-24"
  >
    <!-- Section Header -->
    <div class="text-center mb-8">
      <h2 class="text-3xl md:text-4xl text-center text-[hsl(var(--secondary))] font-bold mb-4">
        Our Academic Faculties
      </h2>
      <p class="text-muted-foreground text-center max-w-2xl mx-auto">
        Discover the seven diverse faculties that make MBSTU a comprehensive center for academic excellence and research innovation across multiple disciplines.
      </p>
    </div>

    <Carousel
      :opts="{
        align: 'start',
        loop: true,
      }"
      class="relative w-full max-w-7xl mx-auto"
    >
      <CarouselContent class="-ml-2 md:-ml-4">
        <CarouselItem
          v-for="faculty in faculties"
          :key="faculty.id"
          class="pl-2 md:pl-4 md:basis-1/2 lg:basis-1/3"
        >
          <Card class="group h-full bg-white hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border-2 border-gray-100 hover:border-blue-200 overflow-hidden">
            <!-- Faculty Header -->
            <div class="relative overflow-hidden">
              <div 
                class="h-36 bg-gradient-to-br from-primary/10 to-primary/5 flex items-center justify-center relative"
                :style="`background: ${faculty.fallbackGradient}`"
              >
                <div class="absolute inset-0 bg-black/20"></div>
                
                <!-- Featured Badge -->
                <div v-if="faculty.featured" class="absolute top-3 left-3 z-10">
                  <Badge class="bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-lg border-0">
                    <Star class="h-3 w-3 mr-1" />
                    Featured
                  </Badge>
                </div>
                
                <!-- Ranking Badge -->
                <div v-if="faculty.ranking" class="absolute top-3 right-3 z-10">
                  <Badge :class="getRankingColor(faculty.ranking)" class="shadow-lg font-bold border">
                    {{ faculty.ranking }}
                  </Badge>
                </div>
                
                <!-- Established Badge -->
                <div v-if="faculty.established" class="absolute bottom-3 left-3 z-10">
                  <Badge class="bg-white/20 text-white hover:bg-white/30 shadow-sm text-xs backdrop-blur-sm border-white/30">
                    <Building class="h-3 w-3 mr-1" />
                    Est. {{ faculty.established }}
                  </Badge>
                </div>
                
                <!-- Programs Badge -->
                <div class="absolute bottom-3 right-3 z-10">
                  <Badge class="bg-white/20 text-white hover:bg-white/30 shadow-sm text-xs backdrop-blur-sm border-white/30">
                    <BookOpen class="h-3 w-3 mr-1" />
                    {{ faculty.stats.programs }} Programs
                  </Badge>
                </div>
                
                <!-- Dynamic Faculty Icon -->
                <component :is="faculty.icon" class="h-12 w-12 text-white/90" />
              </div>
            </div>

            <CardHeader class="pb-3">
              <!-- Stats Row -->
              <div class="flex items-center justify-between text-sm text-muted-foreground mb-3">
                <div class="flex items-center gap-1">
                  <BookOpen class="h-4 w-4 text-blue-500" />
                  <span class="font-medium">{{ faculty.stats.departments }} Departments</span>
                </div>
                <div class="flex items-center gap-1">
                  <Users class="h-4 w-4 text-green-500" />
                  <span class="font-medium">{{ faculty.stats.students }} Students</span>
                </div>
              </div>
              
              <CardTitle class="text-lg font-bold line-clamp-2 text-[hsl(var(--secondary))] hover:text-[hsl(var(--primary))] transition-colors duration-200 leading-tight mb-2">
                {{ faculty.name }}
              </CardTitle>
              
              <CardDescription class="line-clamp-3 text-sm text-muted-foreground leading-relaxed">
                {{ faculty.description }}
              </CardDescription>
            </CardHeader>

            <CardContent class="pt-0 space-y-4">
              <!-- Dean Information -->
              <div v-if="faculty.dean" class="bg-gray-50 rounded-lg p-3">
                <div class="text-xs font-medium text-[hsl(var(--secondary))] mb-1">Dean</div>
                <div class="text-sm font-semibold text-gray-900">{{ faculty.dean.name }}</div>
                <div class="text-xs text-muted-foreground">{{ faculty.dean.title }}</div>
              </div>

              <!-- Key Departments -->
              <div class="space-y-2">
                <div class="text-sm font-medium text-[hsl(var(--secondary))] flex items-center gap-1">
                  <GraduationCap class="h-4 w-4" />
                  Key Departments:
                </div>
                <div class="text-xs text-muted-foreground leading-relaxed">
                  {{ faculty.departments.slice(0, 2).join(', ') }}
                  <span v-if="faculty.departments.length > 2" class="text-[hsl(var(--primary))] font-medium">
                    + {{ faculty.departments.length - 2 }} more
                  </span>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex gap-2 pt-2">
                <Button 
                  size="sm" 
                  class="flex-1 text-xs bg-[hsl(var(--secondary))] hover:bg-[hsl(var(--primary))] text-white transition-all duration-200"
                  variant="default"
                >
                  <component :is="faculty.icon" class="h-3 w-3 mr-1" />
                  Explore Faculty
                </Button>
                <Button 
                  size="sm" 
                  variant="outline"
                  class="px-3 border-gray-300 hover:border-[hsl(var(--primary))] hover:text-[hsl(var(--primary))]"
                >
                  <ExternalLink class="h-3 w-3" />
                </Button>
              </div>
            </CardContent>
          </Card>
        </CarouselItem>
      </CarouselContent>
      <CarouselPrevious class="hidden sm:flex -left-12 lg:-left-16" />
      <CarouselNext class="hidden sm:flex -right-12 lg:-right-16" />
    </Carousel>
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
</style>