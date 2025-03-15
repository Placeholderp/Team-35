<template>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
      <!-- Page header -->
      <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900 leading-tight">Product Import</h1>
            <p class="mt-2 text-sm text-gray-600">
              Bulk import products to your catalog
            </p>
          </div>
          <div class="mt-4 md:mt-0 flex space-x-3">
            <button
              @click="router.push({ name: 'app.products' })"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
              Back to Products
            </button>
            <button
              v-if="step === 'review'"
              @click="importProducts"
              :disabled="isImporting || !hasValidData"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <svg v-if="isImporting" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <svg v-else class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              Start Import
            </button>
            <a 
              href="#" 
              @click.prevent="downloadTemplate"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Download Template
            </a>
          </div>
        </div>
      </div>
  
      <!-- Step indicator -->
      <div class="mb-8">
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex" aria-label="Tabs">
            <button
              class="w-1/3 py-4 px-1 text-center border-b-2 text-sm font-medium"
              :class="step === 'upload' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              :disabled="step !== 'upload'"
            >
              <div class="flex justify-center items-center">
                <span class="bg-indigo-100 text-indigo-700 rounded-full h-6 w-6 flex items-center justify-center mr-2">1</span>
                Upload File
              </div>
            </button>
            <button
              class="w-1/3 py-4 px-1 text-center border-b-2 text-sm font-medium"
              :class="step === 'configure' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              :disabled="step !== 'configure'"
            >
              <div class="flex justify-center items-center">
                <span class="bg-indigo-100 text-indigo-700 rounded-full h-6 w-6 flex items-center justify-center mr-2">2</span>
                Configure Import
              </div>
            </button>
            <button
              class="w-1/3 py-4 px-1 text-center border-b-2 text-sm font-medium"
              :class="step === 'review' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              :disabled="step !== 'review'"
            >
              <div class="flex justify-center items-center">
                <span class="bg-indigo-100 text-indigo-700 rounded-full h-6 w-6 flex items-center justify-center mr-2">3</span>
                Review & Import
              </div>
            </button>
          </nav>
        </div>
      </div>
  
      <!-- Upload file step -->
      <div v-if="step === 'upload'" class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Upload Product Data
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Import products from a CSV or Excel file
          </p>
        </div>
        <div class="px-4 py-5 sm:p-6">
          <div class="max-w-xl text-center mx-auto">
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
              <div class="space-y-1 text-center">
                <svg
                  class="mx-auto h-12 w-12 text-gray-400"
                  stroke="currentColor"
                  fill="none"
                  viewBox="0 0 48 48"
                  aria-hidden="true"
                >
                  <path
                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
                <div class="flex text-sm text-gray-600">
                  <label
                    for="file-upload"
                    class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none"
                  >
                    <span>Upload a file</span>
                    <input
                      id="file-upload"
                      name="file-upload"
                      type="file"
                      ref="fileInput"
                      class="sr-only"
                      accept=".csv,.xlsx,.xls"
                      @change="handleFileUpload"
                    />
                  </label>
                  <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs text-gray-500">CSV, Excel (.xlsx, .xls) up to 10MB</p>
              </div>
            </div>
            
            <div v-if="uploadStatus === 'error'" class="mt-4 bg-red-50 border-l-4 border-red-400 p-4">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <h3 class="text-sm font-medium text-red-800">
                    Error uploading file
                  </h3>
                  <div class="mt-2 text-sm text-red-700">
                    <p>{{ uploadError }}</p>
                  </div>
                </div>
              </div>
            </div>
            
            <div v-if="uploadStatus === 'processing'" class="mt-4">
              <div class="flex justify-center">
                <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </div>
              <p class="mt-2 text-sm text-gray-600 text-center">Processing your file...</p>
            </div>
            
            <div v-if="uploadStatus === 'success'" class="mt-4 bg-green-50 border-l-4 border-green-400 p-4">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <h3 class="text-sm font-medium text-green-800">
                    File uploaded successfully
                  </h3>
                  <div class="mt-2 text-sm text-green-700">
                    <p>{{ fileName }} ({{ fileSize }})</p>
                    <p class="mt-1">{{ productCount }} products found in file</p>
                  </div>
                  <div class="mt-4">
                    <button
                      type="button"
                      @click="goToConfigureStep"
                      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                      Continue to Configure
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Configure import step -->
      <div v-if="step === 'configure'" class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Configure Import Options
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Map file columns to product fields
          </p>
        </div>
        <div class="px-4 py-5 sm:p-6">
          <div class="space-y-6">
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
              <div class="sm:col-span-3">
                <div class="flex justify-between">
                  <label for="title-field" class="block text-sm font-medium text-gray-700">Product Name/Title</label>
                  <span class="text-sm text-red-600">Required</span>
                </div>
                <div class="mt-1">
                  <select
                    id="title-field"
                    v-model="columnMapping.title"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  >
                    <option value="">Select column</option>
                    <option v-for="column in availableColumns" :key="column" :value="column">{{ column }}</option>
                  </select>
                </div>
              </div>
  
              <div class="sm:col-span-3">
                <div class="flex justify-between">
                  <label for="price-field" class="block text-sm font-medium text-gray-700">Price</label>
                  <span class="text-sm text-red-600">Required</span>
                </div>
                <div class="mt-1">
                  <select
                    id="price-field"
                    v-model="columnMapping.price"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  >
                    <option value="">Select column</option>
                    <option v-for="column in availableColumns" :key="column" :value="column">{{ column }}</option>
                  </select>
                </div>
              </div>
  
              <div class="sm:col-span-3">
                <label for="description-field" class="block text-sm font-medium text-gray-700">Description</label>
                <div class="mt-1">
                  <select
                    id="description-field"
                    v-model="columnMapping.description"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  >
                    <option value="">Select column</option>
                    <option v-for="column in availableColumns" :key="column" :value="column">{{ column }}</option>
                  </select>
                </div>
              </div>
  
              <div class="sm:col-span-3">
                <label for="category-field" class="block text-sm font-medium text-gray-700">Category</label>
                <div class="mt-1">
                  <select
                    id="category-field"
                    v-model="columnMapping.category"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  >
                    <option value="">Select column</option>
                    <option v-for="column in availableColumns" :key="column" :value="column">{{ column }}</option>
                  </select>
                </div>
              </div>
  
              <div class="sm:col-span-3">
                <label for="quantity-field" class="block text-sm font-medium text-gray-700">Inventory Quantity</label>
                <div class="mt-1">
                  <select
                    id="quantity-field"
                    v-model="columnMapping.quantity"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  >
                    <option value="">Select column</option>
                    <option v-for="column in availableColumns" :key="column" :value="column">{{ column }}</option>
                  </select>
                </div>
              </div>
  
              <div class="sm:col-span-3">
                <label for="published-field" class="block text-sm font-medium text-gray-700">Published Status</label>
                <div class="mt-1">
                  <select
                    id="published-field"
                    v-model="columnMapping.published"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  >
                    <option value="">Select column</option>
                    <option v-for="column in availableColumns" :key="column" :value="column">{{ column }}</option>
                  </select>
                </div>
              </div>
            </div>
  
            <div class="space-y-3">
              <h4 class="text-sm font-medium text-gray-900">Import Options</h4>
              
              <div>
                <div class="flex items-start">
                  <div class="flex items-center h-5">
                    <input
                      id="update-existing"
                      name="update-existing"
                      type="checkbox"
                      v-model="importOptions.updateExisting"
                      class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                    />
                  </div>
                  <div class="ml-3 text-sm">
                    <label for="update-existing" class="font-medium text-gray-700">Update existing products</label>
                    <p class="text-gray-500">If a product with the same title exists, update it instead of creating a new one</p>
                  </div>
                </div>
              </div>
              
              <div>
                <div class="flex items-start">
                  <div class="flex items-center h-5">
                    <input
                      id="skip-empty"
                      name="skip-empty"
                      type="checkbox"
                      v-model="importOptions.skipEmpty"
                      class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                    />
                  </div>
                  <div class="ml-3 text-sm">
                    <label for="skip-empty" class="font-medium text-gray-700">Skip empty fields</label>
                    <p class="text-gray-500">Don't overwrite existing values with empty fields when updating products</p>
                  </div>
                </div>
              </div>
  
              <div>
                <div class="flex items-start">
                  <div class="flex items-center h-5">
                    <input
                      id="publish-all"
                      name="publish-all"
                      type="checkbox"
                      v-model="importOptions.publishAll"
                      class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                    />
                  </div>
                  <div class="ml-3 text-sm">
                    <label for="publish-all" class="font-medium text-gray-700">Publish all products</label>
                    <p class="text-gray-500">Make all imported products visible in your store immediately</p>
                  </div>
                </div>
              </div>
            </div>
  
            <!-- Navigation buttons -->
            <div class="flex justify-between">
              <button
                type="button"
                @click="step = 'upload'"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back
              </button>
              <button
                type="button"
                @click="goToReviewStep"
                :disabled="!isConfigValid"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Continue
                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Review and import step -->
      <div v-if="step === 'review'" class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Review Products
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Verify the data before importing (showing first 10 rows)
          </p>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Product Name
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Price
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Description
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Category
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Quantity
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(product, index) in previewData" :key="index" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">
                    {{ product.title || 'N/A' }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    {{ product.price ? '$' + parseFloat(product.price).toFixed(2) : 'N/A' }}
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 max-w-xs truncate">
                    {{ product.description || 'N/A' }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    {{ product.category || 'N/A' }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ product.quantity || '0' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    :class="[
                      'px-2 inline-flex text-xs leading-5 font-semibold rounded-full', 
                      product.published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                    ]"
                  >
                    {{ product.published ? 'Published' : 'Draft' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
  
        <!-- Import summary -->
        <div class="px-4 py-5 sm:p-6 border-t border-gray-200">
          <h4 class="text-sm font-medium text-gray-900 mb-4">Import Summary</h4>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
              <div class="text-sm text-gray-500">Total Products</div>
              <div class="text-xl font-semibold text-gray-900">{{ productCount }}</div>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg">
              <div class="text-sm text-gray-500">Estimated Processing Time</div>
              <div class="text-xl font-semibold text-gray-900">{{ estimatedTime }}</div>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg">
              <div class="text-sm text-gray-500">Upload Size</div>
              <div class="text-xl font-semibold text-gray-900">{{ fileSize }}</div>
            </div>
          </div>
          
          <!-- Import options summary -->
          <div class="mt-4 bg-gray-50 p-4 rounded-lg">
            <h5 class="text-sm font-medium text-gray-700 mb-2">Selected Options</h5>
            <ul class="space-y-1 text-sm text-gray-600">
              <li v-if="importOptions.updateExisting">
                • Update existing products: Yes
              </li>
              <li v-else>
                • Update existing products: No (Will create new products only)
              </li>
              <li v-if="importOptions.skipEmpty">
                • Skip empty fields: Yes
              </li>
              <li v-else>
                • Skip empty fields: No (Empty fields will overwrite existing values)
              </li>
              <li v-if="importOptions.publishAll">
                • Publish all products: Yes
              </li>
              <li v-else>
                • Publish all products: No (Will use values from file)
              </li>
            </ul>
          </div>
          
          <!-- Warning messages -->
          <div v-if="validationWarnings.length > 0" class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">
                  Attention needed
                </h3>
                <div class="mt-2 text-sm text-yellow-700">
                  <ul class="list-disc pl-5 space-y-1">
                    <li v-for="(warning, index) in validationWarnings" :key="index">
                      {{ warning }}
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Navigation buttons -->
          <div class="mt-6 flex justify-between">
            <button
              type="button"
              @click="step = 'configure'"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
              Back to Configure
            </button>
            
            <button
              type="button"
              @click="importProducts"
              :disabled="isImporting || !hasValidData"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg v-if="isImporting" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <svg v-else class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              {{ isImporting ? 'Importing Products...' : 'Start Import' }}
            </button>
          </div>
        </div>
      </div>
      
      <!-- Import progress modal -->
      <div v-if="showProgressModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
  
          <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
  
          <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
              <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100">
                <svg v-if="importStatus === 'completed'" class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <svg v-else-if="importStatus === 'error'" class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <svg v-else class="animate-spin h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                  {{ 
                    importStatus === 'completed' 
                      ? 'Import Completed' 
                      : importStatus === 'error' 
                        ? 'Import Failed' 
                        : 'Importing Products' 
                  }}
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    {{ 
                      importStatus === 'completed' 
                        ? `Successfully imported ${importStats.success} out of ${importStats.total} products.` 
                        : importStatus === 'error' 
                          ? importError
                          : 'Please wait while your products are being imported.' 
                    }}
                  </p>
                </div>
              </div>
              
              <!-- Progress bar -->
              <div v-if="importStatus === 'processing'" class="mt-4">
                <div class="bg-gray-200 rounded-full h-2.5">
                  <div class="bg-indigo-600 h-2.5 rounded-full" :style="{ width: `${importProgress}%` }"></div>
                </div>
                <div class="flex justify-between mt-1 text-xs text-gray-500">
                  <span>{{ importStats.processed }} of {{ importStats.total }}</span>
                  <span>{{ importProgress }}%</span>
                </div>
              </div>
              
              <!-- Import stats -->
              <div v-if="importStatus === 'completed'" class="mt-4 text-left">
                <div class="bg-gray-50 p-3 rounded-md">
                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <div class="text-xs text-gray-500">Success</div>
                      <div class="text-sm font-medium text-gray-900">{{ importStats.success }}</div>
                    </div>
                    <div>
                      <div class="text-xs text-gray-500">Errors</div>
                      <div class="text-sm font-medium text-gray-900">{{ importStats.errors }}</div>
                    </div>
                    <div>
                      <div class="text-xs text-gray-500">New Products</div>
                      <div class="text-sm font-medium text-gray-900">{{ importStats.new }}</div>
                    </div>
                    <div>
                      <div class="text-xs text-gray-500">Updated Products</div>
                      <div class="text-sm font-medium text-gray-900">{{ importStats.updated }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-5 sm:mt-6">
              <button
                type="button"
                @click="closeProgressModal"
                class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm"
              >
                {{ importStatus === 'processing' ? 'Continue in Background' : 'Close' }}
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Toast notification -->
      <Toast position="bottom" />
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted } from "vue";
  import { useRouter } from 'vue-router';
  import store from "../../store";
  import Toast from "../../components/core/Toast.vue";
  import axiosClient from "../../axios.js";
  
  const router = useRouter();
  const fileInput = ref(null);
  const uploadStatus = ref('idle'); // idle, processing, success, error
  const uploadError = ref('');
  const fileName = ref('');
  const fileSize = ref('');
  const productCount = ref(0);
  const step = ref('upload'); // upload, configure, review
  const importOptions = ref({
    updateExisting: true,
    skipEmpty: true,
    publishAll: false
  });
  const columnMapping = ref({
    title: '',
    price: '',
    description: '',
    category: '',
    quantity: '',
    published: ''
  });
  const availableColumns = ref([]);
  const importedData = ref([]);
  const previewData = ref([]);
  const validationWarnings = ref([]);
  const isImporting = ref(false);
  const importStatus = ref('idle'); // idle, processing, completed, error
  const importProgress = ref(0);
  const importStats = ref({
    total: 0,
    processed: 0,
    success: 0,
    errors: 0,
    new: 0,
    updated: 0
  });
  const importError = ref('');
  const showProgressModal = ref(false);
  
  // Computed properties
  const isConfigValid = computed(() => {
    // Title and price are required
    return columnMapping.value.title && columnMapping.value.price;
  });
  
  const hasValidData = computed(() => {
    return previewData.value.length > 0;
  });
  
  const estimatedTime = computed(() => {
    // Estimate processing time based on product count
    // Assume 10 products per second
    const seconds = Math.ceil(productCount.value / 10);
    
    if (seconds < 60) {
      return `${seconds} seconds`;
    } else {
      const minutes = Math.ceil(seconds / 60);
      return `${minutes} minute${minutes > 1 ? 's' : ''}`;
    }
  });
  
  // Methods
  function handleFileUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    // Check file size (max 10MB)
    if (file.size > 10 * 1024 * 1024) {
      uploadStatus.value = 'error';
      uploadError.value = 'File size exceeds the 10MB limit.';
      return;
    }
    
    // Check file type
    const validTypes = [
      'text/csv', 
      'application/vnd.ms-excel',
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];
    
    if (!validTypes.includes(file.type) && 
        !file.name.endsWith('.csv') && 
        !file.name.endsWith('.xlsx') && 
        !file.name.endsWith('.xls')) {
      uploadStatus.value = 'error';
      uploadError.value = 'Please upload a CSV or Excel file.';
      return;
    }
    
    // Save file info
    fileName.value = file.name;
    fileSize.value = formatFileSize(file.size);
    
    // Show processing state
    uploadStatus.value = 'processing';
    
    // Simulate file processing (in a real app, you would parse the file here)
    setTimeout(() => {
      parseFile(file);
    }, 1000);
  }
  
  function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }
  
  function parseFile(file) {
    // In a real app, you would use a library like PapaParse for CSV files
    // or SheetJS for Excel files
    
    // For demo purposes, let's simulate successful parsing with some sample data
    availableColumns.value = [
      'Product Name', 
      'Price', 
      'Description', 
      'Category', 
      'Quantity', 
      'Published'
    ];
    
    // Auto-map columns based on names
    columnMapping.value = {
      title: availableColumns.value.find(col => /name|title/i.test(col)) || '',
      price: availableColumns.value.find(col => /price/i.test(col)) || '',
      description: availableColumns.value.find(col => /description/i.test(col)) || '',
      category: availableColumns.value.find(col => /category/i.test(col)) || '',
      quantity: availableColumns.value.find(col => /quantity|stock|inventory/i.test(col)) || '',
      published: availableColumns.value.find(col => /published|status/i.test(col)) || ''
    };
    
    // Generate sample data
    const sampleData = [];
    for (let i = 1; i <= 25; i++) {
      sampleData.push({
        'Product Name': `Sample Product ${i}`,
        'Price': (Math.random() * 100 + 10).toFixed(2),
        'Description': `This is a sample product description for Product ${i}.`,
        'Category': ['Electronics', 'Clothing', 'Home & Kitchen', 'Books'][Math.floor(Math.random() * 4)],
        'Quantity': Math.floor(Math.random() * 100),
        'Published': Math.random() > 0.3 ? 'Yes' : 'No'
      });
    }
    
    importedData.value = sampleData;
    productCount.value = sampleData.length;
    
    // Set success status
    uploadStatus.value = 'success';
  }
  
  function goToConfigureStep() {
    step.value = 'configure';
  }
  
  function goToReviewStep() {
    if (!isConfigValid.value) {
      store.commit('showToast', 'Please map the required fields (Product Name and Price)', 'error');
      return;
    }
    
    // Transform imported data using the column mapping
    previewData.value = importedData.value.slice(0, 10).map(row => {
      const transformedRow = {};
      
      for (const [field, column] of Object.entries(columnMapping.value)) {
        if (column && row[column] !== undefined) {
          // Map the value and handle special cases
          let value = row[column];
          
          if (field === 'published') {
            // Convert various "published" values to boolean
            value = /yes|true|1/i.test(value);
          } else if (field === 'price') {
            // Ensure price is a number
            value = parseFloat(value) || 0;
          } else if (field === 'quantity') {
            // Ensure quantity is an integer
            value = parseInt(value) || 0;
          }
          
          transformedRow[field] = value;
        }
      }
      
      // Apply global options
      if (importOptions.value.publishAll) {
        transformedRow.published = true;
      }
      
      return transformedRow;
    });
    
    // Check for potential issues
    validationWarnings.value = [];
    
    const missingPrices = previewData.value.filter(p => !p.price).length;
    if (missingPrices > 0) {
      validationWarnings.value.push(`${missingPrices} product(s) in the preview have no price or invalid price.`);
    }
    
    const missingTitles = previewData.value.filter(p => !p.title).length;
    if (missingTitles > 0) {
      validationWarnings.value.push(`${missingTitles} product(s) in the preview have no title.`);
    }
    
    step.value = 'review';
  }
  
  function importProducts() {
    if (!hasValidData.value || isImporting.value) {
      return;
    }
    
    isImporting.value = true;
    importStatus.value = 'processing';
    showProgressModal.value = true;
    
    // Reset import stats
    importProgress.value = 0;
    importStats.value = {
      total: productCount.value,
      processed: 0,
      success: 0,
      errors: 0,
      new: 0,
      updated: 0
    };
    
    // Simulate import process with progress updates
    const totalProducts = productCount.value;
    let processed = 0;
    
    const interval = setInterval(() => {
      // Update progress
      processed += Math.floor(Math.random() * 5) + 1;
      if (processed > totalProducts) {
        processed = totalProducts;
      }
      
      importStats.value.processed = processed;
      importProgress.value = Math.floor((processed / totalProducts) * 100);
      
      // Update other stats randomly
      importStats.value.success = Math.min(processed, totalProducts - Math.floor(Math.random() * 5));
      importStats.value.errors = processed - importStats.value.success;
      
      if (importOptions.value.updateExisting) {
        importStats.value.new = Math.floor(importStats.value.success * 0.7);
        importStats.value.updated = importStats.value.success - importStats.value.new;
      } else {
        importStats.value.new = importStats.value.success;
        importStats.value.updated = 0;
      }
      
      // Check if completed
      if (processed >= totalProducts) {
        clearInterval(interval);
        
        // Mark as completed after a short delay
        setTimeout(() => {
          importStatus.value = 'completed';
          isImporting.value = false;
          
          // Navigate back to products after a delay if the modal is closed
          setTimeout(() => {
            if (!showProgressModal.value) {
              navigateToProducts();
            }
          }, 2000);
        }, 500);
      }
    }, 300);
  }
  
  function closeProgressModal() {
    showProgressModal.value = false;
    
    // If import is completed, navigate back to products
    if (importStatus.value === 'completed') {
      navigateToProducts();
    }
  }
  
  function navigateToProducts() {
    // Show success message in global toast
    store.commit('showToast', `Successfully imported ${importStats.value.success} products`);
    
    // Navigate back to products list
    router.push({ name: 'app.products' });
  }
  
  function downloadTemplate() {
    // In a real app, this would generate and download a CSV template
    
    // For demo, create a simple CSV string
    const headers = ['Product Name', 'Price', 'Description', 'Category', 'Quantity', 'Published'];
    const sampleRow = ['Example Product', '19.99', 'Description here', 'Electronics', '10', 'Yes'];
    
    const csv = [
      headers.join(','),
      sampleRow.join(',')
    ].join('\n');
    
    // Create a blob and download it
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.style.display = 'none';
    a.href = url;
    a.download = 'product_import_template.csv';
    
    document.body.appendChild(a);
    a.click();
    
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
  }
  </script>