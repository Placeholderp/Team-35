<template>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="p-6 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-medium text-gray-900">Order Notes</h2>
        <button 
          @click="isAddingNote = !isAddingNote"
          class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150"
        >
          <svg 
            v-if="!isAddingNote" 
            xmlns="http://www.w3.org/2000/svg" 
            class="h-4 w-4 mr-1" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <svg 
            v-else 
            xmlns="http://www.w3.org/2000/svg" 
            class="h-4 w-4 mr-1" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          {{ isAddingNote ? 'Cancel' : 'Add Note' }}
        </button>
      </div>
      
      <!-- Add Note Form -->
      <div v-if="isAddingNote" class="p-6 bg-gray-50 border-b border-gray-200">
        <form @submit.prevent="addNote">
          <div class="mb-4">
            <label for="note_type" class="block text-sm font-medium text-gray-700 mb-1">Note Type</label>
            <select 
              id="note_type"
              v-model="newNote.type"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              required
            >
              <option value="internal">Internal Note</option>
              <option value="customer">Customer Note</option>
              <option value="system">System Note</option>
            </select>
          </div>
          
          <div class="mb-4">
            <label for="note_content" class="block text-sm font-medium text-gray-700 mb-1">Note</label>
            <textarea 
              id="note_content"
              v-model="newNote.content"
              rows="3"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              placeholder="Add your note here..."
              required
            ></textarea>
          </div>
          
          <div class="flex justify-end">
            <button 
              type="submit"
              :disabled="isSubmitting"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <svg 
                v-if="isSubmitting" 
                class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" 
                xmlns="http://www.w3.org/2000/svg" 
                fill="none" 
                viewBox="0 0 24 24"
              >
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Save Note
            </button>
          </div>
        </form>
      </div>
      
      <!-- Notes List -->
      <div class="p-6">
        <div v-if="loading" class="flex justify-center p-6">
          <svg class="animate-spin h-8 w-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>
        
        <div v-else-if="notes.length === 0" class="text-center py-8">
          <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">No notes</h3>
          <p class="mt-1 text-sm text-gray-500">Get started by creating a new note.</p>
        </div>
        
        <ul v-else class="space-y-4">
          <li v-for="note in notes" :key="note.id" class="bg-white border rounded-md overflow-hidden">
            <div class="px-4 py-3 flex items-center justify-between" :class="getNoteHeaderClass(note.type)">
              <div class="flex items-center">
                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full" :class="getNoteIconClass(note.type)">
                  <!-- Note type icon (internal/customer/system) -->
                  <!-- SVG icons here -->
                </span>
                <span class="ml-2 font-medium">{{ getNoteTypeLabel(note.type) }}</span>
              </div>
              <span class="text-sm">{{ formatDate(note.created_at) }}</span>
            </div>
            <div class="px-4 py-3 border-t border-gray-200">
              <p class="text-sm text-gray-700">{{ note.content }}</p>
            </div>
            <div class="px-4 py-2 bg-gray-50 border-t border-gray-200 text-xs text-gray-500">
              {{ note.author || 'System' }}
            </div>
          </li>
        </ul>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useStore } from 'vuex';
  
  const props = defineProps({
    orderId: {
      type: [Number, String],
      required: true
    }
  });
  
  const store = useStore();
  const loading = ref(false);
  const isAddingNote = ref(false);
  const isSubmitting = ref(false);
  const notes = ref([]);
  const newNote = ref({
    type: 'internal',
    content: '',
    author: store.state.user?.name || 'Admin'
  });
  
  // Fetch notes on component mount
  onMounted(async () => {
    await fetchNotes();
  });
  
  async function fetchNotes() {
    loading.value = true;
    try {
      // In a real app, this would be an API call
      // const { data } = await axios.get(`/orders/${props.orderId}/notes`);
      // notes.value = data;
      
      // Simulated data for demonstration
      setTimeout(() => {
        notes.value = [
          {
            id: 1,
            type: 'system',
            content: 'Order status changed from "pending" to "processing"',
            created_at: new Date(new Date().getTime() - 86400000 * 2), // 2 days ago
            author: 'System'
          },
          {
            id: 2,
            type: 'internal',
            content: 'Customer called to ask about delivery timeframes. Informed them to expect delivery within 3-5 business days.',
            created_at: new Date(new Date().getTime() - 86400000), // 1 day ago
            author: 'John Doe'
          },
          {
            id: 3,
            type: 'customer',
            content: 'Please deliver to side entrance, there will be someone there between 9am-5pm.',
            created_at: new Date(),
            author: 'Customer'
          }
        ];
        loading.value = false;
      }, 500);
    } catch (error) {
      console.error('Error fetching order notes:', error);
      store.commit('showToast', {
        message: 'Failed to load order notes',
        type: 'error'
      });
      loading.value = false;
    }
  }
  
  async function addNote() {
    if (!newNote.value.content.trim()) return;
    
    isSubmitting.value = true;
    try {
      // In a real app, this would be an API call
      // await axios.post(`/orders/${props.orderId}/notes`, newNote.value);
      
      // Simulated response for demonstration
      setTimeout(() => {
        // Add note to the list (in a real app, you'd use the data from the API response)
        notes.value.unshift({
          id: Date.now(), // Use timestamp as temp ID
          ...newNote.value,
          created_at: new Date()
        });
        
        // Reset form
        newNote.value.content = '';
        isAddingNote.value = false;
        isSubmitting.value = false;
        
        store.commit('showToast', {
          message: 'Note added successfully',
          type: 'success'
        });
      }, 500);
    } catch (error) {
      console.error('Error adding note:', error);
      store.commit('showToast', {
        message: 'Failed to add note',
        type: 'error'
      });
      isSubmitting.value = false;
    }
  }
  
  function formatDate(date) {
    if (!date) return '';
    
    const d = new Date(date);
    const now = new Date();
    const diffTime = Math.abs(now - d);
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 0) {
      return 'Today, ' + d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    } else if (diffDays === 1) {
      return 'Yesterday, ' + d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    } else {
      return d.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' }) + 
             ', ' + d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }
  }
  
  function getNoteTypeLabel(type) {
    const labels = {
      'internal': 'Internal Note',
      'customer': 'Customer Note',
      'system': 'System Note'
    };
    
    return labels[type] || 'Note';
  }
  
  function getNoteHeaderClass(type) {
    const classes = {
      'internal': 'bg-indigo-50 text-indigo-800',
      'customer': 'bg-green-50 text-green-800',
      'system': 'bg-gray-50 text-gray-800'
    };
    
    return classes[type] || 'bg-gray-50 text-gray-800';
  }
  
  function getNoteIconClass(type) {
    const classes = {
      'internal': 'bg-indigo-100 text-indigo-600',
      'customer': 'bg-green-100 text-green-600',
      'system': 'bg-gray-100 text-gray-600'
    };
    
    return classes[type] || 'bg-gray-100 text-gray-600';
  }
  </script>