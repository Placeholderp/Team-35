<template>
  <div>
    <label v-if="type !== 'checkbox'" :for="id" class="block text-sm font-medium text-gray-700 mb-1">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <div class="mt-1 flex rounded-md shadow-sm">
      <span v-if="prepend"
            class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
        {{ prepend }}
      </span>
      <template v-if="type === 'select'">
        <select :id="id"
                :name="name"
                :required="required"
                :disabled="disabled"
                :value="props.modelValue"
                :class="inputClasses"
                @change="onChange($event.target.value)">
          <option v-if="placeholder" value="" disabled selected>{{ placeholder }}</option>
          <option v-for="option of selectOptions" :key="option.key" :value="option.key">{{option.text}}</option>
        </select>
      </template>
      <template v-else-if="type === 'textarea'">
      <textarea :id="id"
                :name="name"
                :required="required"
                :disabled="disabled"
                :value="props.modelValue"
                :rows="rows || 4"
                @input="emit('update:modelValue', $event.target.value)"
                :class="inputClasses"
                :placeholder="placeholder || label"></textarea>
      </template>
      <template v-else-if="type === 'file'">
        <div class="relative">
          <input :id="id"
                 :name="name"
                 :type="type"
                 :required="required"
                 :disabled="disabled"
                 :accept="accept"
                 class="sr-only"
                 ref="fileInput"
                 @input="onFileInput($event)"
                 @change="onFileChange($event)"
                 :placeholder="placeholder || label"/>
          <label :for="id"
                 class="cursor-pointer px-3 py-2 rounded-md border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
            </svg>
            {{ fileButtonText || 'Choose file' }}
          </label>
          <span v-if="fileName" class="ml-2 text-sm text-gray-500">{{ fileName }}</span>
          <button v-if="fileName" 
                  type="button" 
                  class="ml-1 text-gray-400 hover:text-red-500"
                  @click="clearFile">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </template>
      <template v-else-if="type === 'checkbox'">
        <div class="flex items-center h-5">
          <input :id="id"
                 :name="name"
                 :type="type"
                 :checked="Boolean(props.modelValue)"
                 :required="required"
                 :disabled="disabled"
                 @change="emit('update:modelValue', $event.target.checked)"
                 class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"/>
          <label :for="id" class="ml-2 block text-sm text-gray-900">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
          </label>
        </div>
      </template>
      <template v-else>
        <input :id="id"
               :name="name"
               :type="type"
               :required="required"
               :disabled="disabled"
               :min="min"
               :max="max"
               :value="props.modelValue"
               @input="emit('update:modelValue', $event.target.value)"
               :class="inputClasses"
               :placeholder="placeholder || label"
               :step="step"/>
      </template>
      <span v-if="append"
            class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
        {{ append }}
      </span>
    </div>
    <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    <p v-else-if="helperText" class="mt-1 text-sm text-gray-500">{{ helperText }}</p>
  </div>
</template>

<script setup>
import { computed, ref } from "vue";

const props = defineProps({
  modelValue: {
    type: [String, Number, File, Boolean],
    default: ''
  },
  label: String,
  type: {
    type: String,
    default: 'text'
  },
  name: String,
  id: {
    type: String,
    default: () => `id-${Math.floor(1000000 + Math.random() * 1000000)}`
  },
  required: Boolean,
  disabled: Boolean,
  error: String,
  helperText: String,
  prepend: {
    type: String,
    default: ''
  },
  append: {
    type: String,
    default: ''
  },
  placeholder: String,
  selectOptions: Array,
  min: [String, Number],
  max: [String, Number],
  step: {
    type: [String, Number],
    default: "0.01"
  },
  rows: Number,
  accept: String,
  fileButtonText: String
})

// For file input
const fileName = ref('');
const fileInput = ref(null);

const inputClasses = computed(() => {
  const cls = [
    `block w-full px-3 py-2 border ${props.disabled ? 'bg-gray-100' : 'bg-white'} border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm`,
  ];

  if (props.error) {
    cls.push('border-red-500 focus:ring-red-500 focus:border-red-500');
  }

  if (props.append && !props.prepend) {
    cls.push(`rounded-l-md`)
  } else if (props.prepend && !props.append) {
    cls.push(`rounded-r-md`)
  } else if (!props.prepend && !props.append) {
    cls.push('rounded-md')
  }

  if (props.disabled) {
    cls.push('cursor-not-allowed opacity-75');
  }
  
  return cls.join(' ')
})

const emit = defineEmits(['update:modelValue', 'change'])

function onChange(value) {
  emit('update:modelValue', value)
  emit('change', value)
}

function onFileInput(event) {
  emit('change', event.target.files[0]);
}

function onFileChange(event) {
  const file = event.target.files[0];
  if (file) {
    fileName.value = file.name;
    emit('update:modelValue', file);
  }
}

function clearFile() {
  fileName.value = '';
  if (fileInput.value) {
    fileInput.value.value = '';
  }
  emit('update:modelValue', null);
  emit('change', null);
}
</script>