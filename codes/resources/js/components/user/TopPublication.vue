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
  BookOpen, 
  ExternalLink, 
  Calendar, 
  Users, 
  Star, 
  Award, 
  Quote, 
  TrendingUp,
  FileText,
  Globe,
  Download
} from "lucide-vue-next";

interface PublicationProps {
  id: number;
  title: string;
  abstract: string;
  authors: string[];
  correspondingAuthor: string;
  journal: string;
  journalRank: string;
  impactFactor: number;
  publishDate: string;
  volume?: string;
  issue?: string;
  pages?: string;
  doi?: string;
  category: string;
  keywords: string[];
  citations: number;
  downloads: number;
  openAccess: boolean;
  featured: boolean;
  fallbackGradient: string;
  pdfUrl?: string;
  journalUrl?: string;
}

const publicationsList: PublicationProps[] = [
  {
    id: 1,
    title: "Deep Learning Approaches for Predictive Analytics in Educational Data Mining: A Comprehensive Study",
    abstract: "This paper presents a comprehensive analysis of deep learning methodologies applied to educational data mining, focusing on student performance prediction and learning path optimization.",
    authors: ["Dr. Rahman Ahmed", "Prof. Fatima Khan", "Dr. Nasir Uddin", "Md. Karim Hassan"],
    correspondingAuthor: "Dr. Rahman Ahmed",
    journal: "IEEE Transactions on Learning Technologies",
    journalRank: "Q1",
    impactFactor: 3.87,
    publishDate: "2024-02-15",
    volume: "17",
    issue: "3",
    pages: "245-267",
    doi: "10.1109/TLT.2024.1234567",
    category: "Artificial Intelligence",
    keywords: ["Deep Learning", "Educational Data Mining", "Student Performance", "Machine Learning"],
    citations: 156,
    downloads: 2340,
    openAccess: true,
    featured: true,
    fallbackGradient: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
    pdfUrl: "#",
    journalUrl: "#"
  },
  {
    id: 2,
    title: "Quantum-Inspired Optimization Algorithms for Resource Allocation in Cloud Computing Environments",
    abstract: "We propose novel quantum-inspired algorithms that significantly improve resource allocation efficiency in distributed cloud computing systems, achieving up to 35% better performance.",
    authors: ["Prof. Fatima Khan", "Dr. Sarah Ahmed", "Rashida Begum"],
    correspondingAuthor: "Prof. Fatima Khan",
    journal: "Journal of Parallel and Distributed Computing",
    journalRank: "Q1",
    impactFactor: 4.21,
    publishDate: "2024-01-28",
    volume: "189",
    pages: "104-123",
    doi: "10.1016/j.jpdc.2024.104123",
    category: "Cloud Computing",
    keywords: ["Quantum Computing", "Resource Allocation", "Cloud Computing", "Optimization"],
    citations: 89,
    downloads: 1876,
    openAccess: false,
    featured: true,
    fallbackGradient: "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)",
    pdfUrl: "#",
    journalUrl: "#"
  },
  {
    id: 3,
    title: "Blockchain-Based Secure Framework for IoT Device Authentication and Data Integrity",
    abstract: "This research introduces a novel blockchain framework that ensures secure authentication and data integrity for IoT devices in smart city applications.",
    authors: ["Dr. Nasir Uddin", "Md. Karim Hassan", "Dr. Rahman Ahmed"],
    correspondingAuthor: "Dr. Nasir Uddin",
    journal: "Computer Networks",
    journalRank: "Q1",
    impactFactor: 4.89,
    publishDate: "2023-12-10",
    volume: "236",
    pages: "109912",
    doi: "10.1016/j.comnet.2023.109912",
    category: "Cybersecurity",
    keywords: ["Blockchain", "IoT Security", "Authentication", "Data Integrity"],
    citations: 203,
    downloads: 3120,
    openAccess: true,
    featured: true,
    fallbackGradient: "linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)",
    pdfUrl: "#",
    journalUrl: "#"
  },
  {
    id: 4,
    title: "Natural Language Processing for Bangla Text: A Deep Learning Approach with Transformer Models",
    abstract: "We present state-of-the-art results in Bangla natural language processing tasks using transformer architectures, achieving significant improvements in sentiment analysis and named entity recognition.",
    authors: ["Sarah Ahmed", "Dr. Rahman Ahmed", "Rashida Begum", "Prof. Fatima Khan"],
    correspondingAuthor: "Sarah Ahmed",
    journal: "ACM Transactions on Asian and Low-Resource Language Information Processing",
    journalRank: "Q2",
    impactFactor: 2.45,
    publishDate: "2023-11-22",
    volume: "22",
    issue: "8",
    pages: "Article 185",
    doi: "10.1145/3578354",
    category: "Natural Language Processing",
    keywords: ["NLP", "Bangla Language", "Transformers", "Deep Learning"],
    citations: 124,
    downloads: 1945,
    openAccess: true,
    featured: false,
    fallbackGradient: "linear-gradient(135deg, #fa709a 0%, #fee140 100%)",
    pdfUrl: "#",
    journalUrl: "#"
  },
  {
    id: 5,
    title: "Energy-Efficient Machine Learning Models for Edge Computing in Smart Agriculture",
    abstract: "This study develops lightweight ML models optimized for edge devices in agricultural IoT systems, reducing energy consumption by 60% while maintaining prediction accuracy.",
    authors: ["Md. Karim Hassan", "Dr. Nasir Uddin", "Prof. Fatima Khan"],
    correspondingAuthor: "Md. Karim Hassan",
    journal: "IEEE Internet of Things Journal",
    journalRank: "Q1",
    impactFactor: 10.238,
    publishDate: "2023-10-15",
    volume: "10",
    issue: "20",
    pages: "17834-17847",
    doi: "10.1109/JIOT.2023.3297845",
    category: "IoT & Edge Computing",
    keywords: ["Edge Computing", "Machine Learning", "Smart Agriculture", "Energy Efficiency"],
    citations: 78,
    downloads: 1634,
    openAccess: false,
    featured: false,
    fallbackGradient: "linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)",
    pdfUrl: "#",
    journalUrl: "#"
  },
  {
    id: 6,
    title: "Advanced Computer Vision Techniques for Automated Quality Assessment in Manufacturing",
    abstract: "We propose a comprehensive computer vision pipeline that automates quality control in manufacturing processes, achieving 98.7% accuracy in defect detection.",
    authors: ["Rashida Begum", "Sarah Ahmed", "Dr. Rahman Ahmed"],
    correspondingAuthor: "Rashida Begum",
    journal: "Computer Vision and Image Understanding",
    journalRank: "Q1",
    impactFactor: 4.886,
    publishDate: "2023-09-08",
    volume: "235",
    pages: "103781",
    doi: "10.1016/j.cviu.2023.103781",
    category: "Computer Vision",
    keywords: ["Computer Vision", "Quality Control", "Manufacturing", "Deep Learning"],
    citations: 167,
    downloads: 2456,
    openAccess: true,
    featured: false,
    fallbackGradient: "linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)",
    pdfUrl: "#",
    journalUrl: "#"
  }
];

const formatNumber = (num: number): string => {
  if (num >= 1000) {
    return (num / 1000).toFixed(1) + 'K';
  }
  return num.toString();
};

const formatDate = (dateString: string): string => {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  });
};

const getCategoryColor = (category: string): string => {
  const colors: Record<string, string> = {
    'Artificial Intelligence': 'bg-blue-100 text-blue-800 hover:bg-blue-200',
    'Cloud Computing': 'bg-purple-100 text-purple-800 hover:bg-purple-200',
    'Cybersecurity': 'bg-red-100 text-red-800 hover:bg-red-200',
    'Natural Language Processing': 'bg-green-100 text-green-800 hover:bg-green-200',
    'IoT & Edge Computing': 'bg-teal-100 text-teal-800 hover:bg-teal-200',
    'Computer Vision': 'bg-orange-100 text-orange-800 hover:bg-orange-200'
  };
  return colors[category] || 'bg-gray-100 text-gray-800 hover:bg-gray-200';
};

const getRankColor = (rank: string): string => {
  const colors: Record<string, string> = {
    'Q1': 'bg-emerald-100 text-emerald-800 border-emerald-300',
    'Q2': 'bg-blue-100 text-blue-800 border-blue-300',
    'Q3': 'bg-amber-100 text-amber-800 border-amber-300',
    'Q4': 'bg-gray-100 text-gray-800 border-gray-300'
  };
  return colors[rank] || 'bg-gray-100 text-gray-800 border-gray-300';
};
</script>

<template>
  <section
    id="top-publications"
    class="container pt-24"
  >
    <div class="text-center mb-8">
      <h2 class="text-3xl md:text-4xl text-center font-bold mb-4">
        Top Research Publications & Papers
      </h2>
      
      <p class="text-muted-foreground text-center max-w-2xl mx-auto">
        Explore our faculty's cutting-edge research publications in top-tier international journals, 
        contributing to advancements in computer science and engineering.
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
          v-for="publication in publicationsList"
          :key="publication.id"
          class="pl-2 md:pl-4 md:basis-1/2 lg:basis-1/3"
        >
          <Card class="group h-full bg-white hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border-2 border-gray-100 hover:border-blue-200">
            <!-- Publication Header -->
            <div class="relative overflow-hidden rounded-t-lg">
              <div 
                class="h-32 bg-gradient-to-br from-primary/10 to-primary/5 flex items-center justify-center relative"
                :style="`background: ${publication.fallbackGradient}`"
              >
                <div class="absolute inset-0 bg-black/10"></div>
                
                <!-- Featured Badge -->
                <div v-if="publication.featured" class="absolute top-3 left-3 z-10">
                  <Badge class="bg-amber-500 text-white hover:bg-amber-600 shadow-lg">
                    <Star class="h-3 w-3 mr-1" />
                    Featured
                  </Badge>
                </div>
                
                <!-- Journal Rank Badge -->
                <div class="absolute top-3 right-3 z-10">
                  <Badge :class="getRankColor(publication.journalRank)" class="shadow-lg font-bold border">
                    {{ publication.journalRank }}
                  </Badge>
                </div>
                
                <!-- Open Access Badge -->
                <div v-if="publication.openAccess" class="absolute bottom-3 left-3 z-10">
                  <Badge class="bg-green-100 text-green-800 hover:bg-green-200 shadow-sm text-xs">
                    <Globe class="h-3 w-3 mr-1" />
                    Open Access
                  </Badge>
                </div>
                
                <!-- Book Icon -->
                <FileText class="h-10 w-10 text-white/90" />
              </div>
            </div>

            <CardHeader class="pb-3">
              <!-- Publication Date & Impact Factor -->
              <div class="flex items-center justify-between text-sm text-muted-foreground mb-2">
                <div class="flex items-center gap-1">
                  <Calendar class="h-4 w-4" />
                  <span>{{ formatDate(publication.publishDate) }}</span>
                </div>
                <div class="flex items-center gap-1">
                  <Award class="h-4 w-4" />
                  <span class="font-medium">IF: {{ publication.impactFactor }}</span>
                </div>
              </div>
              
              <CardTitle class="text-base font-bold line-clamp-2 group-hover:text-primary transition-colors duration-200 leading-tight">
                {{ publication.title }}
              </CardTitle>
              
              <CardDescription class="line-clamp-3 text-sm text-muted-foreground leading-relaxed">
                {{ publication.abstract }}
              </CardDescription>
            </CardHeader>

            <CardContent class="pt-0 space-y-4">
              <!-- Journal Info -->
              <div class="space-y-2">
                <div class="text-sm font-medium text-foreground">{{ publication.journal }}</div>
                <div v-if="publication.volume" class="text-xs text-muted-foreground">
                  Vol. {{ publication.volume }}{{ publication.issue ? `, Issue ${publication.issue}` : '' }}{{ publication.pages ? `, pp. ${publication.pages}` : '' }}
                </div>
              </div>

              <!-- Category -->
              <div>
                <Badge :class="getCategoryColor(publication.category)" class="text-xs font-medium">
                  {{ publication.category }}
                </Badge>
              </div>

              <!-- Authors -->
              <div class="space-y-1">
                <div class="text-xs font-medium text-muted-foreground">Authors:</div>
                <div class="text-sm text-foreground">
                  <span class="font-medium">{{ publication.correspondingAuthor }}</span>
                  <span v-if="publication.authors.length > 1" class="text-muted-foreground">
                    + {{ publication.authors.length - 1 }} co-authors
                  </span>
                </div>
              </div>

              <!-- Publication Stats -->
              <div class="flex items-center gap-4 text-sm text-muted-foreground">
                <div class="flex items-center gap-1">
                  <Quote class="h-4 w-4" />
                  <span>{{ formatNumber(publication.citations) }} citations</span>
                </div>
                <div class="flex items-center gap-1">
                  <Download class="h-4 w-4" />
                  <span>{{ formatNumber(publication.downloads) }}</span>
                </div>
              </div>

              <!-- Keywords -->
              <div class="flex flex-wrap gap-1">
                <Badge 
                  v-for="keyword in publication.keywords.slice(0, 3)" 
                  :key="keyword"
                  variant="secondary" 
                  class="text-xs py-1 px-2"
                >
                  {{ keyword }}
                </Badge>
              </div>

              <!-- Action Buttons -->
              <div class="flex gap-2">
                <Button 
                  size="sm" 
                  class="flex-1 text-xs group-hover:bg-primary group-hover:text-primary-foreground transition-all duration-200"
                  variant="outline"
                >
                  <FileText class="h-3 w-3 mr-1" />
                  View Paper
                </Button>
                <Button 
                  size="sm" 
                  variant="ghost"
                  class="px-3"
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
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>