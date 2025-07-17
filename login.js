const userTypeSelect = document.getElementById("userType");
const form = document.getElementById("loginForm");

document.getElementById("showPassword").addEventListener("change", function() {
    const passwordInput = document.getElementById("password");
    passwordInput.type = this.checked ? "text" : "password";
});

form.addEventListener("submit", function(event) {
    event.preventDefault(); 

    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();
    const userType = userTypeSelect.value;

    if (username === "") {
        alert("Please enter your username.");
        return;
    }

    if (password === "") {
        alert("Please enter your password.");
        return;
    }

    if (userType === "new") {
        if (password.length < 7 || !/[!@#$%^&*(),.?":{}|<>]/.test(password) || !/[A-Z]/.test(password)) {
            alert("Password must be at least 7 characters long, contain one uppercase letter, and one special character.");
            return;
        }
    }

    const userTypeInput = document.createElement("input");
    userTypeInput.type = "hidden";
    userTypeInput.name = "userType";
    userTypeInput.value = userType;
    form.appendChild(userTypeInput);

    form.submit();
});
