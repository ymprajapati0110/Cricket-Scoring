    function showLoginPrompt(action) {
        if (confirm("Please login first to access " + action + ".")) {
            window.location.href = "login.html";
        }
    }

    document.querySelector('a[href="#"]').addEventListener('click', function(event) {
        event.preventDefault();
    });

    document.querySelector('a[href="#"]').addEventListener('click', function(event) {
        showLoginPrompt("Live Scores");
    });
    document.querySelectorAll(".navbar ul li a")[1].addEventListener('click', function(event) {
        event.preventDefault();
        showLoginPrompt("Start match");
    });
    document.querySelectorAll(".navbar ul li a")[2].addEventListener('click', function(event) {
        event.preventDefault();
        showLoginPrompt("My Profile");
    });

    document.querySelectorAll(".navbar ul li a")[3].addEventListener('click', function() {
        // Redirect to login page
        window.location.href = "login.html";
    });

    document.querySelector('img[alt="Google Play Store"]').parentElement.addEventListener('click', function() {
        window.location.href = "https://play.google.com/store/search?q=cricscorer&c=apps&hl=en_IN";
    });

    document.querySelector('img[alt="App Store"]').parentElement.addEventListener('click', function() {
        window.location.href = "https://www.apple.com/in/search/cricket-score?src=globalnav";
    });

    document.querySelector('img[alt="Instagram"]').parentElement.addEventListener('click', function() {
        window.location.href = "https://www.instagram.com/mera_yashuuu/";
    });

    document.querySelector('img[alt="Facebook"]').parentElement.addEventListener('click', function() {
        window.location.href = "https://www.facebook.com/IndianCricketTeam/";
    });

    document.querySelector('img[alt="YouTube"]').parentElement.addEventListener('click', function() {
        window.location.href = "https://www.youtube.com/watch?v=paOVdYBvyRs";
    });