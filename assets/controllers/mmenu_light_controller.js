import {Controller} from '@hotwired/stimulus';
import 'mmenu-light';

// import { MmenuLight } from "mmenu-light";
// require('mmenu-light');

export default class extends Controller {

    connect() {
        console.log('hello from ' + this.identifier);


        const menu = new MmenuLight( document.getElementById( "menu" ) );
        // console.log(Object.getOwnPropertyNames(menu).forEach( item => console.log(item, menu.item,  typeof menu[item])));
        // let getMethods = (obj) => Object.getOwnPropertyNames(obj).filter(item => true || typeof obj[item] === 'function');
        // console.log(getMethods(menu), getMethods(menu.menu));

        const navigator = menu.navigation({
            slidingSubmenus: false,
            title: 'MMenu Light',
            offCanvas: {
                use: false
            }

            // options
        });

        // const drawer = menu.offcanvas({
        //     use: false
        //     // options
        // });
        // drawer.open();

        console.log(menu);



        // menu.enable( "all" );
        // menu.offcanvas();

        document.querySelector( 'a[href="#menu"]' )
            .addEventListener( 'click', ( evnt ) => {
                drawer.open();

                evnt.preventDefault();
                evnt.stopPropagation();
            });


        // document.addEventListener( "click", ( evnt ) => {
        //     if (evnt.target?.closest?.('a[href^="#/"]')) {
        //         evnt.preventDefault();
        //         alert( "Thank you for clicking, but that's a demo link." );
        //     }
        // });


        //
        // document.querySelector( "a[href='#menu']" )
        //     .addEventListener( "click", ( evnt ) => {
        //         evnt.preventDefault();
        //         drawer.open();
        //     });

    }

}
