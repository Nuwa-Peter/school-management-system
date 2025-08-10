import './bootstrap';

import Alpine from 'alpinejs';
import TomSelect from 'tom-select';

// Import Tom-select CSS (you can choose other themes like 'tom-select.default.css')
import 'tom-select/dist/css/tom-select.bootstrap5.css';

window.Alpine = Alpine;
window.TomSelect = TomSelect;

Alpine.start();

// Initialize Tom-select on elements with the 'tom-select' class
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.tom-select').forEach((el) => {
        new TomSelect(el, {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    });
});

// FullCalendar
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

window.Calendar = Calendar;
window.dayGridPlugin = dayGridPlugin;
window.timeGridPlugin = timeGridPlugin;
window.interactionPlugin = interactionPlugin;
