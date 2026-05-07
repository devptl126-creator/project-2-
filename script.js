function showRegister() {
    document.getElementById('register').style.display = 'block';
    document.getElementById('login').style.display = 'none';
}

function showLogin() {
    document.getElementById('register').style.display = 'none';
    document.getElementById('login').style.display = 'block';
}

// Basic date validation
document.addEventListener('DOMContentLoaded', function() {
    const checkin = document.querySelector('input[name="check_in"]');
    const checkout = document.querySelector('input[name="check_out"]');
    
    if (checkin) {
        checkin.addEventListener('change', function() {
            checkout.min = this.value;
        });
    }
});

function confirmDelete(id) {
    return confirm('Are you sure you want to delete this booking?');
}