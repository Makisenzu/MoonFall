function showCreate() {
    document.getElementById('loginForm').style.opacity = '0';
    setTimeout(() => {
        document.getElementById('loginForm').style.display = 'none';
        document.getElementById('registerForm').style.display = 'block';
        setTimeout(() => {
            document.getElementById('registerForm').style.opacity = '1';
        }, 50);
    }, 300);
}

function showLogin() {
    document.getElementById('registerForm').style.opacity = '0';
    setTimeout(() => {
        document.getElementById('registerForm').style.display = 'none';
        document.getElementById('loginForm').style.display = 'block';
        setTimeout(() => {
            document.getElementById('loginForm').style.opacity = '1';
        }, 50);
    }, 300);
}