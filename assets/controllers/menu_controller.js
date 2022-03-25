import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['menu', 'msg', 'page', 'toggleable'];
    static values = {
        currentMenuItem: {type: String, default: ''},
        depth: Number
        // depth: { type: Number, default: 2 }, stimulus 2.1?
    }


    connect() {
        // if (!this.hasMenuTarget) {
        //     this.menuTarget = this.element.querySelector('#menu');
        // }
        console.warn('hello from ' + this.identifier);

        this.msgTarget.innerHTML = 'loading ' + this.currentMenuItemValue;

        // const Mmenu = require('mmenu-js');
        // let m = this.menuTarget;
        const menu = new Mmenu( '#menu', {
            slidingSubmenus: false,
            offCanvas: {
                page: {
                    selector: "#page"
                }
            }
        });
        // return menu;

        // could also get a class="fetch" or something.
        if (false)
        this.treeTarget.querySelectorAll('[data-href]').forEach(item => {
            console.log("activating partial fetch for " + item.dataset.href);
            item
                .addEventListener(
                    "click", (evnt) => {
                        console.error("click handler for " + item.dataset.href);
                        evnt.preventDefault(); //
                        // item.removeAttribute("href"); // hackish, trying to block the item from opening a new page
                        item.classList.add("ancestor-active");
                        if (item.dataset.href) {
                            const url = item.dataset.href;
                            console.error(url);
                            this.contentTarget.innerText = "Loading ..." + url;

                            fetch(url)
                                .then(response => response.text())
                                .then(html => {
                                    this.contentTarget.innerHTML = html;
                                })
                                .catch(error => {
                                    this.contentTarget.innerHTML = error.toString();
                                    console.error('Error:', error);
                                });
                        }

                        // Example of dynamic list creation.
                        //    Find the panel and listview.
                        // const panel = document.querySelector("#my-list");
                        // const listview = panel.querySelector(".mm-listview");
                        //
                        // //    Create the new listitem.
                        // const listitem = document.createElement("li");
                        // listitem.innerHTML = `<a href="/work">Our work</a>`;
                        //
                        // //    Add the listitem to the listview.
                        // listview.append(listitem);
                        //
                        // //    Update the listview.
                        // api.initListview(listview);
                    }
                );
        });

        // e = this.playground();
        // const e = this.basic();
        // const api = e.API;
        // api.open();

        // Open the sidebar, depending on the page (or class?)
        // api.open();
        // const panel = document.querySelector( "#my-panel" );
        // api.openPanel( panel );

        // this.msgTarget.innerHTML = 'Hello Stimulus!';
    }

    toggle() {
        this.toggleableTarget.classList.toggle('hidden')
    }


    tutorial() {
        Mmenu.configs.classNames.selected = "active";
        Mmenu.configs.offCanvas.page.selector = "#page";

                const menu = new Mmenu( this.treeTarget, {
                    slidingSubmenus: false,
                    extensions: ["theme-dark"]
                });
                return menu;
    }
    basic() {
        var e = new Mmenu( this.treeTarget, {
            slidingSubmenus: true,
            transitionDuration: 0.01,
            classNames: {
                selected: "active"
            },
            // "offCanvas": false,
            "extensions": [
                "pagedim-black",
                "theme-dark",
                // "fx-menu-slide",
                // "fx-panels-none",
            ]
        });
        return e;
    }

    playground() {
        var e = new Mmenu(this.treeTarget, {
                extensions: {
                    all: ["theme-white", "pagedim-black"],
                    "(max-width: 767px)": ["fx-menu-slide"]
                },
                wrappers: ["bootstrap"],
                hooks: {
                    "setSelected:before": (panel) => {
                        console.warn(panel);
                    },
                    "openPanel:start": ( panel ) => {

                        console.log( "Started opening pane: " + panel.id, panel );
                    },
                    "openPanel:finish": ( panel ) => {
                        console.log( "Finished opening panel: " + panel.id );
                    }
                },

                counters: !0,
                // iconPanels: {add: !0, hideDivider: !0, hideNavbar: !0, visible: "first"},
                keyboardNavigation: !0,
                navbar: {title: "mmenu"},
                navbars: [{position: "top", content: ["searchfield"]}, {position: "top"}],
                searchfield: {
                    panel: {
                        add: !0,
                        splash: '<p>What are you looking for?<br />Search the menu or try some of these popular pages:</p><a href="/examples.html">Advanced examples</a><br /><a href="/tutorials/off-canvas">Off-canvas tutorial</a><br /><a href="/download.html">Download the plugin</a><br /><a href="/wordpress-plugin">mmenu WordPress plugin</a><br /><br /><small>Documentation:</small><br .><a href="/docs/core/options.html">Options</a><br /><a href="/docs/extensions">Extensions</a><br /><a href="/docs/addons">Add-ons</a><br /><a href="/docs/core/api.html">API</a>'
                    }
                },
                setSelected: {hover: !0, parent: !0},
                sidebar: {
                    collapsed: {use: 480, hideNavbar: !0, hideDivider: !0},
                    expanded: {use: 800, initial: "remember"}
                }
            },
            {
                offCanvas: {page: {selector: "#page"}}, searchfield: {clear: !0}
            }).API,

            t = $("#hamburger").children(".mburger");

        e.bind("close:finish", function () {
            setTimeout(function () {
                t.attr("href", "#menu")
            }, 100)
        });

        e.bind("open:finish", function () {
            setTimeout(function () {
                t.attr("href", "#page")
            }, 100)
        })
        ;

        return e;
    }


}
