//Added Date-range Picker
import DateRangePicker from 'daterangepicker';

const options = {
    locale: {
        format: "YYYY-MM-DD",
    }
};

new DateRangePicker(document.getElementById('daterangepicker'), options);
