<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" role="dialog">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="close"></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
              <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                Product Barcode
              </h3>
              <div class="mt-4 flex flex-col items-center justify-center">
                <!-- Product Info -->
                <div class="mb-4 w-full text-center">
                  <h4 class="text-base font-medium text-gray-800">{{ product?.title }}</h4>
                  <p class="text-sm text-gray-500">SKU: {{ product?.sku || 'N/A' }}</p>
                </div>
              
                <!-- Barcode -->
                <div v-if="barcodeDataUrl" class="mb-4 p-4 border border-gray-200 rounded-md">
                  <img :src="barcodeDataUrl" alt="Product Barcode" class="mx-auto">
                  <p class="mt-2 text-xs text-center text-gray-500">
                    {{ product?.sku || `PROD-${product?.id}` }}
                  </p>
                </div>
                
                <!-- QR Code -->
                <div v-if="qrCodeDataUrl" class="mb-4 p-4 border border-gray-200 rounded-md">
                  <img :src="qrCodeDataUrl" alt="Product QR Code" class="mx-auto">
                </div>
                
                <!-- Print button -->
                <div class="mt-4 w-full flex space-x-3">
                  <button 
                    @click="printBarcode" 
                    class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Barcode
                  </button>
                  <button 
                    @click="downloadBarcode" 
                    class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button 
            @click="close" 
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, onMounted, watch } from 'vue';
import BarcodeService from '../../services/BarcodeService';

const props = defineProps({
  show: Boolean,
  product: Object
});

const emit = defineEmits(['close']);

const barcodeDataUrl = ref('');
const qrCodeDataUrl = ref('');

function close() {
  emit('close');
}

function printBarcode() {
  const printWindow = window.open('', '_blank');
  printWindow.document.write(`
    <html>
      <head>
        <title>Print Barcode - ${props.product?.title || 'Product'}</title>
        <style>
          body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
          .container { margin-bottom: 20px; }
          .product-title { font-size: 16px; font-weight: bold; margin-bottom: 10px; }
          .product-sku { font-size: 12px; color: #666; margin-bottom: 20px; }
          img { max-width: 100%; }
          @media print {
            body { margin: 0; padding: 0; }
            .print-button { display: none; }
          }
        </style>
      </head>
      <body>
        <div class="product-title">${props.product?.title || 'Product'}</div>
        <div class="product-sku">SKU: ${props.product?.sku || 'N/A'}</div>
        <div class="container">
          <img src="${barcodeDataUrl.value}" alt="Barcode">
        </div>
        <div class="container">
          <img src="${qrCodeDataUrl.value}" alt="QR Code">
        </div>
        <button class="print-button" onclick="window.print();return false;">Print</button>
      </body>
    </html>
  `);
  printWindow.document.close();
}

function downloadBarcode() {
  // Download barcode using the BarcodeService
  if (props.product) {
    BarcodeService.downloadProductBarcode(
      props.product, 
      `${props.product.title}-barcode.png`
    );
  }
}

// Generate barcode when product changes
watch(() => props.product, async (newProduct) => {
  if (newProduct && props.show) {
    try {
      barcodeDataUrl.value = BarcodeService.generateProductBarcode(newProduct);
      qrCodeDataUrl.value = await BarcodeService.generateProductQRCode(newProduct);
    } catch (error) {
      console.error('Error generating codes:', error);
    }
  }
}, { immediate: true });

// Also generate when modal is shown
watch(() => props.show, async (isShown) => {
  if (isShown && props.product) {
    try {
      barcodeDataUrl.value = BarcodeService.generateProductBarcode(props.product);
      qrCodeDataUrl.value = await BarcodeService.generateProductQRCode(props.product);
    } catch (error) {
      console.error('Error generating codes:', error);
    }
  }
});
</script>