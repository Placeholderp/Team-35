class CacheService {
    constructor() {
      this.cache = new Map();
      this.expirations = new Map();
    }
    
    /**
     * Get item from cache
     * @param {String} key - Cache key
     * @returns {any} - Cached value or undefined if not found or expired
     */
    get(key) {
      // Check if item exists and is not expired
      if (this.cache.has(key)) {
        const expiration = this.expirations.get(key);
        
        // Check if item has expired
        if (expiration && expiration < Date.now()) {
          this.remove(key);
          return undefined;
        }
        
        return this.cache.get(key);
      }
      
      return undefined;
    }
    
    /**
     * Set item in cache
     * @param {String} key - Cache key
     * @param {any} value - Value to cache
     * @param {Number} ttl - Time to live in seconds (0 means no expiration)
     */
    set(key, value, ttl = 300) {
      this.cache.set(key, value);
      
      // Set expiration if TTL is provided
      if (ttl > 0) {
        this.expirations.set(key, Date.now() + (ttl * 1000));
      } else {
        this.expirations.delete(key);
      }
      
      return value;
    }
    
    /**
     * Remove item from cache
     * @param {String} key - Cache key
     */
    remove(key) {
      this.cache.delete(key);
      this.expirations.delete(key);
    }
    
    /**
     * Check if item exists in cache and is not expired
     * @param {String} key - Cache key
     * @returns {Boolean} - True if item exists and is not expired
     */
    has(key) {
      if (!this.cache.has(key)) {
        return false;
      }
      
      const expiration = this.expirations.get(key);
      
      // Check if item has expired
      if (expiration && expiration < Date.now()) {
        this.remove(key);
        return false;
      }
      
      return true;
    }
    
    /**
     * Clear all items from cache
     */
    clear() {
      this.cache.clear();
      this.expirations.clear();
    }
    
    /**
     * Clear expired items from cache
     */
    clearExpired() {
      for (const [key, expiration] of this.expirations.entries()) {
        if (expiration < Date.now()) {
          this.remove(key);
        }
      }
    }
    
    /**
     * Get cached value or fetch it if not cached
     * @param {String} key - Cache key
     * @param {Function} fetchFn - Function to fetch data if not cached
     * @param {Number} ttl - Time to live in seconds
     * @returns {Promise} - Cached or fetched value
     */
    async getOrFetch(key, fetchFn, ttl = 300) {
      // Check if item is in cache
      const cachedValue = this.get(key);
      if (cachedValue !== undefined) {
        return cachedValue;
      }
      
      // Fetch data
      const value = await fetchFn();
      
      // Cache fetched data
      this.set(key, value, ttl);
      
      return value;
    }
  }
  
  export default new CacheService();