function togglePassword(eye, psw, flag) {
    eye.onclick = function() {
        if (flag == 0) {
            psw.type = 'text';
            eye.src = 'https://cdn-icons-png.flaticon.com/512/709/709612.png';
            flag = 1;
        } else {
            psw.type = 'password';
            eye.src = 'https://cdn-icons-png.flaticon.com/512/2767/2767146.png';
            flag = 0;
        }
    }
}

var eye1 = document.getElementById('eye');
var psw1 = document.getElementById('psw');
var flag1 = 0;
togglePassword(eye1, psw1, flag1);

var eye2 = document.getElementById('eye2');
var psw2 = document.getElementById('psw2');
var flag2 = 0;
togglePassword(eye2, psw2, flag2);