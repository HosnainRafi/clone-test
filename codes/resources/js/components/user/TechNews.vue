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

import { Calendar, ExternalLink, Clock, Eye, MessageSquare, TrendingUp, Newspaper } from "lucide-vue-next";

interface NewsProps {
  id: number;
  title: string;
  summary: string;
  content: string;
  image: string;
  fallbackGradient: string;
  category: string;
  author: string;
  authorRole: string;
  publishDate: string;
  readTime: string;
  views: number;
  comments: number;
  tags: string[];
  trending: boolean;
  source: string;
  sourceUrl?: string;
}

const techNewsList: NewsProps[] = [
  {
    id: 1,
    title: "AI Revolution in Computer Science Education: New Curriculum at MBSTU",
    summary: "MBSTU CSE Department introduces cutting-edge AI and Machine Learning courses to prepare students for the future of technology.",
    content: "The Computer Science and Engineering Department at MBSTU has announced a comprehensive curriculum overhaul that integrates artificial intelligence and machine learning across all levels of study.",
    image: "/images/news/ai-education.jpg",
    fallbackGradient: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
    category: "Education",
    author: "Dr. Rahman Ahmed",
    authorRole: "Head of Department",
    publishDate: "2024-03-15",
    readTime: "5 min",
    views: 1250,
    comments: 23,
    tags: ["AI", "Education", "Curriculum", "Future Tech"],
    trending: true,
    source: "MBSTU News",
    sourceUrl: "#"
  },
  {
    id: 2,
    title: "Breakthrough in Quantum Computing Research by MBSTU Faculty",
    summary: "Faculty members publish groundbreaking research on quantum algorithms that could revolutionize computational efficiency.",
    content: "A team of researchers from MBSTU's Computer Science Department has made significant progress in quantum computing algorithms, publishing their findings in top-tier international journals.",
    image: "/images/news/quantum-computing.jpg",
    fallbackGradient: "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)",
    category: "Research",
    author: "Prof. Fatima Khan",
    authorRole: "Research Director",
    publishDate: "2024-03-12",
    readTime: "7 min",
    views: 892,
    comments: 15,
    tags: ["Quantum Computing", "Research", "Innovation", "Algorithms"],
    trending: true,
    source: "Tech Research Today",
    sourceUrl: "#"
  },
  {
    id: 3,
    title: "Student Innovation: IoT-Based Smart Campus Management System",
    summary: "Final year students develop comprehensive IoT solution for campus automation and resource management.",
    content: "A group of final year CSE students has developed an innovative IoT-based system that integrates various campus services including library management, energy optimization, and security monitoring.",
    image: "/images/news/smart-campus.jpg",
    fallbackGradient: "linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)",
    category: "Innovation",
    author: "Sarah Ahmed",
    authorRole: "Tech Journalist",
    publishDate: "2024-03-10",
    readTime: "4 min",
    views: 634,
    comments: 8,
    tags: ["IoT", "Smart Campus", "Student Projects", "Innovation"],
    trending: false,
    source: "Campus Tech",
    sourceUrl: "#"
  },
  {
    id: 4,
    title: "Cybersecurity Excellence: MBSTU Team Wins National Competition",
    summary: "CSE students secure first place in Bangladesh National Cybersecurity Challenge with innovative security solutions.",
    content: "The MBSTU Cybersecurity team has achieved remarkable success by winning the national competition, showcasing advanced skills in ethical hacking, network security, and digital forensics.",
    image: "/images/news/cybersecurity-win.jpg",
    fallbackGradient: "linear-gradient(135deg, #fa709a 0%, #fee140 100%)",
    category: "Achievement",
    author: "Md. Karim Hassan",
    authorRole: "Competition Coordinator",
    publishDate: "2024-03-08",
    readTime: "6 min",
    views: 1540,
    comments: 31,
    tags: ["Cybersecurity", "Competition", "Achievement", "Students"],
    trending: true,
    source: "Cyber News BD",
    sourceUrl: "#"
  },
  {
    id: 5,
    title: "Industry Partnership: Major Tech Companies Collaborate with MBSTU",
    summary: "Leading technology companies establish research partnerships and internship programs with CSE department.",
    content: "Several multinational technology companies have signed agreements with MBSTU to provide research funding, internship opportunities, and industry mentorship programs for CSE students.",
    image: "/images/news/industry-partnership.jpg",
    fallbackGradient: "linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)",
    category: "Partnership",
    author: "Dr. Nasir Uddin",
    authorRole: "Industry Relations",
    publishDate: "2024-03-05",
    readTime: "8 min",
    views: 978,
    comments: 19,
    tags: ["Industry", "Partnership", "Internship", "Collaboration"],
    trending: false,
    source: "Industry Today",
    sourceUrl: "#"
  },
  {
    id: 6,
    title: "Open Source Revolution: MBSTU Contributes to Major Projects",
    summary: "Faculty and students actively contribute to open source projects, gaining international recognition in the developer community.",
    content: "The Computer Science Department at MBSTU has been making significant contributions to major open source projects, with several faculty members and students gaining recognition as core contributors.",
    image: "/images/news/open-source.jpg",
    fallbackGradient: "linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)",
    category: "Open Source",
    author: "Rashida Begum",
    authorRole: "Open Source Advocate",
    publishDate: "2024-03-02",
    readTime: "5 min",
    views: 756,
    comments: 12,
    tags: ["Open Source", "Community", "Development", "Recognition"],
    trending: false,
    source: "Open Source Weekly",
    sourceUrl: "#"
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
    'Education': 'bg-blue-100 text-blue-800 hover:bg-blue-200',
    'Research': 'bg-purple-100 text-purple-800 hover:bg-purple-200',
    'Innovation': 'bg-green-100 text-green-800 hover:bg-green-200',
    'Achievement': 'bg-orange-100 text-orange-800 hover:bg-orange-200',
    'Partnership': 'bg-indigo-100 text-indigo-800 hover:bg-indigo-200',
    'Open Source': 'bg-teal-100 text-teal-800 hover:bg-teal-200'
  };
  return colors[category] || 'bg-gray-100 text-gray-800 hover:bg-gray-200';
};
</script>

<template>
  <section
    id="tech-news"
    class="container pt-24"
  >
    <div class="text-center mb-8">
      <h2 class="text-3xl md:text-4xl text-center font-bold mb-4">
        Latest Technology News & Insights
      </h2>
      
      <p class="text-muted-foreground text-center max-w-2xl mx-auto">
        Stay updated with the latest developments in technology, research breakthroughs, 
        and achievements from MBSTU Computer Science & Engineering department.
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
          v-for="news in techNewsList"
          :key="news.id"
          class="pl-2 md:pl-4 md:basis-1/2 lg:basis-1/3"
        >
          <Card class="group h-full bg-white hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border-2 border-gray-100 hover:border-blue-200">
            <!-- News Image/Header -->
            <div class="relative overflow-hidden rounded-t-lg">
              <div 
                class="h-48 bg-gradient-to-br from-primary/10 to-primary/5 flex items-center justify-center relative"
                :style="`background: ${news.fallbackGradient}`"
              >
                <div class="absolute inset-0 bg-black/20"></div>
                
                <!-- Trending Badge -->
                <div v-if="news.trending" class="absolute top-3 left-3 z-10">
                  <Badge class="bg-red-500 text-white hover:bg-red-600 shadow-lg">
                    <TrendingUp class="h-3 w-3 mr-1" />
                    Trending
                  </Badge>
                </div>
                
                <!-- Category Badge -->
                <div class="absolute top-3 right-3 z-10">
                  <Badge :class="getCategoryColor(news.category)" class="shadow-lg font-medium">
                    {{ news.category }}
                  </Badge>
                </div>
                
                <!-- Newspaper Icon -->
                <Newspaper class="h-12 w-12 text-white/80" />
              </div>
            </div>

            <CardHeader class="pb-3">
              <div class="flex items-center gap-2 text-sm text-muted-foreground mb-2">
                <Calendar class="h-4 w-4" />
                <span>{{ formatDate(news.publishDate) }}</span>
                <span class="text-gray-300">•</span>
                <Clock class="h-4 w-4" />
                <span>{{ news.readTime }} read</span>
              </div>
              
              <CardTitle class="text-lg font-bold line-clamp-2 group-hover:text-primary transition-colors duration-200">
                {{ news.title }}
              </CardTitle>
              
              <CardDescription class="line-clamp-3 text-muted-foreground">
                {{ news.summary }}
              </CardDescription>
            </CardHeader>

            <CardContent class="pt-0">
              <!-- Author Info -->
              <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-gradient-to-br from-primary to-primary/60 rounded-full flex items-center justify-center">
                  <span class="text-white text-sm font-semibold">
                    {{ news.author.split(' ').map(n => n[0]).join('') }}
                  </span>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="text-sm font-medium text-foreground">{{ news.author }}</div>
                  <div class="text-xs text-muted-foreground">{{ news.authorRole }}</div>
                </div>
              </div>

              <!-- Engagement Stats -->
              <div class="flex items-center gap-4 text-sm text-muted-foreground mb-4">
                <div class="flex items-center gap-1">
                  <Eye class="h-4 w-4" />
                  <span>{{ formatNumber(news.views) }}</span>
                </div>
                <div class="flex items-center gap-1">
                  <MessageSquare class="h-4 w-4" />
                  <span>{{ news.comments }}</span>
                </div>
                <div class="flex items-center gap-1 text-xs">
                  <span>{{ news.source }}</span>
                </div>
              </div>

              <!-- Tags -->
              <div class="flex flex-wrap gap-1 mb-4">
                <Badge 
                  v-for="tag in news.tags.slice(0, 3)" 
                  :key="tag"
                  variant="secondary" 
                  class="text-xs py-1 px-2"
                >
                  {{ tag }}
                </Badge>
              </div>

              <!-- Action Buttons -->
              <div class="flex gap-2">
                <Button 
                  size="sm" 
                  class="flex-1 group-hover:bg-primary group-hover:text-primary-foreground transition-all duration-200"
                  variant="outline"
                >
                  Read More
                  <ExternalLink class="h-3 w-3 ml-1" />
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