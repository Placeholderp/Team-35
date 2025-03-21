import axiosClient from '../axios';
import JsBarcode from 'jsbarcode';
import QRCode from 'qrcode';
import { formatCurrency } from '../utils/ProductUtils';

export default {
  /**
   * Generate a barcode for a product
   * 
   * @param {Object} product - The product object
   * @returns {string} Data URL for the barcode image
   */
  generateProductBarcode(product) {
    if (!product) {
      throw new Error('Product is required');
    }
    
    // Create a canvas for the barcode
    const canvas = document.createElement('canvas');
    
    // Use SKU if available, otherwise use product ID
    const barcodeValue = product.sku || `PROD-${product.id}`;
    
    // Generate the barcode
    JsBarcode(canvas, barcodeValue, {
      format: "CODE128",
      lineColor: "#000",
      width: 2,
      height: 80,
      displayValue: true,
      fontSize: 14,
      text: product.sku ? `SKU: ${product.sku}` : `ID: ${product.id}`
    });
    
    // Return the data URL
    return canvas.toDataURL('image/png');
  },
  
  /**
   * Generate QR code for a product
   * @param {Object} product - Product data
   * @returns {Promise<String>} - Promise resolving to QR code data URL
   */
  async generateProductQRCode(product) {
    // Create product data for QR code
    const productData = {
      id: product.id,
      sku: product.sku,
      title: product.title,
      quantity: product.quantity,
      price: product.price
    };
    
    try {
      // Generate QR code as data URL
      const dataUrl = await QRCode.toDataURL(JSON.stringify(productData), {
        errorCorrectionLevel: 'H',
        margin: 1,
        width: 200
      });
      
      return dataUrl;
    } catch (error) {
      console.error('Error generating QR code:', error);
      throw error;
    }
  },
  
  /**
   * Process barcode scan result
   * @param {String} scanResult - Scan result
   * @returns {Promise<Object>} - Promise resolving to product data
   */
  async processBarcodeResult(scanResult) {
    try {
      // Attempt to find product by SKU
      const response = await axiosClient.get('/products/find-by-barcode', {
        params: { barcode: scanResult }
      });
      
      return response.data;
    } catch (error) {
      console.error('Error processing barcode scan:', error);
      throw error;
    }
  },
  
  /**
   * Process QR code scan result
   * @param {String} scanResult - Scan result
   * @returns {Object} - Product data from QR code
   */
  processQRCodeResult(scanResult) {
    try {
      // Parse JSON data from QR code
      const productData = JSON.parse(scanResult);
      
      return productData;
    } catch (error) {
      console.error('Error processing QR code scan:', error);
      throw error;
    }
  },
  
  /**
   * Generate and print barcodes for multiple products
   * @param {Array} products - Products to generate barcodes for
   * @param {String} format - Barcode format
   * @returns {String} - HTML content for printing
   */
  generateBarcodeSheet(products, format = 'CODE128') {
    // Create HTML content for printing
    let htmlContent = `
      <!DOCTYPE html>
      <html>
      <head>
        <title>Product Barcodes</title>
        <style>
          body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
          }
          .barcode-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
          }
          .barcode-item {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            width: 200px;
            margin-bottom: 10px;
          }
          .product-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
            height: 36px;
            overflow: hidden;
          }
          .product-info {
            font-size: 10px;
            margin-top: 5px;
          }
          @media print {
            .barcode-item {
              page-break-inside: avoid;
            }
          }
        </style>
      </head>
      <body>
        <div class="barcode-container">
    `;
    
    // Generate barcode for each product
    products.forEach(product => {
      try {
        const canvas = document.createElement('canvas');
        const barcodeValue = product.sku || `PROD-${product.id}`;
        
        JsBarcode(canvas, barcodeValue, {
          format,
          width: 2,
          height: 60,
          displayValue: true,
          fontSize: 12,
          text: product.sku ? `SKU: ${product.sku}` : `ID: ${product.id}`
        });
        
        const barcodeImage = canvas.toDataURL('image/png');
        
        htmlContent += `
          <div class="barcode-item">
            <div class="product-title">${product.title}</div>
            <img src="${barcodeImage}" alt="Barcode">
            <div class="product-info">
              ${product.sku ? `SKU: ${product.sku}` : ''}
              ${product.sku ? '<br>' : ''}
              Price: ${formatCurrency(product.price)}
            </div>
          </div>
        `;
      } catch (error) {
        console.error(`Error generating barcode for product ${product.id}:`, error);
      }
    });
    
    htmlContent += `
        </div>
      </body>
      </html>
    `;
    
    return htmlContent;
  },
  
  /**
   * Generate printable label for a single product
   * @param {Object} product - Product to generate label for
   * @returns {Window} - Print window with label content
   */
  printProductLabel(product) {
    if (!product) {
      throw new Error('Product is required');
    }
    
    // Generate barcode and QR code
    const barcodeImage = this.generateProductBarcode(product);
    
    // Create print window
    const printWindow = window.open('', '_blank');
    
    // Generate HTML content for the label
    printWindow.document.write(`
      <!DOCTYPE html>
      <html>
      <head>
        <title>Product Label - ${product.title}</title>
        <style>
          body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            text-align: center;
          }
          .label-container {
            border: 1px solid #ddd;
            padding: 15px;
            max-width: 300px;
            margin: 0 auto;
          }
          .product-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
          }
          .barcode-container {
            margin: 15px 0;
          }
          .barcode-container img {
            max-width: 100%;
          }
          .product-info {
            font-size: 12px;
            margin-top: 10px;
            text-align: left;
          }
          .price {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
          }
          @media print {
            body {
              margin: 0;
              padding: 0;
            }
            .print-button {
              display: none;
            }
          }
        </style>
      </head>
      <body>
        <div class="label-container">
          <div class="product-title">${product.title}</div>
          <div class="barcode-container">
            <img src="${barcodeImage}" alt="Barcode">
          </div>
          <div class="product-info">
            ${product.sku ? `SKU: ${product.sku}<br>` : ''}
            ${product.quantity ? `In Stock: ${product.quantity}<br>` : ''}
          </div>
          <div class="price">${formatCurrency(product.price)}</div>
        </div>
        <button class="print-button" onclick="window.print();return false;" style="margin-top:20px;">Print Label</button>
      </body>
      </html>
    `);
    
    printWindow.document.close();
    return printWindow;
  },
  
  /**
   * Download product barcode as an image
   * @param {Object} product - Product to generate barcode for
   * @param {String} filename - Optional filename
   */
  downloadProductBarcode(product, filename = null) {
    if (!product) {
      throw new Error('Product is required');
    }
    
    // Generate barcode
    const barcodeImage = this.generateProductBarcode(product);
    
    // Create download link
    const link = document.createElement('a');
    link.href = barcodeImage;
    link.download = filename || `${product.sku || product.id}-barcode.png`;
    
    // Trigger download
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }
};