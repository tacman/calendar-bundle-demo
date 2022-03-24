import { Controller } from '@hotwired/stimulus';

import { Calendar } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'

// https://stackoverflow.com/questions/68084742/dropdown-doesnt-work-after-modal-of-bootstrap-imported
import Modal from 'bootstrap/js/dist/modal';

export default class extends Controller {

    static targets = ["calendar", "modalBody", "modal"]
    static values = {
        url: String,
    }

    connect() {
        console.log(this.baseUrlValue);
        this.invokeCalendar(this.calendarTarget);
        this.calendarTarget.innerHTML = this.baseUrlValue;
        // compile the lodash template
        // this.resourceTemplate = template(this.resourceTemplateTarget.textContent);
        // this.optionTargets.forEach((el, i) => {
        //     if (el.checked) {
        //         this.loadBranch(el.value)
        //     }
        // });

    }

    openModal(e) {
        console.error('yay, open modal!', e, e.currentTarget, e.currentTarget.dataset);

        // this.modalTarget.addEventListener('show.bs.modal',  (e) => {
        //     console.log(e, e.relatedTarget, e.currentTarget);
        //     // do something...
        // });

        this.modal = new Modal(this.modalTarget);
        console.log(this.modal);
        this.modal.show();

    }


    invokeCalendar(el) {


        let _this = this;

        let calendar = new Calendar(el, {

            defaultView: 'dayGridMonth',
            // events: '/admin/events.json',
            eventSources: [
                {
                    url: this.urlValue,
                    method: "POST",
                    extraParams: {
                        filters: JSON.stringify({})
                    },
                    failure: () => {
                        // alert("There was an error while fetching FullCalendar!");
                    },
                },
            ],
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay',
            },
            timeZone: 'UTC',

            editable: true,
            selectable: true,
            navLinks: true,
            nowIndicator: true,
            headerToolbar: { center: 'dayGridMonth,timeGridWeek,timeGridDay' },
            plugins: [dayGridPlugin,timeGridPlugin,interactionPlugin],
            // plugins: [ 'interaction', 'dayGrid', 'timeGrid' ], // https://fullcalendar.io/docs/plugin-index

            // this navigates to the day selected from month view -> day view
            navLinkDayClick: function(date, jsEvent) {
                calendar.changeView('timeGridDay', date);
            },

            // This method fires when we select a time slot, or range of slots.
            select: (info) => {
                console.log(info);
                this.openModal(info.jsEvent);
                //
                // _this.modalTarget.classList.add('active')
                // _this.start_timeTarget.value = info.start
                // _this.end_timeTarget.value = info.end
                //
                // if (info.allDay = true) {
                //     _this.allDayTarget.setAttribute('checked', true)
                // }

            },

            eventDrop: function (info) {
                let data = _this.data(info)
                Rails.ajax({
                    type: 'PUT',
                    url: info.event.url,
                    data: new URLSearchParams(data).toString()
                })
            },

            eventResize: function (info) {
                let data = _this.data(info)

                Rails.ajax({
                    type: 'Put',
                    url: info.event.url,
                    data: new URLSearchParams(data).toString()
                })
            },

            addEvent: function(info) {
                _this.addEvent( info )
            }

        })
        calendar.render()
    }


    selectBranch(event) {
        console.log(event);
        this.loadBranch(event.srcElement.value);
    }

    loadBranch(branch) {
        this.branchTarget.innerHTML = branch;
        this.commitsTarget.innerHTML = `<li>Loading comits for ${branch}</li>`
        var url = this.baseUrlValue + branch;
        this.debugTarget.innerHTML = url;
        this.debugTarget.href = url;
        fetch(url)
            .then( data => {
                if (!data.ok) {
                    throw Error(data.statusText);
                }
                return data.json();
            }).then( commits => {
            this.commitsTarget.innerHTML = this.resourceTemplate({commits: commits});
        }).catch((error) => {
            this.messagesTarget.innerHTML = error.toString();
            console.error('Error:', error);
        });
    }

    formatDate(v) {
        console.log(v);
        return v.replace(/T|Z/g, ' ')
    }

}

// function truncate (v) {
//     var newline = v.indexOf('\n')
//     return newline > 0 ? v.slice(0, newline) : v
// }

