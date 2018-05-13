$(function() {
    $.extend( $.fn.dataTable.defaults, {
        processing: true,
        serverSide: true,
        pageLength: 10,
        sAjaxDataProp: "data",
        autoWidth: false,
        columnDefs: [{
            orderable: false,
            width: '100px'
        }],
        dom: 'r<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            processing: "Loading. Please wait...",
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        drawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
        },
        preDrawCallback: function() {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
        }
    });
});