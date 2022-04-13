import { Controller } from '@hotwired/stimulus';

import { Calendar } from '@fullcalendar/core';
import iCalendarPlugin from '@fullcalendar/icalendar'
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
// import './main.css';

// es module import:
import { ray } from 'node-ray/web';

// https://stackoverflow.com/questions/68084742/dropdown-doesnt-work-after-modal-of-bootstrap-imported
import Modal from 'bootstrap/js/dist/modal';

export default class extends Controller {

    static targets = ["calendar", "modalBody", "modal"]
    static values = {
        url: String,
        icsUrl: { type: String, default: '' },
        format: { type: String, default: 'json' }
    }

    connect() {

        console.log('hello from ' + this.identifier + ' ' + this.urlValue);
        // this.demo(this.calendarTarget);
        this.parseIcsCalendar(this.calendarTarget);

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

    parseIcsCalendar(calendarEl)
    {
        let eventSources = [
            {
                url: this.urlValue,
                // method: "POST",
                extraParams: {
                    anExtraParam: 'url for example',
                    filters: JSON.stringify({
                        'icsUrl' : this.icsUrlValue,
                    })
                },
                failure: (e) => {
                    console.error(e);
                },
            },
        ];
        console.log(eventSources);

        var calendar = new Calendar(calendarEl, {
            plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin ],
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            // initialDate: '2018-01-12',
            navLinks: true, // can click day/week names to navigate views
            editable: true,
            dayMaxEvents: true, // allow "more" link when too many events
            // events: [
            //     {
            //         title: 'All Day Event',
            //         start: '2018-01-01',
            //     },
            //     {
            //         title: 'Click for Google',
            //         url: 'http://google.com/',
            //         start: '2018-01-28'
            //     }
            // ],
            //
            eventSources: eventSources,
            timeZone: 'UTC',
        });
        calendar.render();

    }

    demo(calendarEl)
    {

            var calendar = new Calendar(calendarEl, {
                plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin ],
                // plugins: [ dayGridPlugin ],
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                initialDate: '2018-01-12',
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                dayMaxEvents: true, // allow "more" link when too many events
                events: [
                    {
                        title: 'All Day Event',
                        start: '2018-01-01',
                    },
                    {
                        title: 'Click for Google',
                        url: 'http://google.com/',
                        start: '2018-01-28'
                    }
                ]
            });

            calendar.render();
    }

}

// function truncate (v) {
//     var newline = v.indexOf('\n')
//     return newline > 0 ? v.slice(0, newline) : v
// }

