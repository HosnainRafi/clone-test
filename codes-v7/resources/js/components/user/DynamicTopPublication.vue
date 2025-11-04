<script setup lang="ts">
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/user/ui/card';

import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/user/ui/carousel';

import { Badge } from '@/components/user/ui/badge';
import { Button } from '@/components/user/ui/button';

import { Award, Calendar, Download, ExternalLink, FileText, Globe, Quote, Star } from 'lucide-vue-next';

// 1. Define the prop interface
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

// 2. Define the component props
const props = defineProps<{
    publicationItems: PublicationProps[];
}>();

// 3. All helper functions remain the same
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
        day: 'numeric',
    });
};

// Function to decode HTML entities
const decodeHtml = (html: string | undefined): string => {
    if (!html) return '';
    try {
        const txt = document.createElement('textarea');
        txt.innerHTML = html;
        return txt.value;
    } catch (e) {
        console.error('Error decoding HTML:', e);
        return html;
    }
};

const getCategoryColor = (category: string): string => {
    const colors: Record<string, string> = {
        'Artificial Intelligence': 'bg-blue-100 text-blue-800 hover:bg-blue-200',
        'Cloud Computing': 'bg-purple-100 text-purple-800 hover:bg-purple-200',
        Cybersecurity: 'bg-red-100 text-red-800 hover:bg-red-200',
        'Natural Language Processing': 'bg-green-100 text-green-800 hover:bg-green-200',
        'IoT & Edge Computing': 'bg-teal-100 text-teal-800 hover:bg-teal-200',
        'Computer Vision': 'bg-orange-100 text-orange-800 hover:bg-orange-200',
    };
    return colors[category] || 'bg-gray-100 text-[hsl(var(--secondary))] hover:bg-gray-200';
};

const getRankColor = (rank: string): string => {
    const colors: Record<string, string> = {
        Q1: 'bg-emerald-100 text-emerald-800 border-emerald-300',
        Q2: 'bg-blue-100 text-blue-800 border-blue-300',
        Q3: 'bg-amber-100 text-amber-800 border-amber-300',
        Q4: 'bg-gray-100 text-[hsl(var(--secondary))] border-gray-300',
    };
    return colors[rank] || 'bg-gray-100 text-[hsl(var(--secondary))] border-gray-300';
};
</script>

<template>
    <section id="top-publications" class="container pt-24">
        <div class="mb-8 text-center">
            <h2 class="mb-4 text-center text-3xl font-bold text-[hsl(var(--secondary))] md:text-4xl">Top Research Publications & Papers</h2>

            <p class="mx-auto max-w-2xl text-center text-muted-foreground">
                Explore our faculty's cutting-edge research publications in top-tier international journals, contributing to advancements in computer
                science and engineering.
            </p>
        </div>

        <Carousel
            :opts="{
                align: 'start',
                loop: true,
            }"
            class="relative mx-auto w-full max-w-7xl"
        >
            <CarouselContent class="-ml-2 md:-ml-4">
                <CarouselItem v-for="publication in props.publicationItems" :key="publication.id" class="pl-2 md:basis-1/2 md:pl-4 lg:basis-1/3">
                    <Card
                        class="group h-full transform border-2 border-gray-100 bg-white transition-all duration-500 hover:-translate-y-2 hover:border-blue-200 hover:shadow-2xl"
                    >
                        <div class="relative overflow-hidden rounded-t-lg">
                            <div
                                class="relative flex h-32 items-center justify-center bg-gradient-to-br from-primary/10 to-primary/5"
                                :style="`background: ${publication.fallbackGradient}`"
                            >
                                <div class="absolute inset-0 bg-black/10"></div>

                                <div v-if="publication.featured" class="absolute top-3 left-3 z-10">
                                    <Badge class="bg-amber-500 text-white shadow-lg hover:bg-amber-600">
                                        <Star class="mr-1 h-3 w-3" />
                                        Featured
                                    </Badge>
                                </div>

                                <div class="absolute top-3 right-3 z-10">
                                    <Badge :class="getRankColor(publication.journalRank)" class="border font-bold shadow-lg">
                                        {{ publication.journalRank }}
                                    </Badge>
                                </div>

                                <div v-if="publication.openAccess" class="absolute bottom-3 left-3 z-10">
                                    <Badge class="bg-green-100 text-xs text-green-800 shadow-sm hover:bg-green-200">
                                        <Globe class="mr-1 h-3 w-3" />
                                        Open Access
                                    </Badge>
                                </div>

                                <FileText class="h-10 w-10 text-white/90" />
                            </div>
                        </div>

                        <CardHeader class="pb-3">
                            <div class="mb-2 flex items-center justify-between text-sm text-muted-foreground">
                                <div class="flex items-center gap-1">
                                    <Calendar class="h-4 w-4" />
                                    <span>{{ formatDate(publication.publishDate) }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <Award class="h-4 w-4" />
                                    <span class="font-medium">IF: {{ publication.impactFactor }}</span>
                                </div>
                            </div>

                            <CardTitle
                                class="hover:[hsl(var(--primary))] line-clamp-2 text-base leading-tight font-bold text-[hsl(var(--secondary))] transition-colors duration-200"
                            >
                                {{ publication.title }}
                            </CardTitle>

                            <CardDescription
                                class="line-clamp-3 text-sm leading-relaxed text-muted-foreground"
                                v-html="decodeHtml(publication.abstract)"
                            >
                            </CardDescription>
                        </CardHeader>

                        <CardContent class="space-y-4 pt-0">
                            <div class="space-y-2">
                                <div class="text-sm font-medium text-[hsl(var(--secondary))]">{{ publication.journal }}</div>
                                <div v-if="publication.volume" class="text-xs text-muted-foreground">
                                    Vol. {{ publication.volume }}{{ publication.issue ? `, Issue ${publication.issue}` : ''
                                    }}{{ publication.pages ? `, pp. ${publication.pages}` : '' }}
                                </div>
                            </div>

                            <div>
                                <Badge :class="getCategoryColor(publication.category)" class="text-xs font-medium">
                                    {{ publication.category }}
                                </Badge>
                            </div>

                            <div class="space-y-1">
                                <div class="text-xs font-medium text-muted-foreground">Authors:</div>
                                <div class="text-sm text-[hsl(var(--secondary))]">
                                    <span class="font-medium text-[hsl(var(--secondary))]">{{ publication.correspondingAuthor }}</span>
                                    <span v-if="publication.authors.length > 1" class="text-muted-foreground">
                                        + {{ publication.authors.length - 1 }} co-authors
                                    </span>
                                </div>
                            </div>

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

                            <div class="flex flex-wrap gap-1">
                                <Badge
                                    v-for="keyword in publication.keywords.slice(0, 3)"
                                    :key="keyword"
                                    variant="secondary"
                                    class="px-2 py-1 text-xs"
                                >
                                    {{ keyword }}
                                </Badge>
                            </div>

                            <div class="flex gap-2">
                                <Button
                                    size="sm"
                                    class="flex-1 text-xs text-[hsl(var(--secondary))] transition-all duration-200 hover:bg-[hsl(var(--primary))] hover:text-[hsl(var(--primary-foreground))]"
                                    variant="outline"
                                >
                                    <FileText class="mr-1 h-3 w-3" />
                                    View Paper
                                </Button>
                                <Button size="sm" variant="ghost" class="px-3">
                                    <ExternalLink class="h-3 w-3" />
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </CarouselItem>
            </CarouselContent>
            <CarouselPrevious class="-left-12 hidden sm:flex lg:-left-16" />
            <CarouselNext class="-right-12 hidden sm:flex lg:-right-16" />
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
    line-clamp: 3; /* Standard property */
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
