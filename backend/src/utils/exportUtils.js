/**
 * Utility functions for exporting data
 */

/**
 * Export data to CSV file
 * @param {Array} data - Array of objects to export
 * @param {String} filename - Filename for the exported file
 * @param {Array} columns - Array of column definitions { key, label }
 */
export function exportToCsv(data, filename, columns) {
    if (!data || !data.length) {
      console.error('No data to export');
      return;
    }
  
    // Create CSV header row
    const header = columns.map(col => `"${col.label}"`).join(',');
    
    // Create CSV rows from data
    const rows = data.map(item => 
      columns
        .map(col => {
          // Get the value using the column key
          let value = item[col.key];
          
          // Format date values if needed
          if (col.format === 'date' && value) {
            value = new Date(value).toLocaleDateString();
          }
          
          // Format currency values if needed
          if (col.format === 'currency' && value != null) {
            value = Number(value).toFixed(2);
          }
          
          // Handle nested objects with dot notation (e.g. "customer.name")
          if (col.key.includes('.') && value === undefined) {
            const keys = col.key.split('.');
            let nestedValue = item;
            
            for (const key of keys) {
              nestedValue = nestedValue?.[key];
              if (nestedValue === undefined) break;
            }
            
            value = nestedValue;
          }
          
          // Escape values that contain commas or quotes
          if (value == null) return '""';
          return `"${String(value).replace(/"/g, '""')}"`;
        })
        .join(',')
    );
    
    // Combine header and rows
    const csv = [header, ...rows].join('\n');
    
    // Create download link
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    
    // Set link properties
    link.setAttribute('href', url);
    link.setAttribute('download', filename);
    link.style.display = 'none';
    
    // Add link to document, click it, and remove it
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }
  
  /**
   * Export orders to CSV
   * @param {Array} orders - Array of order objects
   * @param {String} filename - Optional filename (default: orders_export.csv)
   */
  export function exportOrdersToCsv(orders, filename = 'orders_export.csv') {
    // Define columns for orders export
    const columns = [
      { key: 'id', label: 'Order ID' },
      { key: 'customer.first_name', label: 'First Name' },
      { key: 'customer.last_name', label: 'Last Name' },
      { key: 'customer.email', label: 'Email' },
      { key: 'status', label: 'Status' },
      { key: 'total_price', label: 'Total', format: 'currency' },
      { key: 'created_at', label: 'Date', format: 'date' },
      { key: 'payment_method', label: 'Payment Method' }
    ];
    
    exportToCsv(orders, filename, columns);
  }
  
  /**
   * Export order items to CSV
   * @param {Object} order - Order object with items array
   * @param {String} filename - Optional filename
   */
  export function exportOrderItemsToCsv(order, filename = `order_${order.id}_items.csv`) {
    if (!order || !order.items || !order.items.length) {
      console.error('No items to export');
      return;
    }
    
    // Define columns for order items export
    const columns = [
      { key: 'product.sku', label: 'SKU' },
      { key: 'product.title', label: 'Product' },
      { key: 'quantity', label: 'Quantity' },
      { key: 'unit_price', label: 'Unit Price', format: 'currency' },
      { key: 'total', label: 'Total Price', format: 'currency' }
    ];
    
    // Calculate total for each item
    const items = order.items.map(item => ({
      ...item,
      total: item.quantity * item.unit_price
    }));
    
    exportToCsv(items, filename, columns);
  }