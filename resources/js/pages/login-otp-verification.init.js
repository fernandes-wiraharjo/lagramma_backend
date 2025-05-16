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
