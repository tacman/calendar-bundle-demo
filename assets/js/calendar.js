import { Calendar } from '@fullcalendar/core';

import '../styles/calendar.css';

document.addEventListener('DOMContentLoaded', () => {
    var calendarEl = document.getElementById('calendar-holder');

    var calendar = new Calendar(calendarEl, {
        defaultView: 'dayGridMonth',
        editable: true,
        eventSources: [
            {
                url: "{{ path('fc_load_events') }}",
                method: "POST",
                extraParams: {
                    anExtraParam: 'url for example',
                    filters: JSON.stringify({
                        'icsUrl' : 'https://www.officeholidays.com/ics/usa/arizona',
                    })
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
        plugins: [ 'interaction', 'dayGrid', 'timeGrid' ], // https://fullcalendar.io/docs/plugin-index
        timeZone: 'UTC',
    });
    calendar.render();
});
