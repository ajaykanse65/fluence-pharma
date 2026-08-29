

//  script to add data to sheet 
$(document).ready(function () {
    jQuery('#frmSubmit-2').on('submit', function (e) {

        e.preventDefault();

        jQuery.ajax({
            url: 'https://script.google.com/macros/s/AKfycbwacYMO4b6PSvOS_4yqVuWZ0FugIrDG0Mff9BsVx5A4wXSKFFJhIOaVVgCN6spaj_W94g/exec',
            type: 'post',
            data: jQuery('#frmSubmit-2').serialize(),
            success: function (result) {
                console.log("Thank you, Someone will reach out to you soon!!")
            },
        });

        console.log("hello", jQuery('#frmSubmit-2').serialize());
        jQuery.ajax({
            url: '../enquiry-kyk.php',
            type: 'post',
            data: jQuery('#frmSubmit-2').serialize(),
            success: function (result) {
                // alert("Thankyou, Someone will reach out to you soon!!");
                $('#success_msg').html("Thankyou, Someone will reach out to you soon!!");
                $('#success_msg').css({ "font-size": "xx-large", "font-weight": "bold" });
            },

        });
        return false;
    });

});

//   popup to be shown on page load  

const loginPopup = document.querySelector(".login-popup");
const close = document.querySelector(".close");

window.addEventListener("load", function () {

    showPopup();

})

function showPopup() {
    const timeLimit = 3 // seconds;
    let i = 0;
    const timer = setInterval(function () {
        i++;
        if (i == timeLimit) {
            clearInterval(timer);
            loginPopup.classList.add("show");
        }
        console.log(i)
    }, 1000);
}

close.addEventListener("click", function () {
    loginPopup.classList.remove("show");
})



//  cookie on popup on load  

var modal2 = document.getElementById("myModal-2");
var span = document.getElementById("close-2");


// When the user clicks on <span> (x), close the modal
span.onclick = function () {
    modal2.style.display = "none";
    document.cookie = "WebsiteName=fluencepharma.in; max-age=" + 24 * 60 * 60;
}

// Use the created cookie to hide or show the popup screen.
const WebsiteCookie = document.cookie.indexOf("WebsiteName=");
if (WebsiteCookie != -1) {
    modal2.style.display = "none";// Hide the popup screen if the cookie is not expired.
    document.cookie = "WebsiteName=fluencepharma.in; max-age=" + 24 * 60 * 60;
}
else {
    modal2.style.display = "block";// Show the
}


//  to disable submit button once submitted   

var form_being_submitted = false; // global variable

function checkForm(form) {
    if (form.name.value == "") {
        alert("Please enter your name");
        form.name.focus();
        return false;
    }
    //Add more fields according to your form
    return true;
}

//  to include header from header.html   

jQuery(function (i) {
    i(".include").each(function () {
        i(this).load(i(this).data("include"))
    })
})


//  form input if another city given 


function showfield(name) {
    if (name == 'Other') document.getElementById('div1').innerHTML = 'Other: <input type="text" name="other" />';
    else
        document.getElementById('div1').innerHTML = '';
}
