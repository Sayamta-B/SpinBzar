function register_click() {
    // Get form values
    let username = document.forms["myForm"]["username"].value.trim();
    let email = document.forms["myForm"]["email"].value.trim();
    let password = document.forms["myForm"]["password"].value;
    let phone = document.forms["myForm"]["phone"].value.trim();
    let address = document.forms["myForm"]["address"].value.trim();

    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    
    if (!username) {
        alert("Please fill in the username.");
        return false;
    }

 
    if (!email) {
        alert("Please fill in the email.");
        return false;
    }
    if (!emailPattern.test(email)) {
        alert("Email format is invalid.");
        return false;
    }

    if (!password) {
        alert("Please fill in the password.");
        return false;
    }
    if (password.length < 8 || password.length > 20) {
        alert("Password should be between 8 and 20 characters.");
        return false;
    }

    if (!/^\d{10}$/.test(phone)) {
        alert("Phone number must be exactly 10 digits.");
        return false;
    }
    if (!address) {
        alert("Please fill in the address.");
        return false;
    }

    return true; 
}
