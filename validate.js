// validation.js - Professional Version (Fixed!)

// ✅ Modern Email Validation (better regex)
function checkEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// ✅ Better Phone Validation (10 digits or +country code)
function checkPhone(phone) {
    // Remove spaces, dashes, parentheses
    const cleaned = phone.replace(/[\s\-\$\$]/g, '');
    // Check for 10 digits OR + followed by 10+ digits
    const phoneRegex = /^(\+?\d{10,15})$/;
    return phoneRegex.test(cleaned);
}

// ✅ Show Error Message
function showError(id, msg) {
    const errorDiv = document.getElementById(id);
    if (errorDiv) {
        errorDiv.textContent = msg;
        errorDiv.style.display = 'block';
        errorDiv.style.color = '#dc3545';
        errorDiv.style.fontSize = '14px';
        errorDiv.style.marginTop = '5px';
    }
}

// ✅ Hide All Errors
function hideErrors() {
    const errors = document.getElementsByClassName('error');
    for (let error of errors) {
        error.style.display = 'none';
        error.textContent = '';
    }
}

// ✅ Main Validation Function
function validateForm() {
    const email = document.getElementById('email')?.value.trim() || '';
    const phone = document.getElementById('phone')?.value.trim() || '';
    
    hideErrors();
    let isValid = true;
    
    if (!email) {
        showError('email-error', 'Email is required!');
        isValid = false;
    } else if (!checkEmail(email)) {
        showError('email-error', 'Please enter a valid email address!');
        isValid = false;
    }
    
    if (!phone) {
        showError('phone-error', 'Phone number is required!');
        isValid = false;
    } else if (!checkPhone(phone)) {
        showError('phone-error', 'Please enter a valid 10-digit phone number!');
        isValid = false;
    }
    
    return isValid;
}

// ✅ FIXED: Confirmation Dialog (REMOVED alert() calls)
function confirmRegister() {
    const name = document.getElementById('name')?.value.trim() || 'User';
    const email = document.getElementById('email')?.value.trim() || '';
    const phone = document.getElementById('phone')?.value.trim() || '';
    
    const msg = `Confirm Registration?\n\n` +
                `👤 Name: ${name}\n` +
                `📧 Email: ${email}\n` +
                `📱 Phone: ${phone}\n\n` +
                `Click OK to proceed or Cancel to edit.`;
    
    // ✅ ONLY confirm() - NO alert() calls!
    return confirm(msg);
}

// ✅ FIXED: Form Submit Handler
function formSubmit(event) {
    event.preventDefault();
    
    if (validateForm()) {
        if (confirmRegister()) {
            // ✅ SUCCESS: Submit the form
            const form = document.getElementById('reg-form');
            if (form) {
                form.submit();
            } else {
                console.log('Form submitted successfully!');
                // Fallback for demo
                alert('✅ Registration Successful! (Demo Mode)');
            }
        }
    }
}

// ✅ Initialize on Page Load
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('reg-form');
    if (form) {
        form.addEventListener('submit', formSubmit);
    }
    
    // ✅ Live Validation (Real-time feedback)
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const nameInput = document.getElementById('name');
    
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            if (this.value.trim() && !checkEmail(this.value.trim())) {
                showError('email-error', 'Please enter a valid email!');
            }
        });
        emailInput.addEventListener('input', function() {
            if (this.value.trim() && checkEmail(this.value.trim())) {
                hideErrors(); // Hide error on valid input
            }
        });
    }
    
    if (phoneInput) {
        phoneInput.addEventListener('blur', function() {
            if (this.value.trim() && !checkPhone(this.value.trim())) {
                showError('phone-error', 'Please enter valid phone number!');
            }
        });
        phoneInput.addEventListener('input', function() {
            if (this.value.trim() && checkPhone(this.value.trim())) {
                hideErrors(); // Hide error on valid input
            }
        });
    }
    
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            hideErrors(); // Hide errors when typing name
        });
    }
});

// ✅ Optional: Add shake animation for errors
function shakeError(inputId) {
    const input = document.getElementById(inputId);
    if (input) {
        input.style.animation = 'shake 0.5s';
        setTimeout(() => {
            input.style.animation = '';
        }, 500);
    }
}