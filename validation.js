document.getElementById("myForm").addEventListener("submit", function(event) {
    // Reset error messages
    document.getElementById("nameError").innerText = "";
    document.getElementById("emailError").innerText = "";
    document.getElementById("phoneError").innerText = "";
    document.getElementById("countryError").innerText = "";

    
    


    var name = document.getElementById("name").value.trim();
    
    if (name === "") {
        document.getElementById("nameError").innerText = "Name is required";
        event.preventDefault();
    } else if (!/^[a-zA-Z\s]+$/.test(name)) {
        document.getElementById("nameError").innerText = "Name must contain only Alphabets.";
        event.preventDefault();
    }

     // Validate email
     var email = document.getElementById("email").value.trim();
     var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
     if (!emailRegex.test(email)) {
         document.getElementById("emailError").innerText = "Invalid email id";
         event.preventDefault();
     }

    //  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    //  if (email.trim() === '') {
    //    document.getElementById('emailError').innerHTML = 'Email is required';
    //    return false;
    //  } else if (!emailRegex.test(email)) {
    //    document.getElementById('emailError').innerHTML = 'Invalid email address';
    //    return false;
    //  }

    

     

    // Validate phone number
    var phone = document.getElementById("phone").value.trim();
    var phoneRegex = /^\d{10}$/; // Assuming a 10-digit phone number
    if (!phoneRegex.test(phone)) {
        document.getElementById("phoneError").innerText = "Invalid phone number";
        event.preventDefault();
    }
    // var phoneRegex = /^\d{10}$/; // Assuming a 10-digit phone number
    // if (phone.trim() === '') {
    //     document.getElementById('phoneError').innerHTML = 'Phone is required';
    //     return false;
    //   } else if (!phoneRegex.test(phone)) {
    //     document.getElementById('phoneError').innerHTML = 'Invalid phone number (10 digits)';
    //     return false;
    //   }


      // Validate country
    var country = document.getElementById("country").value;
    if (country === "") {
        document.getElementById("countryError").innerText = "Please select your Inquiry Type";
        event.preventDefault();
    }

    // Validate quantity
    // var quantity = document.getElementById("quantity").value.trim();
    // if (quantity === "") {
    //     document.getElementById("quantityError").innerText = "Quantity is required";
    //     event.preventDefault();
    // } else if (quantity === "" || isNaN(quantity) || quantity <= 0 || quantity > 9) {
    //     document.getElementById("quantityError").innerText = "Quantity limit exceeds please contact our branch";
    //     event.preventDefault();
    // }
});


