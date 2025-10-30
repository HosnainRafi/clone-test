<template>
  <DefaultLayout>
    <!-- Breadcrumb -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h2 class="text-title-md2 font-bold text-black dark:text-white">
        {{ pageId ? 'Edit Page' : 'Create New Page' }}
      </h2>
      <nav>
        <ol class="flex items-center gap-2">
          <li>
            <Link class="font-medium" href="/dashboard">Dashboard /</Link>
          </li>
          <li>
            <Link class="font-medium" href="/dashboard/pages">Pages /</Link>
          </li>
          <li class="font-medium text-primary">{{ pageId ? 'Edit' : 'Create' }}</li>
        </ol>
      </nav>
    </div>

    <!-- Page Editor Form -->
    <div class="rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
      <form @submit.prevent="submitForm" class="space-y-6">
        <!-- Basic Page Info -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <div>
            <label class="mb-2.5 block text-black dark:text-white">Title</label>
            <input
              v-model="form.title"
              type="text"
              placeholder="Enter page title"
              class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
              required
            />
          </div>

          <div>
            <label class="mb-2.5 block text-black dark:text-white">Slug</label>
            <input
              v-model="form.slug"
              type="text"
              placeholder="page-slug"
              class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
              required
            />
          </div>
        </div>

        <!-- Page Builder Section -->
        <div class="border-t border-stroke pt-6 dark:border-strokedark">
          <div class="mb-6 flex items-center justify-between">
            <h3 class="text-xl font-semibold text-black dark:text-white">Page Components</h3>
            <button
              type="button"
              @click="showComponentLibrary = !showComponentLibrary"
              class="inline-flex items-center rounded-md bg-primary py-2 px-4 text-sm font-medium text-white hover:bg-opacity-90"
            >
              <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              {{ showComponentLibrary ? 'Hide Components' : 'Add Component' }}
            </button>
          </div>

          <!-- Component Library -->
          <div v-if="showComponentLibrary" class="mb-6 rounded-lg border border-stroke bg-gray-50 p-4 dark:border-strokedark dark:bg-gray-800">
            <h4 class="mb-4 text-lg font-medium text-black dark:text-white">Available Components</h4>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
              <button
                v-for="comp in componentLibrary"
                :key="comp.type"
                type="button"
                @click="addComponent(comp.type)"
                class="flex items-center rounded-lg border border-stroke bg-white p-4 text-left transition-all hover:border-primary hover:shadow-md dark:border-strokedark dark:bg-boxdark dark:hover:border-primary"
              >
                <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                  <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                  </svg>
                </div>
                <div>
                  <h5 class="font-medium text-black dark:text-white">{{ comp.name }}</h5>
                  <p class="text-sm text-gray-500 dark:text-gray-400">{{ comp.description }}</p>
                </div>
              </button>
            </div>
          </div>

          <!-- Page Components List -->
          <div class="space-y-4">
            <!-- Empty State -->
            <div v-if="pageComponents.length === 0" class="rounded-lg border-2 border-dashed border-stroke p-12 text-center dark:border-strokedark">
              <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
              </svg>
              <h3 class="mt-4 text-lg font-medium text-black dark:text-white">No components added yet</h3>
              <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Click "Add Component" above to start building your page.</p>
              
              <!-- Quick Add Buttons in Empty State -->
              <div class="mt-6">
                <p class="mb-3 text-sm font-medium text-black dark:text-white">Quick Add:</p>
                <div class="flex flex-wrap justify-center gap-2">
                  <button
                    v-for="comp in componentLibrary"
                    :key="comp.type"
                    type="button"
                    @click="addComponent(comp.type)"
                    class="inline-flex items-center rounded-md border border-primary bg-primary/10 px-3 py-1 text-sm font-medium text-primary hover:bg-primary hover:text-white transition-colors"
                  >
                    {{ comp.name }}
                  </button>
                </div>
              </div>
            </div>

            <!-- Dynamic Components with Drag & Drop -->
            <div
              v-for="(comp, index) in pageComponents"
              :key="comp.id"
              :draggable="true"
              @dragstart="startDrag(comp.id)"
              @dragover.prevent
              @drop="dropComponent(index)"
              class="group relative rounded-lg border-2 border-dashed border-stroke p-6 transition-all hover:border-primary dark:border-strokedark"
              :class="{
                'border-primary bg-primary/5': draggedComponent === comp.id,
                'border-stroke dark:border-strokedark': draggedComponent !== comp.id
              }"
            >
              <!-- Component Controls -->
              <div class="absolute -top-3 right-3 z-10 flex space-x-2 opacity-0 transition-opacity group-hover:opacity-100">
                <!-- Settings Button -->
                <button
                  type="button"
                  @click="openComponentSettings(comp)"
                  class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-white shadow-lg hover:bg-blue-700"
                  title="Component settings"
                >
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                </button>
                <!-- Drag Handle -->
                <button
                  type="button"
                  class="flex h-8 w-8 cursor-move items-center justify-center rounded-lg bg-gray-600 text-white shadow-lg hover:bg-gray-700"
                  title="Drag to reorder"
                >
                  <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                  </svg>
                </button>
                <!-- Delete Button -->
                <button
                  type="button"
                  @click="removeComponent(comp.id)"
                  class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-600 text-white shadow-lg hover:bg-red-700"
                  title="Delete component"
                >
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>
              </div>

              <!-- Component Header -->
              <div class="mb-4 flex items-center justify-between">
                <div class="flex items-center">
                  <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10">
                    <svg class="h-4 w-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                  </div>
                  <div>
                    <h4 class="font-medium text-black dark:text-white">{{ getComponentName(comp.type) }}</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Component #{{ index + 1 }}</p>
                  </div>
                </div>
                <div class="text-xs text-gray-400">
                  Order: {{ comp.order + 1 }}
                </div>
              </div>

              <!-- Component Preview -->
              <div class="overflow-hidden rounded-lg border border-stroke bg-white dark:border-strokedark dark:bg-boxdark">
                <component 
                  :is="getComponent(comp.type)" 
                  v-if="getComponent(comp.type)"
                  :component-data="getComponentDataForPreview(comp)"
                />
                <div v-else class="p-4 text-center text-gray-500">
                  Component "{{ comp.type }}" not found
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-between border-t border-stroke pt-6 dark:border-strokedark">
          <Link
            href="/dashboard/pages"
            class="inline-flex items-center rounded border border-stroke py-2 px-6 text-black hover:shadow-1 dark:border-strokedark dark:text-white"
          >
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Pages
          </Link>
          <button
            type="submit"
            :disabled="isSubmitting"
            class="inline-flex items-center rounded bg-primary py-2 px-6 text-white hover:bg-opacity-90 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
            :class="{ 'animate-pulse': isSubmitting }"
          >
            <!-- Loading Spinner -->
            <svg 
              v-if="isSubmitting" 
              class="mr-2 h-4 w-4 animate-spin" 
              fill="none" 
              viewBox="0 0 24 24"
            >
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <!-- Success/Default Icon -->
            <svg 
              v-else
              class="mr-2 h-4 w-4" 
              fill="none" 
              stroke="currentColor" 
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ isSubmitting ? 'Saving...' : (pageId ? 'Update Page' : 'Create Page') }}
          </button>
        </div>
      </form>
    </div>

    <!-- Component Settings Modal -->
    <div
      v-if="showComponentSettings"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
      @click.self="closeComponentSettings"
    >
      <div class="bg-white dark:bg-boxdark rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-stroke dark:border-strokedark">
          <h3 class="text-xl font-semibold text-black dark:text-white">
            {{ editingComponent?.type }} Settings
          </h3>
          <button
            @click="closeComponentSettings"
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
          >
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6">
          <!-- HeroCarousel Settings -->
          <div v-if="editingComponent?.type === 'HeroCarousel'">
            <h4 class="text-lg font-medium text-black dark:text-white mb-4">Hero Carousel Configuration</h4>
            
            <div class="space-y-4">
              <div
                v-for="(slide, index) in heroCarouselForm.slides"
                :key="slide.id"
                class="p-4 border border-stroke dark:border-strokedark rounded-lg"
              >
                <div class="flex items-center justify-between mb-3">
                  <h5 class="font-medium text-black dark:text-white">Slide {{ index + 1 }}</h5>
                  <button
                    v-if="heroCarouselForm.slides.length > 1"
                    @click="removeSlide(index)"
                    class="text-red-500 hover:text-red-700"
                  >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Title
                    </label>
                    <input
                      v-model="slide.title"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="Enter slide title"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Subtitle
                    </label>
                    <input
                      v-model="slide.subtitle"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="Enter slide subtitle"
                    />
                  </div>
                  
                  <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Description
                    </label>
                    <textarea
                      v-model="slide.description"
                      rows="3"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="Enter slide description"
                    ></textarea>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Image URL
                    </label>
                    <input
                      v-model="slide.image"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="/images/hero/slide1.jpg"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Fallback Gradient
                    </label>
                    <input
                      v-model="slide.fallbackGradient"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      CTA Text
                    </label>
                    <input
                      v-model="slide.ctaText"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="Learn More"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      CTA Link
                    </label>
                    <input
                      v-model="slide.ctaLink"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="/about"
                    />
                  </div>
                </div>
              </div>
            </div>
            
            <button
              @click="addSlide"
              class="mt-4 inline-flex items-center rounded bg-primary py-2 px-4 text-white hover:bg-opacity-90"
            >
              <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Add Slide
            </button>
          </div>

          <!-- HeadlineMarquee Settings -->
          <div v-if="editingComponent?.type === 'HeadlineMarquee'">
            <h4 class="text-lg font-medium text-black dark:text-white mb-4">Headlines Configuration</h4>
            
            <div class="space-y-4">
              <div
                v-for="(headline, index) in headlineMarqueeForm.headlines"
                :key="headline.id"
                class="p-4 border border-stroke dark:border-strokedark rounded-lg"
              >
                <div class="flex items-center justify-between mb-3">
                  <h5 class="font-medium text-black dark:text-white">Headline {{ index + 1 }}</h5>
                  <button
                    v-if="headlineMarqueeForm.headlines.length > 1"
                    @click="removeHeadline(index)"
                    class="text-red-500 hover:text-red-700"
                  >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Text
                    </label>
                    <input
                      v-model="headline.text"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="Enter headline text"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Link
                    </label>
                    <input
                      v-model="headline.link"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="Enter link URL"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Type
                    </label>
                    <select
                      v-model="headline.type"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                    >
                      <option value="announcement">Announcement</option>
                      <option value="news">News</option>
                      <option value="event">Event</option>
                      <option value="research">Research</option>
                      <option value="achievement">Achievement</option>
                    </select>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Priority
                    </label>
                    <select
                      v-model="headline.priority"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                    >
                      <option value="high">High</option>
                      <option value="medium">Medium</option>
                      <option value="low">Low</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            
            <button
              @click="addHeadline"
              class="mt-4 inline-flex items-center rounded bg-primary py-2 px-4 text-white hover:bg-opacity-90"
            >
              <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Add Headline
            </button>
          </div>

          <!-- MessageFrom Settings -->
          <div v-if="editingComponent?.type === 'MessageFrom'">
            <h4 class="text-lg font-medium text-black dark:text-white mb-4">Message From Configuration</h4>
            
            <div class="space-y-6">
              <!-- Basic Information -->
              <div class="p-4 border border-stroke dark:border-strokedark rounded-lg">
                <h5 class="font-medium text-black dark:text-white mb-3">Basic Information</h5>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Full Name
                    </label>
                    <input
                      v-model="messageFromForm.name"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="Enter full name"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Title/Position
                    </label>
                    <input
                      v-model="messageFromForm.title"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="e.g., Vice-Chancellor"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Department
                    </label>
                    <input
                      v-model="messageFromForm.department"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="Enter department"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      University/Organization
                    </label>
                    <input
                      v-model="messageFromForm.university"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="e.g., MBSTU"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Photo URL
                    </label>
                    <input
                      v-model="messageFromForm.photo"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="/images/faculty/person.jpg"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Experience
                    </label>
                    <input
                      v-model="messageFromForm.experience"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="e.g., 25+ years"
                    />
                  </div>
                </div>
              </div>

              <!-- Contact Information -->
              <div class="p-4 border border-stroke dark:border-strokedark rounded-lg">
                <h5 class="font-medium text-black dark:text-white mb-3">Contact Information</h5>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Email
                    </label>
                    <input
                      v-model="messageFromForm.email"
                      type="email"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="email@mbstu.ac.bd"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Phone
                    </label>
                    <input
                      v-model="messageFromForm.phone"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="+880921 55399"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Fax
                    </label>
                    <input
                      v-model="messageFromForm.fax"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="+880921 55400"
                    />
                  </div>
                  
                  <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Office Address
                    </label>
                    <input
                      v-model="messageFromForm.office"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="Vice Chancellor Office, MBSTU"
                    />
                  </div>
                </div>
              </div>

              <!-- Message Content -->
              <div class="p-4 border border-stroke dark:border-strokedark rounded-lg">
                <h5 class="font-medium text-black dark:text-white mb-3">Message Content</h5>
                
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Greeting/Title
                    </label>
                    <input
                      v-model="messageFromForm.greeting"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="Message from the Vice-Chancellor"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Subtitle
                    </label>
                    <input
                      v-model="messageFromForm.subtitle"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="Building Excellence in Science and Technology"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Message Title
                    </label>
                    <input
                      v-model="messageFromForm.messageTitle"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="Welcome to MBSTU"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Message Content
                    </label>
                    <textarea
                      v-model="messageFromForm.content"
                      rows="6"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="Enter the main message content..."
                    ></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- FacultiesCarousel Settings -->
          <div v-if="editingComponent?.type === 'FacultiesCarousel'">
            <h4 class="text-lg font-medium text-black dark:text-white mb-4">Faculties Carousel Configuration</h4>
            
            <div class="space-y-4">
              <div
                v-for="(faculty, index) in facultiesCarouselForm.faculties"
                :key="faculty.id"
                class="p-4 border border-stroke dark:border-strokedark rounded-lg"
              >
                <div class="flex items-center justify-between mb-3">
                  <h5 class="font-medium text-black dark:text-white">Faculty {{ index + 1 }}</h5>
                  <button
                    v-if="facultiesCarouselForm.faculties.length > 1"
                    @click="removeFaculty(index)"
                    class="text-red-500 hover:text-red-700"
                  >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
                
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Faculty Name
                    </label>
                    <input
                      v-model="faculty.name"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="Faculty of Engineering"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Short Name
                    </label>
                    <input
                      v-model="faculty.shortName"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="Engineering"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Image URL
                    </label>
                    <input
                      v-model="faculty.image"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="/images/faculties/engineering.jpg"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Link
                    </label>
                    <input
                      v-model="faculty.link"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="/faculty-of-engineering"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Established Year
                    </label>
                    <input
                      v-model="faculty.established"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="2002"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Ranking
                    </label>
                    <select
                      v-model="faculty.ranking"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                    >
                      <option value="Top Rated">Top Rated</option>
                      <option value="Excellence">Excellence</option>
                      <option value="Premier">Premier</option>
                      <option value="Growing">Growing</option>
                      <option value="Emerging">Emerging</option>
                      <option value="Cultural Hub">Cultural Hub</option>
                      <option value="Specialized">Specialized</option>
                    </select>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Icon
                    </label>
                    <select
                      v-model="faculty.icon"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                    >
                      <option value="Calculator">Calculator</option>
                      <option value="Microscope">Microscope</option>
                      <option value="Globe">Globe</option>
                      <option value="Briefcase">Briefcase</option>
                      <option value="Users">Users</option>
                      <option value="BookText">BookText</option>
                      <option value="Heart">Heart</option>
                    </select>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Featured
                    </label>
                    <input
                      v-model="faculty.featured"
                      type="checkbox"
                      class="rounded border border-stroke focus:border-primary focus-visible:outline-none dark:border-strokedark"
                    />
                  </div>
                </div>
                
                <!-- Description -->
                <div class="mb-4">
                  <label class="block text-sm font-medium text-black dark:text-white mb-2">
                    Description
                  </label>
                  <textarea
                    v-model="faculty.description"
                    rows="3"
                    class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                    placeholder="Faculty description..."
                  ></textarea>
                </div>
                
                <!-- Fallback Gradient -->
                <div class="mb-4">
                  <label class="block text-sm font-medium text-black dark:text-white mb-2">
                    Fallback Gradient
                  </label>
                  <input
                    v-model="faculty.fallbackGradient"
                    type="text"
                    class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                    placeholder="linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)"
                  />
                </div>
                
                <!-- Dean Information -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Dean Name
                    </label>
                    <input
                      v-model="faculty.dean.name"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="Prof. Dr. Name"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Dean Title
                    </label>
                    <input
                      v-model="faculty.dean.title"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="Dean, Faculty of Engineering"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Dean Email
                    </label>
                    <input
                      v-model="faculty.dean.email"
                      type="email"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="dean@mbstu.ac.bd"
                    />
                  </div>
                </div>
                
                <!-- Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Departments Count
                    </label>
                    <input
                      v-model.number="faculty.stats.departments"
                      type="number"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="4"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Students
                    </label>
                    <input
                      v-model="faculty.stats.students"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="1,250+"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Faculty
                    </label>
                    <input
                      v-model="faculty.stats.faculty"
                      type="text"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="48+"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                      Programs
                    </label>
                    <input
                      v-model.number="faculty.stats.programs"
                      type="number"
                      class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                      placeholder="12"
                    />
                  </div>
                </div>
                
                <!-- Departments List -->
                <div class="mb-4">
                  <label class="block text-sm font-medium text-black dark:text-white mb-2">
                    Departments (one per line)
                  </label>
                  <textarea
                    :value="faculty.departments?.join('\\n') || ''"
                    @input="updateDepartments(faculty, $event)"
                    rows="4"
                    class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                    placeholder="Computer Science and Engineering (CSE)&#10;Information and Communication Technology (ICT)&#10;..."
                  ></textarea>
                </div>
                
                <!-- Highlights List -->
                <div class="mb-4">
                  <label class="block text-sm font-medium text-black dark:text-white mb-2">
                    Highlights (one per line)
                  </label>
                  <textarea
                    :value="faculty.highlights?.join('\\n') || ''"
                    @input="updateHighlights(faculty, $event)"
                    rows="4"
                    class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                    placeholder="State-of-the-art laboratories&#10;Industry collaboration&#10;..."
                  ></textarea>
                </div>
                
                <!-- Research Areas List -->
                <div class="mb-4">
                  <label class="block text-sm font-medium text-black dark:text-white mb-2">
                    Research Areas (one per line)
                  </label>
                  <textarea
                    :value="faculty.researchAreas?.join('\\n') || ''"
                    @input="updateResearchAreas(faculty, $event)"
                    rows="4"
                    class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                    placeholder="Artificial Intelligence & Machine Learning&#10;Robotics & Automation&#10;..."
                  ></textarea>
                </div>
                
                <!-- Achievements List -->
                <div class="mb-4">
                  <label class="block text-sm font-medium text-black dark:text-white mb-2">
                    Achievements (one per line)
                  </label>
                  <textarea
                    :value="faculty.achievements?.join('\\n') || ''"
                    @input="updateAchievements(faculty, $event)"
                    rows="4"
                    class="w-full rounded border border-stroke bg-gray py-3 px-4 text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                    placeholder="15+ International Research Publications (2024)&#10;IEEE Student Branch Chapter&#10;..."
                  ></textarea>
                </div>
              </div>
            </div>
            
            <button
              @click="addFaculty"
              class="mt-4 inline-flex items-center rounded bg-primary py-2 px-4 text-white hover:bg-opacity-90"
            >
              <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Add Faculty
            </button>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-stroke dark:border-strokedark">
          <button
            @click="closeComponentSettings"
            class="inline-flex items-center rounded border border-stroke py-2 px-4 text-black hover:shadow-1 dark:border-strokedark dark:text-white"
          >
            Cancel
          </button>
          <button
            @click="saveComponentSettings"
            :disabled="isSavingSettings"
            class="inline-flex items-center rounded bg-primary py-2 px-4 text-white hover:bg-opacity-90 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
            :class="{ 'animate-pulse': isSavingSettings }"
          >
            <!-- Loading Spinner -->
            <svg 
              v-if="isSavingSettings" 
              class="mr-2 h-4 w-4 animate-spin" 
              fill="none" 
              viewBox="0 0 24 24"
            >
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <!-- Save Icon -->
            <svg 
              v-else
              class="mr-2 h-4 w-4" 
              fill="none" 
              stroke="currentColor" 
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ isSavingSettings ? 'Saving...' : 'Save Settings' }}
          </button>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import HeroCarousel from '@/components/user/HeroCarousel.vue'
import HeadlineMarquee from '@/components/user/HeadlineMarquee.vue'
import MessageFrom from '@/components/user/MessageFrom.vue'
import FacultiesCarousel from '@/components/user/FacultiesCarousel.vue'

// Props
const props = defineProps<{
  pageId?: string | number
}>()

// Form data
const form = ref({
  title: '',
  slug: ''
})

// Page builder data
const pageComponents = ref<Array<{id: string, type: string, order: number}>>([])
const showComponentLibrary = ref(false)
const draggedComponent = ref<string | null>(null)
const isSubmitting = ref(false)
const showComponentSettings = ref(false)
const editingComponent = ref<any>(null)
const isSavingSettings = ref(false)

// Component settings data
const componentSettings = ref<Record<string, any>>({})

// HeroCarousel settings form
const heroCarouselForm = ref({
  slides: [
    {
      id: 1,
      title: 'Welcome to Computer Science and Engineering',
      subtitle: 'Department of CSE, MBSTU',
      description: 'Empowering the next generation of computer scientists and engineers with cutting-edge education and research opportunities.',
      image: '/images/carousel/slide1.jpg',
      fallbackGradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)',
      ctaText: 'Learn More',
      ctaLink: '/about'
    }
  ]
})

// HeadlineMarquee settings form
const headlineMarqueeForm = ref({
  headlines: [
    {
      id: 1,
      type: 'announcement',
      text: '',
      link: '',
      priority: 'medium' as 'high' | 'medium' | 'low'
    }
  ]
})

// MessageFrom settings form
const messageFromForm = ref({
  name: 'Professor Dr. Md. Anwarul Azim Akhand',
  title: 'Vice-Chancellor',
  department: 'Mawlana Bhashani Science and Technology University',
  university: 'MBSTU',
  photo: '/images/faculty/vc.jpg',
  email: 'vc@mbstu.ac.bd',
  phone: '+880921 55399',
  fax: '+880921 55400',
  office: 'Vice Chancellor Office, MBSTU',
  experience: '25+ years',
  greeting: 'Message from Chairman',
  subtitle: 'A warm welcome from the leadership of our Computer Science department.',
  messageTitle: 'Welcome to the Department of Computer Science and Engineering',
  content: `It gives me immense pleasure to welcome you to the Department of Computer Science and Engineering at Mawlana Bhashani Science and Technology University. Our department has been at the forefront of technological innovation and academic excellence since its establishment.`
})

// FacultiesCarousel settings form
const facultiesCarouselForm = ref({
  faculties: [
    {
      id: 1,
      name: 'Faculty of Engineering',
      shortName: 'Engineering',
      description: 'Leading innovation in technology and engineering with cutting-edge research, industry partnerships, and world-class laboratories.',
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
      icon: 'Calculator'
    }
  ]
})

// Auto-show component library when no components exist
watch(() => pageComponents.value.length, (newLength) => {
  if (newLength === 0) {
    showComponentLibrary.value = true
  }
}, { immediate: true })

// Available components library
const componentLibrary = [
  { 
    type: 'HeroCarousel', 
    name: 'Hero Carousel', 
    description: 'Image carousel for hero sections',
    component: HeroCarousel 
  },
  { 
    type: 'HeadlineMarquee', 
    name: 'Headline Marquee', 
    description: 'Scrolling text for announcements',
    component: HeadlineMarquee 
  },
  { 
    type: 'MessageFrom', 
    name: 'Message From', 
    description: 'Message from leadership section',
    component: MessageFrom 
  },
  { 
    type: 'FacultiesCarousel', 
    name: 'Faculties Carousel', 
    description: 'Faculty members showcase carousel',
    component: FacultiesCarousel 
  }
]

// Auto-generate slug from title
watch(() => form.value.title, (newTitle) => {
  if (newTitle && !props.pageId) {
    form.value.slug = newTitle
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/(^-|-$)/g, '')
  }
})

// Watch for pageId changes and reload data
watch(() => props.pageId, (newPageId) => {
  try {
    if (newPageId) {
      console.log('PageId changed to:', newPageId)
      loadPageData()
    }
  } catch (error) {
    console.error('Error in pageId watcher:', error)
  }
}, { immediate: true })

// Page Builder Methods
const addComponent = (type: string) => {
  const newComponent = {
    id: `${type}-${Date.now()}`,
    type: type,
    order: pageComponents.value.length
  }
  pageComponents.value.push(newComponent)
  showComponentLibrary.value = false
}

const removeComponent = (id: string) => {
  pageComponents.value = pageComponents.value.filter(comp => comp.id !== id)
  // Reorder remaining components to maintain proper sequence
  pageComponents.value.forEach((comp, index) => {
    comp.order = index
  })
  console.log('Component removed, remaining components:', pageComponents.value.map(c => ({ id: c.id, type: c.type, order: c.order })))
}

const startDrag = (id: string) => {
  draggedComponent.value = id
}

const dropComponent = (targetIndex: number) => {
  if (!draggedComponent.value) return
  
  const draggedIndex = pageComponents.value.findIndex(comp => comp.id === draggedComponent.value)
  if (draggedIndex === -1) return
  
  // Remove dragged component and insert at new position
  const [draggedComp] = pageComponents.value.splice(draggedIndex, 1)
  pageComponents.value.splice(targetIndex, 0, draggedComp)
  
  // Update order values to maintain proper sequence
  pageComponents.value.forEach((comp, index) => {
    comp.order = index
  })
  
  console.log('Components reordered:', pageComponents.value.map(c => ({ id: c.id, type: c.type, order: c.order })))
  
  draggedComponent.value = null
}

const getComponent = (type: string) => {
  try {
    const comp = componentLibrary.find(c => c.type === type)
    return comp ? comp.component : null
  } catch (error) {
    console.error('Error getting component:', type, error)
    return null
  }
}

const getComponentName = (type: string) => {
  const comp = componentLibrary.find(c => c.type === type)
  return comp ? comp.name : type
}

// Get component data with settings for preview
const getComponentDataForPreview = (component: any) => {
  const baseData = { ...component }
  
  // If there are component settings for this component, merge them
  if (componentSettings.value && componentSettings.value[component.id]) {
    const settings = componentSettings.value[component.id]
    
    console.log(`Preview loading settings for ${component.type} (${component.id}):`, settings)
    
    // For HeroCarousel, pass slides as props
    if (component.type === 'HeroCarousel' && settings.slides) {
      baseData.slides = settings.slides
      console.log('Preview HeroCarousel slides loaded:', settings.slides)
    }
    
    // For HeadlineMarquee, pass headlines as props
    if (component.type === 'HeadlineMarquee' && settings.headlines) {
      baseData.headlines = settings.headlines
      console.log('Preview HeadlineMarquee headlines loaded:', settings.headlines)
    }
    
    // For MessageFrom, pass all message data as props
    if (component.type === 'MessageFrom' && settings) {
      baseData.messageData = settings
      console.log('Preview MessageFrom data loaded:', settings)
    }
    
    // For FacultiesCarousel, pass faculties as props
    if (component.type === 'FacultiesCarousel' && settings.faculties) {
      baseData.faculties = settings.faculties
      console.log('Preview FacultiesCarousel faculties loaded:', settings.faculties)
    }
    
    // Add other component-specific settings here as needed
  } else {
    console.log(`Preview: No settings found for ${component.type} (${component.id})`)
  }
  
  return baseData
}

// Component settings functions
const openComponentSettings = (component: any) => {
  editingComponent.value = component
  
  // Load existing settings for the component
  if (componentSettings.value[component.id]) {
    if (component.type === 'HeroCarousel') {
      heroCarouselForm.value = { ...componentSettings.value[component.id] }
    } else if (component.type === 'HeadlineMarquee') {
      headlineMarqueeForm.value = { ...componentSettings.value[component.id] }
    } else if (component.type === 'MessageFrom') {
      messageFromForm.value = { ...componentSettings.value[component.id] }
    } else if (component.type === 'FacultiesCarousel') {
      facultiesCarouselForm.value = { ...componentSettings.value[component.id] }
    }
  } else {
    // Initialize with default values
    if (component.type === 'HeroCarousel') {
      heroCarouselForm.value = {
        slides: [
          {
            id: 1,
            title: 'Welcome to MBSTU',
            subtitle: 'Excellence in Science and Technology',
            description: 'Mawlana Bhashani Science and Technology University is a leading institution dedicated to advancing knowledge and innovation.',
            image: '/images/hero/campus.jpg',
            fallbackGradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)',
            ctaText: 'Learn More',
            ctaLink: '/about'
          }
        ]
      }
    } else if (component.type === 'HeadlineMarquee') {
      headlineMarqueeForm.value = {
        headlines: [
          {
            id: 1,
            type: 'announcement',
            text: '',
            link: '',
            priority: 'medium' as 'high' | 'medium' | 'low'
          }
        ]
      }
    } else if (component.type === 'MessageFrom') {
      messageFromForm.value = {
        name: 'Professor Dr. Md. Anwarul Azim Akhand',
        title: 'Vice-Chancellor',
        department: 'Mawlana Bhashani Science and Technology University',
        university: 'MBSTU',
        photo: '/images/faculty/vc.jpg',
        email: 'vc@mbstu.ac.bd',
        phone: '+880921 55399',
        fax: '+880921 55400',
        office: 'Vice Chancellor Office, MBSTU',
        experience: '25+ years',
        greeting: 'Message from the Vice-Chancellor',
        subtitle: 'Building Excellence in Science and Technology',
        messageTitle: 'Welcome to MBSTU',
        content: `Welcome to Mawlana Bhashani Science and Technology University (MBSTU), a leading center for academic excellence and research in Bangladesh.`
      }
    } else if (component.type === 'FacultiesCarousel') {
      facultiesCarouselForm.value = {
        faculties: [
          {
            id: 1,
            name: 'Faculty of Engineering',
            shortName: 'Engineering',
            description: 'Leading innovation in technology and engineering with cutting-edge research, industry partnerships, and world-class laboratories.',
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
            icon: 'Calculator'
          }
        ]
      }
    }
  }
  
  showComponentSettings.value = true
}

const closeComponentSettings = () => {
  showComponentSettings.value = false
  editingComponent.value = null
}

const addSlide = () => {
  const newId = Math.max(...heroCarouselForm.value.slides.map(s => s.id), 0) + 1
  heroCarouselForm.value.slides.push({
    id: newId,
    title: '',
    subtitle: '',
    description: '',
    image: '',
    fallbackGradient: '',
    ctaText: '',
    ctaLink: ''
  })
}

const removeSlide = (index: number) => {
  if (heroCarouselForm.value.slides.length > 1) {
    heroCarouselForm.value.slides.splice(index, 1)
  }
}

const addHeadline = () => {
  const newId = Math.max(...headlineMarqueeForm.value.headlines.map(h => h.id), 0) + 1
  headlineMarqueeForm.value.headlines.push({
    id: newId,
    type: 'announcement',
    text: '',
    link: '',
    priority: 'medium'
  })
}

const removeHeadline = (index: number) => {
  if (headlineMarqueeForm.value.headlines.length > 1) {
    headlineMarqueeForm.value.headlines.splice(index, 1)
  }
}

const addFaculty = () => {
  facultiesCarouselForm.value.faculties.push({
    id: Date.now(),
    name: '',
    shortName: '',
    description: '',
    image: '',
    fallbackGradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)',
    link: '',
    departments: [],
    stats: {
      departments: 0,
      students: '',
      faculty: '',
      programs: 0
    },
    highlights: [],
    researchAreas: [],
    achievements: [],
    featured: false,
    established: '',
    ranking: 'Premier',
    dean: {
      name: '',
      title: '',
      email: ''
    },
    icon: 'Calculator'
  })
}

const removeFaculty = (index: number) => {
  if (facultiesCarouselForm.value.faculties.length > 1) {
    facultiesCarouselForm.value.faculties.splice(index, 1)
  }
}

// Helper functions for array fields
const updateDepartments = (faculty: any, event: any) => {
  faculty.departments = event.target.value.split('\n').filter((item: string) => item.trim())
}

const updateHighlights = (faculty: any, event: any) => {
  faculty.highlights = event.target.value.split('\n').filter((item: string) => item.trim())
}

const updateResearchAreas = (faculty: any, event: any) => {
  faculty.researchAreas = event.target.value.split('\n').filter((item: string) => item.trim())
}

const updateAchievements = (faculty: any, event: any) => {
  faculty.achievements = event.target.value.split('\n').filter((item: string) => item.trim())
}

const saveComponentSettings = async () => {
  if (!editingComponent.value || !props.pageId) {
    console.error('Missing required data:', { 
      editingComponent: editingComponent.value, 
      pageId: props.pageId 
    })
    return
  }
  
  console.log('Starting save with pageId:', props.pageId)
  console.log('Editing component:', editingComponent.value)
  
  isSavingSettings.value = true
  
  try {
    // Save settings for the component
    if (editingComponent.value.type === 'HeroCarousel') {
      componentSettings.value[editingComponent.value.id] = { ...heroCarouselForm.value }
    } else if (editingComponent.value.type === 'HeadlineMarquee') {
      componentSettings.value[editingComponent.value.id] = { ...headlineMarqueeForm.value }
    } else if (editingComponent.value.type === 'MessageFrom') {
      componentSettings.value[editingComponent.value.id] = { ...messageFromForm.value }
    } else if (editingComponent.value.type === 'FacultiesCarousel') {
      componentSettings.value[editingComponent.value.id] = { ...facultiesCarouselForm.value }
    }
    
    console.log('Saving component settings:', componentSettings.value)
    console.log('Current form data:', form.value)
    console.log('Current components:', pageComponents.value)
    
    // Prepare form data with current page data and updated component settings
    const formData = {
      title: form.value.title,
      slug: form.value.slug,
      components: pageComponents.value,
      componentSettings: componentSettings.value
    }
    
    console.log('Sending form data:', formData)
    
    // Save to backend
    const response = await fetch(`/api/pages/${props.pageId}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify(formData)
    })
    
    console.log('Response status:', response.status)
    console.log('Response headers:', response.headers)
    
    if (response.ok) {
      const updatedPage = await response.json()
      console.log('Component settings saved successfully')
      console.log('Updated page response:', updatedPage)
      
      // Update component settings with response
      if (updatedPage.componentSettings) {
        componentSettings.value = updatedPage.componentSettings
        console.log('Updated componentSettings from response:', updatedPage.componentSettings)
      }
      
      closeComponentSettings()
    } else {
      const errorText = await response.text()
      console.error('Failed to save component settings:', response.status, errorText)
      console.error('Response headers:', Object.fromEntries(response.headers.entries()))
      alert(`Failed to save component settings: ${response.status} - ${errorText}`)
    }
  } catch (error) {
    console.error('Error saving component settings:', error)
    alert('An error occurred while saving. Please try again.')
  } finally {
    isSavingSettings.value = false
  }
}

// Form submission
const submitForm = async () => {
  if (isSubmitting.value) return // Prevent double submission
  
  isSubmitting.value = true
  
  try {
    console.log('Submitting form:', form.value)
    console.log('Page components:', pageComponents.value)
    
    const formData = {
      ...form.value,
      components: pageComponents.value,
      componentSettings: componentSettings.value
    }
    
    if (props.pageId) {
      // Update existing page
      const response = await fetch(`/api/pages/${props.pageId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify(formData)
      })
      
      if (response.ok) {
        const updatedPage = await response.json()
        console.log('Page updated successfully')
        
        // Update form data with response (in case server modified anything)
        form.value.title = updatedPage.title || form.value.title
        form.value.slug = updatedPage.slug || form.value.slug
        
        // Update components if returned
        if (updatedPage.components) {
          pageComponents.value = updatedPage.components
        }
        
        // Show success message (you can add a toast notification here)
        console.log('Staying on edit page after successful update')
      } else {
        const errorText = await response.text()
        console.error('Failed to update page:', response.status, errorText)
      }
    } else {
      // Create new page
      const response = await fetch('/api/pages', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify(formData)
      })
      
      if (response.ok) {
        const newPage = await response.json()
        console.log('Page created successfully')
        // Redirect to edit page for the newly created page
        router.visit(`/dashboard/pages/${newPage.id}/edit`)
      } else {
        const errorText = await response.text()
        console.error('Failed to create page:', response.status, errorText)
      }
    }
  } catch (error) {
    console.error('Error submitting form:', error)
  } finally {
    isSubmitting.value = false
  }
}

// Load page data if editing
const loadPageData = async () => {
  if (!props.pageId) {
    console.log('No pageId provided, skipping data load')
    return
  }
  
  try {
    console.log('Loading page data for ID:', props.pageId)
    const response = await fetch(`/api/pages/${props.pageId}`)
    console.log('Load page response status:', response.status)
    
    if (response.ok) {
      const page = await response.json()
      
      form.value.title = page.title || ''
      form.value.slug = page.slug || ''
      
      // Load components if they exist
      if (page.components && Array.isArray(page.components)) {
        pageComponents.value = page.components
      } else {
        pageComponents.value = []
      }
      
      // Load component settings if they exist and are an object
      if (page.componentSettings && typeof page.componentSettings === 'object' && !Array.isArray(page.componentSettings)) {
        componentSettings.value = page.componentSettings
      } else {
        componentSettings.value = {}
      }
      
      console.log('Loaded componentSettings:', componentSettings.value)
    } else {
      const errorText = await response.text()
      console.error('Failed to load page:', response.status, errorText)
    }
  } catch (error) {
    console.error('Error loading page data:', error)
  }
}

// Lifecycle
onMounted(() => {
  console.log('PageEditor mounted with pageId:', props.pageId)
  // Also try loading data on mount as backup
  if (props.pageId) {
    console.log('Calling loadPageData from onMounted')
    loadPageData()
  }
})
</script>
