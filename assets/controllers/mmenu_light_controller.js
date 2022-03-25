import {Controller} from '@hotwired/stimulus';
import 'mmenu-light';

// import { MmenuLight } from "mmenu-light";
// require('mmenu-light');

export default class extends Controller {

    connect() {
        console.log('hello from ' + this.identifier);

        const menu = new MmenuLight( document.getElementById( "menu" ) );
        console.log(menu);

        menu.enable( "all" );
        menu.offcanvas();

        document.querySelector( 'a[href="#menu"]' )
            .addEventListener( 'click', ( evnt ) => {
                menu.open();

                evnt.preventDefault();
                evnt.stopPropagation();
            });


        document.addEventListener( "click", ( evnt ) => {
            if (evnt.target?.closest?.('a[href^="#/"]')) {
                evnt.preventDefault();
                alert( "Thank you for clicking, but that's a demo link." );
            }
        });


        //
        // document.querySelector( "a[href='#menu']" )
        //     .addEventListener( "click", ( evnt ) => {
        //         evnt.preventDefault();
        //         drawer.open();
        //     });

    }

}
