// datatable for products list
if ($('#products_list').length) {
    $('#products_list').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        bLengthChange: true,
        "bDestroy": false,
        language: {
            search: "<i class='ti-search'></i>",
            searchPlaceholder: 'Search here...',
            paginate: {
                "first": '<i class="fas fa-angle-double-left"></i>',
                "last": '<i class="fas fa-angle-double-right"></i>',
                next: "<i class='ti-arrow-right'></i>",
                previous: "<i class='ti-arrow-left'></i>"
            },
            "emptyTable": "No products avaialble.",
            "zeroRecords": "No matching product(s) found",
            "info": "Showing _START_ to _END_ of _TOTAL_ entities",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "(Total _MAX_ products)",
            "thousands": ",",
        },
        columnDefs: [
            {
                visible: true,
                orderable: false,
                targets: 0,
            },
            {
                visible: true,
                orderable: false,
                targets: 6,
            }
        ],
        'order': [[1, 'asc']],
        responsive: true,
        'autoWidth': false,
        searching: true,
        info: true,
        paging: true,
    });
}