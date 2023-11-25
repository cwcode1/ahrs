
        function confirmLogout() {
            var result = confirm("Are you sure you want to log out?");
            if (result) {
                // Create a new XMLHttpRequest object
                var xhr = new XMLHttpRequest();

                // Configure it to send a POST request to logout.php
                xhr.open("POST", "logout.php", true);

                // Set the content type for POST requests
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                // Set up a callback function to handle the response
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Redirect to the login page after the server-side logout
                        window.location.href = "login.html";
                    }
                };

                // Send the AJAX request (form data can be null as we are handling logout on the server)
                xhr.send(null);
            } else {
                // If the user clicks "Cancel," do nothing or perform other actions
            }
        }
    