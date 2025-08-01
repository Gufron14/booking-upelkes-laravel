import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';

window.initBookingCalendar = function(events) {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) {
        console.error('Calendar element not found');
        return;
    }

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin],
        initialView: 'dayGridMonth',
        locale: 'id',
        height: 400,
        events: events,
        eventDisplay: 'block',
        eventDidMount: function(info) {
            // Add tooltip with booking details
            info.el.setAttribute('title', 
                'Customer: ' + info.event.extendedProps.customer + '\n' +
                'Layanan: ' + info.event.extendedProps.description
            );
        },
        eventClick: function(info) {
            // Show booking details in alert (you can customize this)
            alert(
                'Booking Details:\n' +
                'Room/Space: ' + info.event.title + '\n' +
                'Customer: ' + info.event.extendedProps.customer + '\n' +
                'Service: ' + info.event.extendedProps.description + '\n' +
                'Start: ' + info.event.start.toLocaleDateString() + '\n' +
                'End: ' + (info.event.end ? info.event.end.toLocaleDateString() : 'Same day')
            );
        },
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek'
        },
        buttonText: {
            today: 'Hari Ini',
            month: 'Bulan',
            week: 'Minggu'
        }
    });
    
    calendar.render();
};
