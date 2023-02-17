/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/style.scss';

// start the Stimulus application
import './bootstrap';


const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything

// or you can include specific pieces
require('bootstrap/js/dist/tooltip');
require('bootstrap/js/dist/popover');
window.bootstrap = require('bootstrap');

$(document).ready(function () {
    $('[data-toggle="popover"]').popover();
});

// Navbar Toggler
$(document).ready(function () {
    const bsOffcanvas = document.getElementById('offcanvas-navbar');
    bsOffcanvas.addEventListener('hide.bs.offcanvas', event => {
        $("#navbar-button").removeClass("active");
        $("body").removeClass("navbar-expanded");
    })
    bsOffcanvas.addEventListener('show.bs.offcanvas', event => {
        $("#navbar-button").addClass("active");
        $("body").addClass("navbar-expanded");
    });
});

$('#navbar .nav-item').on('click', function () {
    let isSmallWindow = $(this).width() < 768;
    if (isSmallWindow){
        navbar(false);
    }
})

$(document).ready(function () {
    let isSmallWindow = $(document).width() > 768;
    navbar(isSmallWindow);
});

function navbar(open) {
    if (open) {
        jQuery('#offcanvas-navbar').offcanvas('show');
        console.log('show');
    } else {
        jQuery('#offcanvas-navbar').offcanvas('hide');
    }
}
