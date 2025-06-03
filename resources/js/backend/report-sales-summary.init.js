function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is 0-based
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function getDefaultDateRange() {
    const end = new Date();
    const start = new Date();
    start.setMonth(start.getMonth() - 1);
    return { start, end };
}

function formatCurrency(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value ?? 0)
}

function filterData() {
    const range = document.getElementById('demo-datepicker')._flatpickr.selectedDates;

    let startDate, endDate;

    if (range.length === 2) {
        startDate = formatDate(range[0]);
        endDate = formatDate(range[1]);
    } else {
        // fallback if nothing is selected, default last 1 month
        const defaultRange = getDefaultDateRange();
        startDate = formatDate(defaultRange.start);
        endDate = formatDate(defaultRange.end);
    }

    // Sales Summary
    $.get('/report/sales-summary/list', { start_date: startDate, end_date: endDate }, function(data) {
        $('#total-revenue').text(formatCurrency(data.total_revenue)).attr('data-target', data.total_revenue ?? 0);
        $('#total-orders').text(data.total_orders).attr('data-target', data.total_orders ?? 0);
        $('#total-items').text(data.total_items).attr('data-target', data.total_items ?? 0);
        $('#avg-order-value').text(formatCurrency(data.avg_order_value)).attr('data-target', data.avg_order_value ?? 0);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const { start, end } = getDefaultDateRange();

    flatpickr("#demo-datepicker", {
        mode: "range",
        dateFormat: "d M, Y",
        defaultDate: [start, end]
        // onClose: filterData
    });

    filterData(); // initial load
});
