<template>
  <div class="container" :class="containerClass">
    <header>
      <h1>{{ title }}</h1>
    </header>
    <div class="card">
      <p>{{ welcomeMessage }}</p>
      <ul>
        <li v-for="(item, index) in listItems" :key="index">{{ item }}</li>
      </ul>
      <div class="debug-info">
        <p>Current Domain Type: <strong>{{ subdomainType }}</strong></p>
        <p>Full Domain: <strong>{{ fullDomain }}</strong></p>
        <div class="json-display">
          <h4>Site Data (JSON):</h4>
          <pre><code>{{ JSON.stringify(siteData, null, 2) }}</code></pre>
        </div>
      </div>
      <button @click="incrementCount" class="btn" :class="buttonClass">
        Count: {{ count }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

// Get configuration from Laravel via window.appConfig
const subdomainType = ref('default');
const fullDomain = ref('mbstu.localhost');
const siteData = ref('site.data');

onMounted(() => {
  // Use the data passed from Laravel
  if (window.appConfig) {
    if (window.appConfig.subdomainType) {
      subdomainType.value = window.appConfig.subdomainType;
    }
    if (window.appConfig.fullDomain) {
      fullDomain.value = window.appConfig.fullDomain;
    }
    if (window.appConfig.siteData) {
      siteData.value = window.appConfig.siteData;
    }
  }
});

// Computed property to safely display site data as JSON string
const displaySiteData = computed(() => {
  try {
    return siteData.value ? siteData.value.toString() : 'No site data available';
  } catch (error) {
    return `Error displaying site data: ${error.message}`;
  }
});

// Reactive properties based on subdomain
const title = computed(() => {
  switch (subdomainType.value) {
    case 'ict':
      return 'MBSTU ICT Department';
    case 'cse':
      return 'MBSTU CSE Department';
    default:
      return 'MBSTU Home Page';
  }
});

const welcomeMessage = computed(() => {
  switch (subdomainType.value) {
    case 'ict':
      return 'Welcome to Information & Communication Technology Department';
    case 'cse':
      return 'Welcome to Computer Science & Engineering Department';
    default:
      return 'Welcome to Mawlana Bhashani Science and Technology University';
  }
});

const listItems = computed(() => {
  switch (subdomainType.value) {
    case 'ict':
      return [
        'Networking and Communication',
        'Information Systems',
        'Digital Innovation'
      ];
    case 'cse':
      return [
        'Software Engineering',
        'Computer Architecture',
        'AI and Machine Learning'
      ];
    default:
      return [
        'Excellence in Education',
        'Research Innovation',
        'Modern Campus'
      ];
  }
});

const containerClass = computed(() => {
  switch (subdomainType.value) {
    case 'ict':
      return 'ict-theme';
    case 'cse':
      return 'cse-theme';
    default:
      return 'default-theme';
  }
});

const buttonClass = computed(() => {
  switch (subdomainType.value) {
    case 'ict':
      return 'btn-ict';
    case 'cse':
      return 'btn-cse';
    default:
      return '';
  }
});

const count = ref(0);

const incrementCount = () => {
  count.value++;
};
</script>

<style scoped>
.container {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
  text-align: center;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

/* Theme-specific container backgrounds */
.default-theme {
  background-color: #fdfdfc;
}

.ict-theme {
  background-color: #ffebeb; /* Light red */
}

.cse-theme {
  background-color: #ebffeb; /* Light green */
}

header {
  width: 100%;
  margin-bottom: 2rem;
}

h1 {
  font-size: 2.5rem;
  margin-bottom: 1rem;
  font-weight: 600;
}

/* Theme-specific header colors */
.default-theme h1 {
  color: #4F46E5;
}

.ict-theme h1 {
  color: #E53E3E; /* Red */
}

.cse-theme h1 {
  color: #38A169; /* Green */
}

.card {
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  padding: 1.5rem;
  margin-top: 1rem;
  width: 100%;
}

ul {
  list-style-type: none;
  padding: 0;
  margin: 1.5rem 0;
}

li {
  margin: 0.5rem 0;
  font-size: 1.1rem;
}

li:before {
  content: "✅ ";
}

/* Theme-specific list item markers */
.ict-theme li:before {
  content: "🔴 ";
}

.cse-theme li:before {
  content: "🟢 ";
}

.btn {
  background-color: #4F46E5;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s;
  margin-top: 1rem;
}

.btn:hover {
  background-color: #4338CA;
}

/* Theme-specific buttons */
.btn-ict {
  background-color: #E53E3E;
}

.btn-ict:hover {
  background-color: #C53030;
}

.btn-cse {
  background-color: #38A169;
}

.btn-cse:hover {
  background-color: #2F855A;
}

.debug-info {
  margin-top: 1.5rem;
  padding: 0.75rem;
  background-color: #f8f9fa;
}
</style>