import axiosClient from '../axios';
import { formatDate, formatCurrency } from '../utils/ProductUtils';

export default {
  /**
   * Generate an inventory value report
   * @param {Object} options - Report options
   * @returns {Promise} - Promise resolving to report data
   */
  async generateInventoryValueReport(options = {}) {
    try {
      const response = await axiosClient.get('/reports/inventory-value', { params: options });
      return this.processInventoryValueReport(response.data);
    } catch (error) {
      console.error('Error generating inventory value report:', error);
      throw error;
    }
  },
  
  /**
   * Generate a stock movement report
   * @param {Object} options - Report options including date range
   * @returns {Promise} - Promise resolving to report data
   */
  async generateStockMovementReport(options = {}) {
    try {
      const response = await axiosClient.get('/reports/stock-movement', { params: options });
      return this.processStockMovementReport(response.data);
    } catch (error) {
      console.error('Error generating stock movement report:', error);
      throw error;
    }
  },
  
  /**
   * Generate a low stock report
   * @param {Object} options - Report options
   * @returns {Promise} - Promise resolving to report data
   */
  async generateLowStockReport(options = {}) {
    try {
      const response = await axiosClient.get('/reports/low-stock', { params: options });
      return this.processLowStockReport(response.data);
    } catch (error) {
      console.error('Error generating low stock report:', error);
      throw error;
    }
  },
  
  /**
   * Process inventory value report data
   * @param {Object} data - Raw report data
   * @returns {Object} - Processed report data
   */
  processInventoryValueReport(data) {
    // Process raw report data
    const totalValue = data.products.reduce((sum, product) => {
      return sum + (product.quantity * product.price);
    }, 0);
    
    const categoriesValue = data.products.reduce((result, product) => {
      const categoryId = product.category_id || 'uncategorized';
      const categoryName = product.category ? product.category.name : 'Uncategorized';
      const productValue = product.quantity * product.price;
      
      if (!result[categoryId]) {
        result[categoryId] = {
          id: categoryId,
          name: categoryName,
          value: 0,
          products: []
        };
      }
      
      result[categoryId].value += productValue;
      result[categoryId].products.push({
        id: product.id,
        name: product.title,
        sku: product.sku,
        quantity: product.quantity,
        price: product.price,
        value: productValue
      });
      
      return result;
    }, {});
    
    return {
      totalValue,
      formattedTotalValue: formatCurrency(totalValue),
      categories: Object.values(categoriesValue).sort((a, b) => b.value - a.value),
      highestValueProducts: data.products
        .map(product => ({
          id: product.id,
          name: product.title,
          sku: product.sku,
          quantity: product.quantity,
          price: product.price,
          value: product.quantity * product.price,
          formattedValue: formatCurrency(product.quantity * product.price)
        }))
        .sort((a, b) => b.value - a.value)
        .slice(0, 10),
      generatedAt: formatDate(new Date())
    };
  },
  
  /**
   * Process stock movement report data
   * @param {Object} data - Raw report data
   * @returns {Object} - Processed report data
   */
  processStockMovementReport(data) {
    // Group movements by date
    const movementsByDate = data.movements.reduce((result, movement) => {
      const date = formatDate(new Date(movement.created_at), 'YYYY-MM-DD');
      
      if (!result[date]) {
        result[date] = [];
      }
      
      result[date].push(movement);
      return result;
    }, {});
    
    // Calculate daily totals
    const dailyTotals = Object.keys(movementsByDate).map(date => {
      const movements = movementsByDate[date];
      const inflow = movements
        .filter(m => m.quantity > 0)
        .reduce((sum, m) => sum + m.quantity, 0);
      const outflow = movements
        .filter(m => m.quantity < 0)
        .reduce((sum, m) => sum + Math.abs(m.quantity), 0);
        
      return {
        date,
        formattedDate: formatDate(new Date(date)),
        inflow,
        outflow,
        netChange: inflow - outflow,
        movements: movements.length
      };
    }).sort((a, b) => new Date(b.date) - new Date(a.date));
    
    // Calculate product movement statistics
    const productMovements = data.movements.reduce((result, movement) => {
      const productId = movement.product_id;
      
      if (!result[productId]) {
        result[productId] = {
          product_id: productId,
          product_name: movement.product ? movement.product.title : 'Unknown Product',
          product_sku: movement.product ? movement.product.sku : '',
          inflow: 0,
          outflow: 0,
          netChange: 0,
          movements: 0
        };
      }
      
      if (movement.quantity > 0) {
        result[productId].inflow += movement.quantity;
      } else {
        result[productId].outflow += Math.abs(movement.quantity);
      }
      
      result[productId].netChange += movement.quantity;
      result[productId].movements++;
      
      return result;
    }, {});
    
    return {
      dateRange: {
        start: data.dateRange.start,
        end: data.dateRange.end,
        formattedStart: formatDate(new Date(data.dateRange.start)),
        formattedEnd: formatDate(new Date(data.dateRange.end))
      },
      totalMovements: data.movements.length,
      totalInflow: data.movements
        .filter(m => m.quantity > 0)
        .reduce((sum, m) => sum + m.quantity, 0),
      totalOutflow: data.movements
        .filter(m => m.quantity < 0)
        .reduce((sum, m) => sum + Math.abs(m.quantity), 0),
      dailyTotals,
      productMovements: Object.values(productMovements)
        .sort((a, b) => b.movements - a.movements),
      topInflowProducts: Object.values(productMovements)
        .sort((a, b) => b.inflow - a.inflow)
        .slice(0, 10),
      topOutflowProducts: Object.values(productMovements)
        .sort((a, b) => b.outflow - a.outflow)
        .slice(0, 10),
      generatedAt: formatDate(new Date())
    };
  },
  
  /**
   * Process low stock report data
   * @param {Object} data - Raw report data
   * @returns {Object} - Processed report data
   */
  processLowStockReport(data) {
    // Group products by status
    const groupedProducts = data.products.reduce((result, product) => {
      if (product.quantity === 0) {
        result.outOfStock.push(product);
      } else if (product.quantity <= (product.reorder_level || 5)) {
        result.lowStock.push(product);
      } else {
        result.inStock.push(product);
      }
      
      return result;
    }, { outOfStock: [], lowStock: [], inStock: [] });
    
    return {
      outOfStock: groupedProducts.outOfStock.map(product => ({
        id: product.id,
        name: product.title,
        sku: product.sku,
        quantity: product.quantity,
        reorderLevel: product.reorder_level || 5,
        lastSold: product.last_sold_at ? formatDate(new Date(product.last_sold_at)) : 'N/A',
        estimatedDemand: product.estimated_daily_demand || 'N/A'
      })),
      lowStock: groupedProducts.lowStock.map(product => ({
        id: product.id,
        name: product.title,
        sku: product.sku,
        quantity: product.quantity,
        reorderLevel: product.reorder_level || 5,
        daysUntilStockout: product.estimated_daily_demand ? 
          Math.floor(product.quantity / product.estimated_daily_demand) : 
          'N/A',
        recommendedOrder: product.recommended_order_quantity || 
          Math.max((product.reorder_level || 5) * 2 - product.quantity, 1)
      })),
      stats: {
        totalProducts: data.products.length,
        outOfStockCount: groupedProducts.outOfStock.length,
        lowStockCount: groupedProducts.lowStock.length,
        outOfStockPercentage: (groupedProducts.outOfStock.length / data.products.length * 100).toFixed(1),
        lowStockPercentage: (groupedProducts.lowStock.length / data.products.length * 100).toFixed(1)
      },
      generatedAt: formatDate(new Date())
    };
  }
};