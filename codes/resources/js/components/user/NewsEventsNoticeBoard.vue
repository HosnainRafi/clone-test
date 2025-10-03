<script setup lang="ts">
import { ref, computed } from 'vue'
import { Calendar, Clock, Users, MapPin, ExternalLink, ChevronRight, Bell, Newspaper, Star } from 'lucide-vue-next'

// Active tab state
const activeTab = ref<'news' | 'events' | 'notices'>('news')

// Sample data for News, Events, and Notices
const newsData = [
  {
    id: 1,
    title: 'CSE Department Achieves Top Ranking in National University Assessment',
    excerpt: 'The Computer Science and Engineering department has been ranked among the top 5 CSE departments in Bangladesh...',
    date: '2025-01-15',
    category: 'Achievement',
    image: '/images/news/ranking-achievement.jpg',
    priority: 'high',
    author: 'Dr. Rahman Ahmed',
    readTime: '3 min read'
  },
  {
    id: 2,
    title: 'New AI Research Lab Inaugurated with State-of-the-Art Equipment',
    excerpt: 'A cutting-edge Artificial Intelligence research laboratory has been established to enhance research capabilities...',
    date: '2025-01-12',
    category: 'Research',
    image: '/images/news/ai-lab-opening.jpg',
    priority: 'medium',
    author: 'Prof. Sarah Khan',
    readTime: '4 min read'
  },
  {
    id: 3,
    title: 'Students Win International Programming Championship',
    excerpt: 'Three CSE students represented Bangladesh at the ACM ICPC World Finals and secured second position...',
    date: '2025-01-10',
    category: 'Student Success',
    image: '/images/news/programming-championship.jpg',
    priority: 'high',
    author: 'News Desk',
    readTime: '2 min read'
  },
  {
    id: 4,
    title: 'Industry Partnership with Leading Tech Companies Announced',
    excerpt: 'The department has signed MoUs with Google, Microsoft, and local tech giants for internship programs...',
    date: '2025-01-08',
    category: 'Partnership',
    image: '/images/news/industry-partnership.jpg',
    priority: 'medium',
    author: 'Admin Office',
    readTime: '3 min read'
  }
]

const eventsData = [
  {
    id: 1,
    title: 'International Conference on Artificial Intelligence and Machine Learning',
    description: 'Join leading researchers and industry experts for presentations on cutting-edge AI technologies.',
    date: '2025-03-15',
    time: '09:00 AM',
    endDate: '2025-03-17',
    venue: 'MBSTU Auditorium',
    category: 'Conference',
    status: 'upcoming',
    registration: 'Open',
    fee: 'Free for students',
    organizer: 'CSE Department',
    participants: '200+ expected'
  },
  {
    id: 2,
    title: 'Annual Tech Fest 2025 - CodeCraft',
    description: 'Three-day technology festival featuring programming contests, workshops, and tech exhibitions.',
    date: '2025-02-20',
    time: '10:00 AM',
    endDate: '2025-02-22',
    venue: 'University Campus',
    category: 'Festival',
    status: 'upcoming',
    registration: 'Open',
    fee: 'Registration required',
    organizer: 'Student Council',
    participants: '500+ participants'
  },
  {
    id: 3,
    title: 'Industry Expert Seminar on Cybersecurity',
    description: 'Expert talk on current cybersecurity challenges and career opportunities in the field.',
    date: '2025-02-05',
    time: '02:00 PM',
    endDate: '2025-02-05',
    venue: 'Conference Hall',
    category: 'Seminar',
    status: 'upcoming',
    registration: 'Open',
    fee: 'Free',
    organizer: 'CSE Department',
    participants: '100 seats'
  },
  {
    id: 4,
    title: 'Freshers\' Orientation Program',
    description: 'Welcome program for new students including department introduction and academic guidance.',
    date: '2025-01-25',
    time: '09:00 AM',
    endDate: '2025-01-25',
    venue: 'University Auditorium',
    category: 'Orientation',
    status: 'completed',
    registration: 'Closed',
    fee: 'Free',
    organizer: 'Academic Office',
    participants: '150 students'
  }
]

const noticesData = [
  {
    id: 1,
    title: 'Fall 2025 Semester Admission Application Deadline Extended',
    content: 'The deadline for Fall 2025 semester admission applications has been extended to February 15, 2025. Eligible candidates can apply through the university portal.',
    date: '2025-01-18',
    category: 'Admission',
    priority: 'high',
    department: 'Admission Office',
    validUntil: '2025-02-15',
    attachments: ['admission-guidelines.pdf', 'application-form.pdf']
  },
  {
    id: 2,
    title: 'Spring 2025 Final Examination Schedule',
    content: 'The final examination for Spring 2025 semester will commence from March 1, 2025. Detailed schedule and hall ticket information available on student portal.',
    date: '2025-01-16',
    category: 'Examination',
    priority: 'high',
    department: 'Controller of Examinations',
    validUntil: '2025-03-01',
    attachments: ['exam-schedule.pdf', 'guidelines.pdf']
  },
  {
    id: 3,
    title: 'Research Grant Application Open for Faculty Members',
    content: 'Faculty members can apply for research grants up to BDT 5,00,000 for innovative research projects. Application deadline: March 30, 2025.',
    date: '2025-01-14',
    category: 'Research',
    priority: 'medium',
    department: 'Research Cell',
    validUntil: '2025-03-30',
    attachments: ['grant-application.pdf']
  },
  {
    id: 4,
    title: 'Library Timing Change During Exam Period',
    content: 'The university library will remain open 24/7 during the final examination period (March 1-15, 2025) to support students.',
    date: '2025-01-12',
    category: 'Facilities',
    priority: 'low',
    department: 'Library',
    validUntil: '2025-03-15',
    attachments: []
  }
]

// Computed properties for filtering and sorting
const currentData = computed(() => {
  switch (activeTab.value) {
    case 'news':
      return newsData.sort((a, b) => new Date(b.date).getTime() - new Date(a.date).getTime())
    case 'events':
      return eventsData.sort((a, b) => new Date(a.date).getTime() - new Date(b.date).getTime())
    case 'notices':
      return noticesData.sort((a, b) => new Date(b.date).getTime() - new Date(a.date).getTime())
    default:
      return []
  }
})

// Utility functions
const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const getTimeAgo = (dateString: string) => {
  const now = new Date()
  const date = new Date(dateString)
  const diffTime = Math.abs(now.getTime() - date.getTime())
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
  
  if (diffDays === 1) return '1 day ago'
  if (diffDays < 7) return `${diffDays} days ago`
  if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`
  return `${Math.floor(diffDays / 30)} months ago`
}

const getPriorityColor = (priority: string) => {
  switch (priority) {
    case 'high': return 'text-red-600 bg-red-50 border-red-200'
    case 'medium': return 'text-yellow-600 bg-yellow-50 border-yellow-200'
    default: return 'text-blue-600 bg-blue-50 border-blue-200'
  }
}

const getEventStatus = (status: string) => {
  switch (status) {
    case 'upcoming': return 'text-green-600 bg-green-50'
    case 'ongoing': return 'text-blue-600 bg-blue-50'
    case 'completed': return 'text-gray-600 bg-gray-50'
    default: return 'text-gray-600 bg-gray-50'
  }
}
</script>

<template>
  <section class="container pt-24">
    <!-- Section Header -->
    <div class="text-center mb-12">
    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
        News, Events & Notices
    </h2>
    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
        Stay updated with the latest news, upcoming events, and important announcements from the Department of Computer Science and Engineering
    </p>
    </div>

    <!-- Tab Navigation -->
    <div class="flex justify-center mb-8">
    <div class="inline-flex rounded-lg bg-white p-1 shadow-lg border border-gray-200">
        <button
        @click="activeTab = 'news'"
        :class="[
            'flex items-center px-6 py-3 rounded-md text-sm font-medium transition-all duration-200',
            activeTab === 'news'
            ? 'bg-blue-600 text-white shadow-sm'
            : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'
        ]"
        >
        <Newspaper class="w-4 h-4 mr-2" />
        News
        <span :class="[
            'ml-2 px-2 py-0.5 text-xs rounded-full font-semibold',
            activeTab === 'news' 
            ? 'bg-white text-blue-600' 
            : 'bg-blue-100 text-blue-700'
        ]">
            {{ newsData.length }}
        </span>
        </button>
        <button
        @click="activeTab = 'events'"
        :class="[
            'flex items-center px-6 py-3 rounded-md text-sm font-medium transition-all duration-200',
            activeTab === 'events'
            ? 'bg-blue-600 text-white shadow-sm'
            : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'
        ]"
        >
        <Calendar class="w-4 h-4 mr-2" />
        Events
        <span :class="[
            'ml-2 px-2 py-0.5 text-xs rounded-full font-semibold',
            activeTab === 'events' 
            ? 'bg-white text-blue-600' 
            : 'bg-green-100 text-green-700'
        ]">
            {{ eventsData.filter(e => e.status === 'upcoming').length }}
        </span>
        </button>
        <button
        @click="activeTab = 'notices'"
        :class="[
            'flex items-center px-6 py-3 rounded-md text-sm font-medium transition-all duration-200',
            activeTab === 'notices'
            ? 'bg-blue-600 text-white shadow-sm'
            : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'
        ]"
        >
        <Bell class="w-4 h-4 mr-2" />
        Notices
        <span :class="[
            'ml-2 px-2 py-0.5 text-xs rounded-full font-semibold',
            activeTab === 'notices' 
            ? 'bg-white text-blue-600' 
            : 'bg-red-100 text-red-700'
        ]">
            {{ noticesData.filter(n => n.priority === 'high').length }}
        </span>
        </button>
    </div>
    </div>

    <!-- Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Main Content -->
    <div class="lg:col-span-2">
        
        <!-- News Tab -->
        <div v-if="activeTab === 'news'" class="space-y-6">
        <div
            v-for="item in newsData.slice(0, 4)"
            :key="item.id"
            class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300 group"
        >
            <div class="md:flex">
            <div class="md:w-1/3">
                <div class="h-48 md:h-full bg-gradient-to-br from-blue-500 to-purple-600 relative overflow-hidden">
                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                <div class="absolute top-4 left-4">
                    <span :class="[
                    'px-2 py-1 text-xs font-medium rounded-full',
                    getPriorityColor(item.priority)
                    ]">
                    {{ item.category }}
                    </span>
                </div>
                </div>
            </div>
            <div class="md:w-2/3 p-6">
                <div class="flex items-center justify-between mb-3">
                <div class="flex items-center text-sm text-gray-500">
                    <Clock class="w-4 h-4 mr-1" />
                    {{ formatDate(item.date) }}
                </div>
                <span class="text-sm text-blue-600 font-medium">{{ item.readTime }}</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                {{ item.title }}
                </h3>
                <p class="text-gray-600 mb-4 line-clamp-2">
                {{ item.excerpt }}
                </p>
                <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">By {{ item.author }}</span>
                <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                    Read More
                    <ChevronRight class="w-4 h-4 ml-1" />
                </a>
                </div>
            </div>
            </div>
        </div>
        </div>

        <!-- Events Tab -->
        <div v-if="activeTab === 'events'" class="space-y-6">
        <div
            v-for="event in eventsData.slice(0, 4)"
            :key="event.id"
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300"
        >
            <div class="flex items-start justify-between mb-4">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                <Calendar class="w-6 h-6 text-blue-600" />
                </div>
                <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ event.title }}</h3>
                <div class="flex items-center text-sm text-gray-500 space-x-4">
                    <span class="flex items-center">
                    <Clock class="w-4 h-4 mr-1" />
                    {{ formatDate(event.date) }} at {{ event.time }}
                    </span>
                    <span class="flex items-center">
                    <MapPin class="w-4 h-4 mr-1" />
                    {{ event.venue }}
                    </span>
                </div>
                </div>
            </div>
            <span :class="[
                'px-3 py-1 text-xs font-medium rounded-full',
                getEventStatus(event.status)
            ]">
                {{ event.status }}
            </span>
            </div>
            <p class="text-gray-600 mb-4">{{ event.description }}</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-900">Category:</span>
                <p class="text-gray-600">{{ event.category }}</p>
            </div>
            <div>
                <span class="font-medium text-gray-900">Registration:</span>
                <p class="text-gray-600">{{ event.registration }}</p>
            </div>
            <div>
                <span class="font-medium text-gray-900">Fee:</span>
                <p class="text-gray-600">{{ event.fee }}</p>
            </div>
            <div>
                <span class="font-medium text-gray-900">Participants:</span>
                <p class="text-gray-600">{{ event.participants }}</p>
            </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
            <span class="text-sm text-gray-500">Organized by {{ event.organizer }}</span>
            <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                View Details
                <ExternalLink class="w-4 h-4 ml-1" />
            </a>
            </div>
        </div>
        </div>

        <!-- Notices Tab -->
        <div v-if="activeTab === 'notices'" class="space-y-4">
        <div
            v-for="notice in noticesData"
            :key="notice.id"
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300"
        >
            <div class="flex items-start justify-between mb-3">
            <div class="flex items-center">
                <div :class="[
                'p-2 rounded-lg mr-3',
                notice.priority === 'high' ? 'bg-red-100' : 'bg-blue-100'
                ]">
                <Bell :class="[
                    'w-5 h-5',
                    notice.priority === 'high' ? 'text-red-600' : 'text-blue-600'
                ]" />
                </div>
                <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ notice.title }}</h3>
                <div class="flex items-center text-sm text-gray-500 space-x-4 mt-1">
                    <span>{{ formatDate(notice.date) }}</span>
                    <span>{{ notice.department }}</span>
                </div>
                </div>
            </div>
            <span :class="[
                'px-2 py-1 text-xs font-medium rounded-full',
                getPriorityColor(notice.priority)
            ]">
                {{ notice.priority }}
            </span>
            </div>
            <p class="text-gray-600 mb-4">{{ notice.content }}</p>
            <div v-if="notice.attachments.length > 0" class="mb-4">
            <h4 class="text-sm font-medium text-gray-900 mb-2">Attachments:</h4>
            <div class="flex flex-wrap gap-2">
                <span
                v-for="attachment in notice.attachments"
                :key="attachment"
                class="inline-flex items-center px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 cursor-pointer"
                >
                📎 {{ attachment }}
                </span>
            </div>
            </div>
            <div class="text-sm text-gray-500">
            Valid until: {{ formatDate(notice.validUntil) }}
            </div>
        </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Quick Stats -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
            <span class="text-gray-600">Total News</span>
            <span class="font-semibold text-blue-600">{{ newsData.length }}</span>
            </div>
            <div class="flex items-center justify-between">
            <span class="text-gray-600">Upcoming Events</span>
            <span class="font-semibold text-green-600">{{ eventsData.filter(e => e.status === 'upcoming').length }}</span>
            </div>
            <div class="flex items-center justify-between">
            <span class="text-gray-600">Active Notices</span>
            <span class="font-semibold text-red-600">{{ noticesData.filter(n => n.priority === 'high').length }}</span>
            </div>
        </div>
        </div>

        <!-- Recent Updates -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <Star class="w-5 h-5 mr-2 text-yellow-500" />
            Recent Updates
        </h3>
        <div class="space-y-3">
            <div v-for="item in [...newsData.slice(0, 2), ...eventsData.slice(0, 1)]" :key="`recent-${item.id}`" class="border-l-3 border-blue-500 pl-3">
            <h4 class="font-medium text-gray-900 text-sm line-clamp-2">{{ item.title }}</h4>
            <p class="text-xs text-gray-500 mt-1">{{ getTimeAgo(item.date) }}</p>
            </div>
        </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h3>
        <div class="space-y-2">
            <a href="/news" class="block text-blue-600 hover:text-blue-700 text-sm font-medium hover:underline">
            View All News →
            </a>
            <a href="/events" class="block text-blue-600 hover:text-blue-700 text-sm font-medium hover:underline">
            Event Calendar →
            </a>
            <a href="/notices" class="block text-blue-600 hover:text-blue-700 text-sm font-medium hover:underline">
            All Notices →
            </a>
            <a href="/announcements" class="block text-blue-600 hover:text-blue-700 text-sm font-medium hover:underline">
            Announcements →
            </a>
        </div>
        </div>
    </div>
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

.border-l-3 {
  border-left-width: 3px;
}

/* Smooth hover transitions for cards */
.bg-white {
  transition: all 0.3s ease;
}

.bg-white:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Ensure smooth transitions for all interactive elements */
.group {
  transition: all 0.3s ease;
}

.group-hover\:text-blue-600:hover {
  transition: color 0.2s ease;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .grid {
    grid-template-columns: 1fr;
  }
  
  .bg-white:hover {
    transform: none; /* Disable transform on mobile to prevent issues */
  }
}
</style>