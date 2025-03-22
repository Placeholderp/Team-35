<script>
    // CSRF Token refresh mechanism
    function refreshCsrfToken() {
        fetch('/csrf-refresh', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.token) {
                // Update all CSRF tokens in the page
                const metaToken = document.querySelector('meta[name="csrf-token"]');
                if (metaToken) {
                    metaToken.setAttribute('content', data.token);
                }
                
                // Update all form tokens
                document.querySelectorAll('input[name="_token"]').forEach(input => {
                    input.value = data.token;
                });
                
                console.log('CSRF token refreshed');
            }
        })
        .catch(error => {
            console.error('Failed to refresh CSRF token:', error);
        });
    }
    
    // Set up a periodic refresh every 30 minutes
    setInterval(refreshCsrfToken, 30 * 60 * 1000);
    
    // Also refresh when a user returns to the page after it was in background
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            refreshCsrfToken();
        }
    });
    </script>