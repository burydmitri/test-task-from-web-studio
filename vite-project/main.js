import 'normalize.css'
import './src/styles/style.scss'

import Mmenu from 'mmenu-js'
import 'mburger-webcomponent'

document.addEventListener(
    "DOMContentLoaded", () => {
        new Mmenu("#mmenu", {
            navbar: {
                add: false
            },
            slidingSubmenus: false,
            offCanvas: {
                position: "right",
                page: {
                    selector: "#app",
                }
            },
        });
        console.log(window.innerWidth)
    }
);

$(document).ready(function () {
    $(".owl-carousel").owlCarousel({
        nav:true,
        navContainer: ".tariffs__buttons",
        dotsContainer: ".tariffs__dots",
        responsive:{
            0:{
                items:1,
                margin:15,
                stagePadding: 20,
            },
            590:{
                items:2,
                margin:20,
                stagePadding: 30,
            },
            1330:{
                items:4,
                margin:20,
                stagePadding: 1,
            },
            1440:{
                items:4,
                margin:30,
                stagePadding: 1,
            }
        }
    });
});