<!DOCTYPE html>
<html>
<head>
    <title>User Activity</title>
</head>
<body>
    <!-- Your HTML content here -->

    <script src="{{ asset('js/checkUserActivity.js') }}"></script>
    <script>
        let timeout;

        // Function to sign out user
        function signout() {
            // Call the signout API
            $.ajax({
                url: '/api/signout',
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                complete: function() {
                    // Whether the signout was successful or not, we can clear the token
                    // and reload the page to ensure the user is signed out
                    localStorage.removeItem('token');
                    location.reload();
                }
            });
        }

        // Start a 15-minute timeout when the page loads
        timeout = setTimeout(signout, 15 * 60 * 1000);

        // Reset the timer on mouse movement
        document.addEventListener('mousemove', function() {
            clearTimeout(timeout);
            timeout = setTimeout(signout, 15 * 60 * 1000);
        });
    </script>
</body>
</html>
