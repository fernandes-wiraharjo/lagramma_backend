let resendCooldown = 30; // seconds
let countdownInterval;

window.handleResendRegisterOTP = function (event) {
    event.preventDefault();

    const resendLink = document.getElementById('resend-link');
    if (resendLink.classList.contains('disabled')) return;

    const phone = document.querySelector('input[name="phone"]').value;
    const csrfToken = document.querySelector('input[name="_token"]').value;

    fetch("/register-otp-resend", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
            "Accept": "application/json"
        },
        body: JSON.stringify({ phone })
    })
    .then(async response => {
        const data = await response.json(); // wait for JSON regardless of status

        if (!response.ok) {
            // throws with server-side message if available
            throw new Error("Failed to resend OTP. " + (data.message || "Unknown error"));
        }

        // Show success message
        const msg = document.getElementById("resend-success-message");
        msg.classList.remove("d-none");

        // Hide after 5 seconds
        setTimeout(() => {
            msg.classList.add("d-none");
        }, 5000);

        // Start the cooldown timer
        startCooldown();
    })
    .catch(error => {
        alert("Resend failed: " + error.message);
    });
};

function startCooldown() {
    const resendLink = document.getElementById('resend-link');
    const resendTimer = document.getElementById('resend-timer');

    // Disable link
    resendLink.classList.add('disabled');
    resendLink.style.pointerEvents = 'none';
    resendLink.style.color = 'gray';

    resendTimer.style.display = 'inline';
    let remaining = resendCooldown;
    resendTimer.innerText = `(${remaining}s)`;

    countdownInterval = setInterval(() => {
        remaining--;
        resendTimer.innerText = `(${remaining}s)`;

        if (remaining <= 0) {
            clearInterval(countdownInterval);
            resendLink.classList.remove('disabled');
            resendLink.style.pointerEvents = 'auto';
            resendLink.style.color = ''; // back to default color
            resendTimer.style.display = 'none';
        }
    }, 1000);
}

// move next
function getInputElement(index) {
    return document.getElementById('digit' + index + '-input');
}

function moveToNext(index, event) {
    const eventCode = event.which || event.keyCode;

     // Move to next input if length is 1
    if (getInputElement(index).value.length === 1) {
        if (index !== 4) {
            getInputElement(index + 1).focus();
        } else {
            // Automatically submit the form when the last digit is entered
            document.getElementById('verify-otp-form').submit();
        }
    }

    // Move to the previous input on Backspace
    if (eventCode === 8 && index !== 1) {
        getInputElement(index - 1).focus();
    }
}
