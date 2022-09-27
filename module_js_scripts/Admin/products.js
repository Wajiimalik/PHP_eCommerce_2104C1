
if ($('#products_list').length) {
    $('#products_list').DataTable({
        bLengthChange: false    ,
        "bDestroy": false,
        language: {
            search: "<i class='ti-search'></i>",
            searchPlaceholder: 'Search here...',
            paginate: {
                next: "<i class='ti-arrow-right'></i>",
                previous: "<i class='ti-arrow-left'></i>"
            }
        },
        columnDefs: [{
            visible: false
        }],
        responsive: true,
        searching: true,
        info: true,
        paging: true,
    });
}