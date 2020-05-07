
// any CSS you import will output into a single css file (app.css in this case)
import '../scss/app.scss';
import '../scss/navbar.scss';
import '../scss/homepage.scss';

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// hide navbar on scroll down :
var prevScrollpos = window.pageYOffset;
window.onscroll = function() {
    var currentScrollPos = window.pageYOffset;
    if (prevScrollpos > currentScrollPos) {
        document.getElementById("navbar").style.top = "0px";
    } else {
        document.getElementById("navbar").style.top = "-127px";
    }
    prevScrollpos = currentScrollPos;
};