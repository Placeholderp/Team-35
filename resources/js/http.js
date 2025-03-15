/**
 * Sends an HTTP request using the Fetch API.
 */
export function request(method, url, data = {}) {
  return fetch(url, {
    method, 
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
    },
    // GET requests do not have a body.
    ...(method === 'get' ? {} : { body: JSON.stringify(data) })
  }).then(async (response) => {
    if (response.status >= 200 && response.status < 300) {
      return response.json();
    }
    // Otherwise, throw an error with the JSON-parsed error details.
    throw await response.json();
  });
}

/**
 * A helper function to perform an HTTP GET request.
 */
export function get(url) {
  return request('get', url);
}

/**
 * A helper function to perform an HTTP POST request.
 */
export function post(url, data) {
  return request('post', url, data);
}
