import {Controller} from "@hotwired/stimulus";
// import $ from 'jquery'; // for datatables.
// // import {SurvosDataTable} from 'survos-datatables';
import {default as axios} from "axios";
import 'datatables.net-scroller';
// import 'datatables.net-searchpanes-bs5'
import 'datatables.net-fixedheader-bs5';
// import {Modal} from "bootstrap"; !!
// https://stackoverflow.com/questions/68084742/dropdown-doesnt-work-after-modal-of-bootstrap-imported
import Modal from 'bootstrap/js/dist/modal';

// require('./js/Components/DataTables');
const DataTable = require('datatables.net');

// @todo: generalize this
require('./../js/Routing');

require('datatables.net-bs5');
require('datatables.net-select-bs5');
require('datatables.net-buttons-bs5');

// import cb from "../js/app-buttons";


const contentTypes = {
    'PATCH': 'application/merge-patch+json',
    'POST': 'application/json'
};

export default class extends Controller {
    static targets = ['table', 'modal', 'modalBody', 'fieldSearch', 'message'];
    static values = {
        apiCall: String,
        sortableFields: String,
        filter: String
    }

    connect() {
        super.connect(); //
        this.sortableFields = JSON.parse(this.sortableFieldsValue);
        this.filter = JSON.parse(this.filterValue || '[]')
        console.log('hi from ' + this.identifier, this.sortableFields, this.filter);

        console.log(this.tableTarget ? 'table target exists' : 'missing table target')
        console.log(this.modalTarget ? 'target exists' : 'missing modalstarget')
        // console.log(this.fieldSearch ? 'target exists' : 'missing fieldSearch')
        console.log(this.sortableFieldsValue);
        console.assert(this.modalTarget, "Missing modal target");
        this.that = this;
        this.dt = this.initDataTable(this.tableTarget);

    }

    openModal(e) {
        console.error('yay, open modal!', e, e.currentTarget, e.currentTarget.dataset);

        this.modalTarget.addEventListener('show.bs.modal', (e) => {
            console.log(e, e.relatedTarget, e.currentTarget);
            // do something...
        });

        this.modal = new Modal(this.modalTarget);
        console.log(this.modal);
        this.modal.show();

    }

    createdRow(row, data, dataIndex) {
        // we could add the thumbnail URL here.
        // console.log(row, data, dataIndex, this.identifier);
        // let aaController = 'projects';
        // row.classList.add("text-danger");
        // row.setAttribute('data-action', aaController + '#openModal');
        // row.setAttribute('data-controller', 'modal-form', {formUrl: 'test'});
    }

    notify(message) {
        console.log(message);
        this.messageTarget.innerHTML = message;
    }


    handleTrans(el) {
        console.log(el);
        let transitionButtons = el.querySelectorAll('button.transition');
        // console.log(transitionButtons);
        transitionButtons.forEach(btn => btn.addEventListener('click', (event) => {
            const isButton = event.target.nodeName === 'BUTTON';
            if (!isButton) {
                return;
            }
            console.log(event, event.target, event.currentTarget);

            let row = this.dt.row(event.target.closest('tr'));
            let data = row.data();
            console.log(row, data);
            this.notify('deleting ' + data.id);

            // console.dir(event.target.id);
        }));

        if (0)
            $('.transition').click(function (e) {
                // console.log(e, e.target);
                var rowId = $(this).closest('tr').attr('id');
                // this also works: var rowId2 = $(e.target).closest('tr').attr('id');
                let transition = $(e.target).closest('button').data('transition');
                console.warn(transition, rowId);
                if (transition === undefined) {
                    console.error("Undefined transition", rowId, $(e.target));
                }
                $("#" + rowId).remove();

                if (transition === 'edit') {
                    let url = $(e.target).closest('button').data('url');

                    // const url = Routing.generate('article_edit', {articleId: rowId});
                    var strWindowFeatures = "menubar=on,location=no,resizable=yes,scrollbars=yes,status=yes";
                    let newWindow = window.open(url, "Article_" + rowId, strWindowFeatures);
                    // var newWindow = window.open(url);
                    newWindow.focus()
                    return false;


                } else {
                    cb.transitionHeadlines(this, transition, rowId, 'article_transition')
                        .done(function (data) {
                            console.log(data);
                        });

                }
            });
    }

    requestTransition(route, entityClass, id) {

    }

    addButtonClickListener(dt) {
        console.log("Listening for transition events");

        dt.on('click', 'tr td button.transition', ($event) => {
            console.log($event.currentTarget);
            let target = $event.currentTarget;
            var data = dt.row(target.closest('tr')).data();
            let transition = target.dataset['t'];
            console.log(transition, target);
            console.log(data, $event);
            this.that.modalBodyTarget.innerHTML = transition;
            this.modal = new Modal(this.modalTarget);
            this.modal.show();

        });

        dt.on('click', 'tr td button .modal', ($event, x) => {
            console.log($event, $event.currentTarget);
            var data = dt.row($event.currentTarget.closest('tr')).data();
            console.log(data, $event, x);

            let btn = $event.currentTarget;
            let modalRoute = btn.dataset.modalRoute;
            if (modalRoute) {
                this.modalBodyTarget.innerHTML = data.code;
                this.modal = new Modal(this.modalTarget);
                this.modal.show();
                console.assert(data.uniqueIdentifiers, "missing uniqueIdentifiers, add @Groups to entity")
                let formUrl = Routing.generate(modalRoute, {...data.uniqueIdentifiers, _page_content_only: 1});

                axios({
                    method: 'get', //you can set what request you want to be
                    url: formUrl,
                    // data: {id: varID},
                    // headers: {
                    //     _page_content_only: '1' // could send blocks that we want??
                    // }
                })
                    .then(response => this.modalBodyTarget.innerHTML = response.data)
                    .catch(error => this.modalBodyTarget.innerHTML = error)
                ;
            }

        });
    }

    addRowClickListener(dt) {
        dt.on('click', 'tr td', ($event) => {
            let el = $event.currentTarget;
            console.log($event, $event.currentTarget);
            var data = dt.row($event.currentTarget).data();
            var btn = el.querySelector('button');
            console.log(btn);
            let modalRoute = null;
            if (btn) {
                console.error(btn, btn.dataset, btn.dataset.modalRoute);
                modalRoute = btn.dataset.modalRoute;
            }


            console.error(el.dataset, data, $event.currentTarget,);
            console.log(this.identifier + ' received an tr->click event', data, el);

            if (el.querySelector("a")) {
                return; // skip links, let it bubble up to handle
            }

            if (modalRoute) {
                this.modalBodyTarget.innerHTML = data.code;
                this.modal = new Modal(this.modalTarget);
                this.modal.show();
                console.assert(data.uniqueIdentifiers, "missing uniqueIdentifiers, add @Groups to entity")
                let formUrl = Routing.generate(modalRoute, data.uniqueIdentifiers);

                axios({
                    method: 'get', //you can set what request you want to be
                    url: formUrl,
                    // data: {id: varID},
                    headers: {
                        _page_content_only: '1' // could send blocks that we want??
                    }
                })
                    .then(response => this.modalBodyTarget.innerHTML = response.data)
                    .catch(error => this.modalBodyTarget.innerHTML = error)
                ;
            }
        });
    }

    initDataTable(el) {
        let apiPlatformHeaders = {
            Accept: 'application/ld+json',
            'Content-Type': 'application/json'
        };

        // let dt = $(el).DataTable({
        let dt = new DataTable(el, {
            createdRow: this.createdRow,
            // paging: true,
            scrollY: '70vh', // vh is percentage of viewport height, https://css-tricks.com/fun-viewport-units/
            // scrollY: true,
            // displayLength: 50, // not sure how to adjust the 'length' sent to the server
            // pageLength: 15,
            columnDefs: this.columnDefs,
            orderCellsTop: true,
            fixedHeader: true,

            deferRender: true,
            // scrollX:        true,
            scrollCollapse: true,
            scroller: {
                rowHeight: 90,
                displayBuffer: 10,
                loadingIndicator: true,
            },
            // "processing": true,
            serverSide: true,

            initComplete: (obj, data) => {
                this.handleTrans(el);
                console.log(obj, data);
                let xapi = new DataTable.Api(obj);
                console.log(xapi);
                // xapi.
                console.log(xapi.table);

                // this.addRowClickListener(dt);
                this.addButtonClickListener(dt);

                return; // forget it, not worth the effort.


                // https://datatables.net/reference/api/
                // console.error(obj.oApi, el, dt);
                // console.warn(Object.getOwnPropertyNames(obj).filter(item => typeof obj[item] === 'function'));
                // console.warn(Object.getOwnPropertyNames(obj).filter(item => typeof obj[item] !== 'function'));
                let tr = el.querySelector('thead tr');
                let trClone = tr.cloneNode(true);

                trClone.classList.add('filters');

                // i give up on putting it in the table, so move it to above the table
                el.querySelector('thead').insertBefore(trClone, tr);
                // this.fieldSearchTarget.append(trClone);
                // console.log(el);
                // el.querySelector('.js-dt-info').insert(trClone);


                console.log(el.querySelector('thead'));

                // const trClone = JSON.parse(JSON.stringify(tr)); // {...tr}; //  Object.assign({}, tr);
                console.warn(trClone);

                // $('thead tr').clone(true).addClass('filters').appendTo( '#example thead' );

                console.error('init complete, set up footer');
                console.log(trClone.childNodes);

                let columns = this.columns();
                for (let i = 0; i < trClone.children.length; i++) {
                    let th = trClone.children[i];
                    let column = columns[i];

                    const input = document.createElement("input");
                    input.setAttribute("type", "text");
                    input.setAttribute("placeholder", column.data);
                    th.appendChild(input);

                    // console.log(th, column);
                }
                return;

                trClone.children.forEach((node, index, parent) => {
                    console.log(index, node);
                });
                this.columns().forEach((column, index) => {
                    // var cell = trClone.insertCell(index);
                    var headerCell = document.createElement("TH");
                    var cell = trClone.append(headerCell);

                    // console.log(column, index, cell);
                    // cell.innerHTML = column.data;

                    const input = document.createElement("input");
                    input.setAttribute("type", "text");
                    input.setAttribute("placeholder", column.data);
                    headerCell.appendChild(input);
                });
                console.log(trClone);

                // this.initFooter(trClone);
                // var api = event.api();
                return;


                var api = this.api();
                // For each column
                console.log(api.columns());
                api.columns().eq(0).each(function (colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
                    var title = $(cell).text();
                    $(cell).html('<input type="text" placeholder="' + title + '" />');
                    // On every keypress in this input
                    $('input', $('.filters th').eq($(api.column(colIdx).header()).index()))
                        .off('keyup change')
                        .on('keyup change', function (e) {
                            e.stopPropagation();
                            // Get the search value
                            $(this).attr('title', $(this).val());
                            var regexr = '({search})'; //$(this).parents('th').find('select').val();
                            var cursorPosition = this.selectionStart;
                            // Search the column for that value
                            api
                                .column(colIdx)
                                .search((this.value != "") ? regexr.replace('{search}', '(((' + this.value + ')))') : "", this.value != "", this.value == "")
                                .draw();
                            $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
                        });
                });
            },

            // dom: '<"js-dt-buttons"B><"js-dt-info"i>ft',
            dom: '<"js-dt-buttons"B><"js-dt-info"i>ft',
            buttons: [], // this.buttons,
            columns: this.columns(),
            ajax: (params, callback, settings) => {

                console.warn(params, settings);
                // this is the data sent to API platform!


                let apiParams = this.dataTableParamsToApiPlatformParams(params);
                // this.debug &&
                // console.error(params, apiParams);
                // console.log(`DataTables is requesting ${params.length} records starting at ${params.start}`, apiParams);

                Object.assign(apiParams, this.filter);
                console.log(params, apiParams, this.filter);
                axios.get(this.apiCallValue, {
                    params: apiParams,
                    headers: apiPlatformHeaders
                })
                    .then((response) => {
                        // handle success
                        let hydraData = response.data;
                        var total = hydraData['hydra:totalItems'];
                        console.log(response);
                        var itemsReturned = hydraData['hydra:member'].length;
                        let first = (params.page - 1) * params.itemsPerPage;
                        if (params.search.value) {
                            console.log(`dt search: ${params.search.value}`);
                        }

                        console.log(`dt request: ${params.length} starting at ${params.start}`);

                        // let first = (apiOptions.page - 1) * apiOptions.itemsPerPage;
                        let d = hydraData['hydra:member'];
                        if (total) {
                            console.log(d[0]);
                        }
                        // console.log(d, itemsReturned, total, params.draw);

                        // if there's a "next" page and we didn't get everything, fetch the next page and return the slice.
                        let next = hydraData["hydra:view"]['hydra:next'];
                        let callbackValues = {
                            draw: params.draw,
                            data: d,
                            recordsTotal: total,
                            recordsFiltered: total, //  itemsReturned,
                        }

                        if (next && (params.start > 0)) // && itemsReturned !== params.length
                        {

                            console.log('fetching second page ' + next);
                            axios.get(next, {
                                headers: apiPlatformHeaders,
                            })
                                .then(response => response.data)
                                .then(json => {
                                    d = d.concat(json['hydra:member']);

                                    this.debug && console.log(d.map(obj => obj.id));
                                    if (this.debug && console && console.log) {
                                        console.log(`  ${itemsReturned} (of ${total}) returned, page ${apiOptions.page}, ${apiOptions.itemsPerPage}/page first: ${first} :`, d);
                                    }
                                    d = d.slice(params.start - first, (params.start - first) + params.length);
                                    callbackValues.data = d;

                                    itemsReturned = d.length;

                                    console.log(`2-page callback with ${total} records (${itemsReturned} items)`);
                                    console.log(d);
                                });
                        }
                        console.log(callbackValues);

                        callback(callbackValues);
                    })
                    .catch(function (error) {
                        // handle error
                        console.error(error);
                    })
                ;

            },
        });


        return dt;

    }

    get columnDefs() {
        return [
            // { targets: [0, 1], visible: true},
            {targets: '_all', visible: true, sortable: false, "defaultContent": "~~"}
        ]
    }


    get columns() {
        // if columns isn't overwritten, use the th's in the first tr?  or data-field='status', and then make the api call with _fields=...?
        // or https://datatables.net/examples/ajax/null_data_source.html
        return [
            {title: '@id', data: 'id'}
        ]
    }

    createTransitions(transitionCodes, row, projectId) {
        let str = `<div data-controller="transition" data-transition-id-value="${row.id}">`;

        if (transitionCodes && transitionCodes.length) {
            transitionCodes.forEach((transitionCode) => {
                // console.log(transitionCode);
                const style = getMarkingStyle('transitions', transitionCode);
                let label = style.label; //??
                let btn = `<button 
data-transition-action="click" 
title=" ${label}" class="btn btn-sm btn-app transition 
transition-${transitionCode}" 
data-t="${transitionCode}" 
style="font-size: 1.1rem; background-color: white;"><span class="text-small">${label}</span>`;
                // btn += '<span class="badge bg-warning">3</span>';
                btn += `</button> `;


                // need an edit button story and article
                str += btn;
            });

        }

        const sample = `<btn class="btn btn-app">
                  <span class="badge bg-warning">3</span>
                  <i class="fas fa-bullhorn"></i> Notifications
                </btn>`;

        str += "<span data-transition-target='response'>x</div>";
        str += "</div>";
        return str;
    }

    actions({prefix = null, actions = ['edit', 'show']} = {})
    {
        let icons = {edit: 'fas fa-edit', show: 'fas fa-pencil-square'};
        let buttons = actions.map(action => {
            let modal_route = prefix + action;
            let icon = icons[action];
            // Routing.generate()
            return `<button data-modal-route="${modal_route}" class="btn btn-action-${action}" title="${modal_route}"><span class="action-${action} fas fa-${icon}"></span></button>`;
        });

        // console.log(buttons);
        return {
            title: 'actions',
            render: () => {
                return buttons.join(' ');
            }
        }
        // actions.forEach(action => {
        // })
    }

    c({
          propertyName = null,
          name = null,
          route = null,
          modal_route = null,
          label = null,
          modal = false,
          render = null,
          renderType = 'string'
      } = {})
    {

        // console.log(name, data, this.sortableFields.includes(data));
        if (propertyName === 'enabledTransitions') {
            return {
                name: 'transitions',
                title: 'Transitions',
                data: 'enabledTransitionCodes',
                render: (data, type, row, meta) => {
                    let str = 't: ';
                    str +=  row.enabledTransitionCodes.map(t => `<button data-transition="${t}" class="transition transition-${t}">${t}</button>`).join(' * ');
                    return str;
                    return this.createTransitions(data, row);
                },
            }
        }

            if (render === null) {
                render = (data, type, row, meta) => {
                    // if (!label) {
                    //     // console.log(row, data);
                    //     label = data || propertyName;
                    // }
                    let display = label ? label : data;
                    if (renderType === 'image') {
                        return `<img class="img-thumbnail plant-thumb" alt="${data}" src="${data}" />`;
                    }

                    if (route) {
                        let url = Routing.generate(route, row.uniqueIdentifiers);
                        if (modal) {
                            return `<button class="btn btn-primary"></button>`;
                        } else {

                            return `<a href="${url}">${display}</a>`;
                        }
                    } else {
                        if (modal_route) {
                            return `<button data-modal-route="${modal_route}" class="btn btn-success">${modal_route}</button>`;
                        } else {
                            return "<b>" + data + "</b>"
                            // return this.guessColumn(propertyName).render;
                        }
                    }

                }
            }

            return {
                title: propertyName,
                data: propertyName || '',
                render: render,
                sortable: this.sortableFields.includes(propertyName)
            }
            // ...function body...
        }

    guessColumn(v) {

        let renderFunction = null;
        switch (v) {
            case 'id':
                renderFunction = (data, type, row, meta) => {
                    console.warn('id render');
                    return "<b>" + data + "!!</b>"
                }
                break;
            case 'date':
            case 'newestPublishTime':
            case 'createTime':
                renderFunction = (data, type, row, meta) => {
                    let isoTime = data;
                    let str = isoTime ? '<time class="timeago" datetime="' + data + '">' + data + '</time>' : '';
                    return str;
                }
                break;
            // default:
            //     renderFunction = ( data, type, row, meta ) => { return data; }


        }
        console.error(v);
        let obj = {
            title: v,
            data: v,
        }
        if (renderFunction) {
            obj.render = renderFunction;
        }
        console.warn(obj);
        return obj;
    }


    dataTableParamsToApiPlatformParams(params) {
        console.error(params);
        let columns = params.columns; // get the columns passed back to us, sanity.
        var apiData = {
            page: 1
        };

        if (params.length) {
            apiData.itemsPerPage = params.length;
        }

        // same as #[ApiFilter(MultiFieldSearchFilter::class, properties: ["label", "code"], arguments: ["searchParameterName"=>"search"])]
        if (params.search && params.search.value) {
            apiData['search'] = params.search.value;
        }

        let order = {};
        // https://jardin.wip/api/projects.jsonld?page=1&itemsPerPage=14&order[code]=asc
        params.order.forEach((o, index) => {
            let c = params.columns[o.column];
            if (c.data) {
                order[c.data] = o.dir;
                // apiData.order = order;
                apiData['order[' + c.data + ']'] = o.dir;
            }
            // console.error(c, order, o.column, o.dir);
        });

        params.columns.forEach(function (column, index) {
            if (column.search && column.search.value) {
                // console.error(column);
                let value = column.search.value;
                // check the first character for a range filter operator

                // data is the column field, at least for right now.
                apiData[column.data] = value;
            }
        });
        console.error(apiData);

        if (params.start) {
            // was apiData.page = Math.floor(params.start / params.length) + 1;
            apiData.page = Math.floor(params.start / apiData.itemsPerPage) + 1;
        }

        // add our own filters
        // apiData['marking'] = ['fetch_success'];

        return apiData;
    }

    initFooter(el) {
        return;

        var footer = el.querySelector('tfoot');
        if (footer) {
            return; // do not initiate twice
        }

        var handleInput = function (column) {
            var input = $('<input class="form-control" type="text">');
            input.attr('placeholder', column.filter.placeholder || column.data);
            return input;
        };

        this.debug && console.log('adding footer');
        // var tr = $('<tr>');
        // var that = this;
        // console.log(this.columns());
        // Create an empty <tfoot> element and add it to the table:
        var footer = el.createTFoot();
        footer.classList.add('show-footer-above');

        var thead = el.querySelector('thead');
        el.insertBefore(footer, thead);

// Create an empty <tr> element and add it to the first position of <tfoot>:
        var row = footer.insertRow(0);


// Insert a new cell (<td>) at the first position of the "new" <tr> element:

// Add some bold text in the new cell:
//         cell.innerHTML = "<b>This is a table footer</b>";
        console.log(el);

        this.columns().forEach((column, index) => {
                var cell = row.insertCell(index);

                console.log(column, index);
                // cell.innerHTML = column.data;

                const input = document.createElement("input");
                input.setAttribute("type", "text");
                input.setAttribute("placeholder", column.data);
                cell.appendChild(input);

                // if (column.filter === true || column.filter.type === 'input') {
                //         el = handleInput(column);
                //     } else if (column.filter.type === 'select') {
                //         el = handleSelect(column);
                //     }

                // var cell = row.insertCell(index);
                // var td = $('<td>');
                // if (column.filter !== undefined) {
                //     var el;
                //     if (column.filter === true || column.filter.type === 'input') {
                //         el = handleInput(column);
                //     } else if (column.filter.type === 'select') {
                //         el = handleSelect(column);
                //     }
                //     that.handleFieldSearch(this.el, el, index);
                //
                //     td.append(el);
            }
        );
        // footer = $('<tfoot>');
        // footer.append(tr);
        // console.log(footer);
        // this.el.append(footer);

        // see http://live.datatables.net/giharaka/1/edit for moving the footer to below the header
    }


}
