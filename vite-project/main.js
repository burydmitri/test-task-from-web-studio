import 'normalize.css'
import './src/styles/style.scss'

import Mmenu from 'mmenu-js'
import 'mburger-webcomponent'


console.log(document)
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
    }
);
