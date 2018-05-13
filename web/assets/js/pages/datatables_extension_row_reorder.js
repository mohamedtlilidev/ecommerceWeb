/* ------------------------------------------------------------------------------
*
*  # Row Reorder extension for Datatables
*
*  Specific JS code additions for datatable_extension_row_reorder.html page
*
*  Version: 1.0
*  Latest update: Nov 9, 2015
*
* ---------------------------------------------------------------------------- */

$(function() {


    // Table setup
    // ------------------------------

    // Setting datatable defaults
    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        columnDefs: [{
            orderable: false,
            width: '100px',
            targets: [ 5 ]
        }],
        dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
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


    // Basic initialization
    $('.datatable-row-basic').DataTable({
        rowReorder: true
    });


    // Full row selection
    var table_reorder= $('.datatable-row-full').DataTable({
        rowReorder: {
            selector: 'td:nth-child(1)'
        },
        columnDefs: [
            { targets: 0, visible: true }
        ]
    });

    table_reorder.on('row-reorder', function (e, diff, edit) {
        //var result = 'Reorder started on row: '+edit.triggerRow.data()[0]+'<br>';
       // var oldPosition=parseInt(edit.triggerRow.data()[1]);
        var id_category=parseInt(edit.triggerRow.data()[1]);
        for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
            var rowData = table_reorder.row( diff[i].node ).data();
            if(diff[i].oldData===edit.triggerRow.data()[0])
                var newPosition=diff[i].newData

        }
        if(typeof newPosition !=='undefined'){
            reorderCategory(id_category,newPosition);
        }



    });
    // Responsive integration
    $('.datatable-row-responsive').DataTable({
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true
    });


    // Reorder events
    var table = $('.datatable-row-events').DataTable({
        rowReorder: true
    });
 
    // Setup event
    table.on('row-reorder', function (e, diff, edit) {
        var result = 'Reorder started on row: '+edit.triggerRow.data()[1]+'<br>';
 
        for (var i=0, ien=diff.length ; i<ien ; i++) {
            var rowData = table.row( diff[i].node ).data();
 
            result += rowData[1]+' updated to be in position '+
                diff[i].newData+' (was '+diff[i].oldData+')<br>';
        }
 
        $('#event-result').html('Event result:<br>'+result);
    });



    // External table additions
    // ------------------------------

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');


    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
    
});

function reorderCategory(id_category,position){
    $.ajax({
        method: 'POST',
        url: Routing.generate('reorder_category',{'id':id_category}),
        data: {position:position},
        dataType: 'json',
    }).done(function( jsonData ) {
        if(jsonData.success){

                $.jGrowl('Mise à jour affectué avec succés', {
                    header: 'Success !',
                    theme: 'bg-success'
                });
        }else{
            $.jGrowl("Une erreur s'est produite !!", {
                header: 'Erreur !',
                theme: 'bg-danger'
            });
        }

    }).always(function (data) {
    });
}
