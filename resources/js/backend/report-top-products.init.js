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

let topProductsTable;

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

    // If table exists, destroy it before reinitializing
    if (topProductsTable) {
        topProductsTable.destroy();
    }

    // Top Selling Products DataTable
    topProductsTable = new DataTable('#tb_data', {
        processing: true,
        serverSide: true,
        ajax: {
            url: `/report/top-products/list`,
            data: {
                start_date: startDate,
                end_date: endDate
            }
        },
        columns: [
            {
                data: null,
                name: 'rank',
                orderable: false,
                searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                className: 'text-center',
                title: '#'
            },
            { data: 'name', name: 'name' },
            {
                data: 'units_sold',
                name: 'units_sold',
                className: 'text-end',
                render: function (data) {
                    return new Intl.NumberFormat('id-ID').format(data);
                }
            },
            {
                data: 'revenue',
                name: 'revenue',
                className: 'text-end',
                render: function (data) {
                    return formatCurrency(data);
                }
            },
        ],
        order: [[2, 'desc']]
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
