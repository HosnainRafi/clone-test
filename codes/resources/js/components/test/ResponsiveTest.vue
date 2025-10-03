<template>
  <div class="p-4 border-2 border-dashed border-gray-300 m-4">
    <h2 class="text-xl font-bold mb-4">Responsive Display Test</h2>
    
    <!-- Test 1: lg:hidden -->
    <div class="mb-4 p-3 bg-red-100 lg:hidden">
      <strong>Mobile Only:</strong> This red box should be visible on screens smaller than 1024px (mobile/tablet) and hidden on desktop.
    </div>
    
    <!-- Test 2: hidden lg:block -->
    <div class="mb-4 p-3 bg-green-100 hidden lg:block">
      <strong>Desktop Only:</strong> This green box should be hidden on mobile/tablet and visible only on screens 1024px and larger.
    </div>
    
    <!-- Test 3: Responsive text -->
    <div class="mb-4 p-3 bg-blue-100">
      <span class="lg:hidden">📱 Mobile View (< 1024px)</span>
      <span class="hidden lg:inline">🖥️ Desktop View (≥ 1024px)</span>
    </div>
    
    <!-- Test 4: Responsive navigation simulation -->
    <div class="border-t pt-4">
      <h3 class="font-semibold mb-2">Navigation Example:</h3>
      
      <!-- Mobile menu button -->
      <button class="lg:hidden bg-blue-500 text-white px-4 py-2 rounded">
        Menu Button (Mobile Only)
      </button>
      
      <!-- Desktop navigation -->
      <nav class="hidden lg:block">
        <div class="flex space-x-4">
          <a href="#" class="text-blue-600 hover:underline">Home</a>
          <a href="#" class="text-blue-600 hover:underline">About</a>
          <a href="#" class="text-blue-600 hover:underline">Contact</a>
        </div>
      </nav>
    </div>
    
    <!-- Current screen size indicator -->
    <div class="mt-4 p-2 bg-yellow-100 text-sm">
      <strong>Current breakpoint:</strong>
      <span class="lg:hidden">Below lg (< 1024px)</span>
      <span class="hidden lg:inline">lg or above (≥ 1024px)</span>
    </div>
  </div>
</template>

<script setup lang="ts">
// This component helps test responsive utilities
</script>