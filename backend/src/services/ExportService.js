import axiosClient from '../axios';
import { formatDate } from '../utils/ProductUtils';

export default {
  /**
   * Export inventory data as CSV
   * @param {Array} products - Products to export
   * @returns {String} - CSV content
   */
  exportProductsToCSV(products) {
    // Define CSV headers
    const headers = [
      'ID',
      'SKU',
      'Product Name',
      'Category',
      'Current Stock',
      'Reorder Level',
      'Price',
      'Inventory Value',
      'Status'
    ];
    
    // Convert products to CSV rows
    const rows = products.map(product => {
      const status = this.getProductStatus(product);
      
      return [
        product.id,
        product.sku || '',
        product.title,
        product.category ? product.category.name : '',
        product.quantity || 0,
        product.reorder_level || 5,
        product.price || 0,
        (product.quantity || 0) * (product.price || 0),
        status
      ];
    });
    
    // Combine headers and rows
    const csvContent = [
      headers.join(','),
      ...rows.map(row => row.map(cell => this.escapeCSVCell(cell)).join(','))
    ].join('\n');
    
    return csvContent;
  },
  
  /**
   * Export inventory movements as CSV
   * @param {Array} movements - Movements to export
   * @returns {String} - CSV content
   */
  exportMovementsToCSV(movements) {
    // Define CSV headers
    const headers = [
      'Date',
      'Product',
      'SKU',
      'Type',
      'Quantity',
      'Previous Stock',
      'New Stock',
      'Reference',
      'User'
    ];
    
    // Convert movements to CSV rows
    const rows = movements.map(movement => {
      return [
        formatDate(new Date(movement.created_at)),
        movement.product ? movement.product.title : 'Unknown Product',
        movement.product ? (movement.product.sku || '') : '',
        this.formatMovementType(movement.type),
        movement.quantity,
        movement.previous_quantity,
        movement.new_quantity,
        movement.reference || '',
        movement.created_by ? movement.created_by.name : 'System'
      ];
    });
    
    // Combine headers and rows
    const csvContent = [
      headers.join(','),
      ...rows.map(row => row.map(cell => this.escapeCSVCell(cell)).join(','))
    ].join('\n');
    
    return csvContent;
  },
  
  /**
   * Export data to PDF
   * @param {Object} data - Data to export
   * @param {String} template - Template name
   * @returns {Promise} - Promise resolving to PDF blob
   */
  async exportToPDF(data, template) {
    try {
      const response = await axiosClient.post('/export/pdf', {
        data,
        template
      }, {
        responseType: 'blob'
      });
      
      return response.data;
    } catch (error) {
      console.error('Error exporting to PDF:', error);
      throw error;
    }
  },
  
  /**
   * Export data to Excel
   * @param {Object} data - Data to export
   * @param {String} template - Template name
   * @returns {Promise} - Promise resolving to Excel blob
   */
  async exportToExcel(data, template) {
    try {
      const response = await axiosClient.post('/export/excel', {
        data,
        template
      }, {
        responseType: 'blob'
      });
      
      return response.data;
    } catch (error) {
      console.error('Error exporting to Excel:', error);
      throw error;
    }
  },
  
  /**
   * Download exported file
   * @param {Blob} blob - File blob
   * @param {String} filename - File name
   */
  downloadFile(blob, filename) {
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(url);
  },
  
  /**
   * Get product status text
   * @param {Object} product - Product
   * @returns {String} - Status text
   */
  getProductStatus(product) {
    if (!product.track_inventory) return 'Not Tracked';
    if (product.quantity === 0) return 'Out of Stock';
    if (product.quantity <= (product.reorder_level || 5)) return 'Low Stock';
    return 'In Stock';
  },
  
  /**
   * Format movement type for display
   * @param {String} type - Movement type
   * @returns {String} - Formatted type
   */
  formatMovementType(type) {
    if (!type) return 'Unknown';
    
    const typeMap = {
      'purchase': 'Purchase',
      'sale': 'Sale',
      'adjustment': 'Adjustment',
      'return': 'Return',
      'initial': 'Initial Stock'
    };
    
    return typeMap[type] || type.charAt(0).toUpperCase() + type.slice(1);
  },
  
  /**
   * Escape CSV cell content
   * @param {any} cell - Cell content
   * @returns {String} - Escaped cell content
   */
  escapeCSVCell(cell) {
    const cellStr = String(cell);
    
    // If cell contains comma, newline, or double quote, enclose in double quotes
    if (cellStr.includes(',') || cellStr.includes('\n') || cellStr.includes('"')) {
      // Replace double quotes with two double quotes
      return `"${cellStr.replace(/"/g, '""')}"`;
    }
    
    return cellStr;
  }
};