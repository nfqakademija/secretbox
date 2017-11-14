var loginButton = document.getElementById('login-button');
var orderButton = document.getElementById('order-button');

if(loginButton) {
    loginButton.onclick = function () {
        document.getElementById('fa-text').innerHTML = userWaiting;
    }
}

if(orderButton) {
    orderButton.onclick = function () {
        orderButton.disabled = true;
        document.getElementById('order-button-text').innerHTML = userWaiting;
    }
}

// todo order countdown timer:
