// Add this to your project to help debug the API communication

// src/utils/DebugUtils.js
export function inspectAxiosInstance(axiosInstance) {
    // Store the original request method
    const originalRequest = axiosInstance.request;
    
    // Override the request method to add debugging
    axiosInstance.request = function(...args) {
      console.group('Axios Request Debug');
      console.log('Request arguments:', args);
      
      // Call the original method, but intercept the promise chain
      const result = originalRequest.apply(this, args);
      
      // Add debugging to the promise chain
      return result
        .then(response => {
          console.log('Response success:', {
            status: response.status,
            headers: response.headers,
            data: response.data
          });
          console.groupEnd();
          return response;
        })
        .catch(error => {
          console.error('Response error:', error);
          console.log('Error config:', error.config);
          if (error.response) {
            console.log('Error response:', {
              status: error.response.status,
              headers: error.response.headers,
              data: error.response.data
            });
          }
          console.groupEnd();
          throw error;
        });
    };
    
    return axiosInstance;
  }
  
  