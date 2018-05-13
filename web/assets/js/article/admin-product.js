$(function() {
    $('#deleteProduct').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) ;
        var name= button.data('name-product');
        var path_delete= button.data('path-delete');

        var modal = $(this);
        modal.find('.modal-title').text(name);
        modal.find('.modal-content #deleteAction').attr('href',path_delete);
    });
    $('#deleteProduct #deleteAction').on('click',function(e){
        /*e.preventDefault();*/
        $('#deleteProduct').modal('hide');
    });
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) ;
        var name= button.data('name-item');
        var path_delete= button.data('path-delete');

        var modal = $(this);
        modal.find('.modal-title').text(name);
        modal.find('.modal-content #deleteAction').attr('href',path_delete);
    });
    $('#deleteModal #deleteAction').on('click',function(e){
        /*e.preventDefault();*/
        $('#deleteModal').modal('hide');
    });

        $('.category_select').on('change',function () {
            var id_parent=parseInt($(this).val());
            $('.subcategory_select').parent().parent().show();
            $('.subcategory_select').prop('disabled',true).attr('required','required');
            $.ajax({
                method: 'POST',
                url:Routing.generate('subcategory_find'),
                data: { id_parent: id_parent},
                dataType: 'json',
            }).done(function( jsonData ) {
                var html="<option value></option>";
                if(jsonData.data!=0 && jsonData.data.length>0){
                    $.each(jsonData.data,function (index,value) {
                        //console.log(value);
                        html+="<option value='"+value.id+"'>"+value.nameFr+"</option>";
                    });

                }else{
                    $('.subcategory_select').parent().parent().hide();
                    $('.subcategory_select').removeAttr('required')
                }


                $('.subcategory_select').prop('disabled',false).html(html);

            });

        });

        $('.paginate').on('click',function(event){
            event.preventDefault();
            $('#form_filters').attr('action',$(this).attr('href'));
            $('#form_filters').submit();
        });


    });
$(document).ready(function(){

        $('.family_select').on('change',function () {
            $('.category_select').prop('disabled',true);
            var id_family=parseInt($(this).val());
            $.ajax({
                method: 'POST',
                url: Routing.generate('family_find'),
                data: { id_family: id_family},
                dataType: 'json',
            }).done(function( jsonData ) {
                var html="<option value></option>";
                if(jsonData.data!=0 && jsonData.data.length>0) {
                    $.each(jsonData.data, function (index, value) {
                        console.log(value);
                        html += "<option value='" + value.id + "'>" + value.nameFr + "</option>";
                    });
                }
                $('.category_select').prop('disabled',false).html(html);

            });

        });
        $("#status_order").on('change',function(){
            var id_status=parseInt($(this).val());
            var id_order=parseInt($('#order_id').val());
            var is_cancel=$(this).find(':selected').data('cancel');

            if(!is_cancel){

                editStatusOrder(this,id_status,id_order,null);
            }else
            {
                $('#message_cancel').modal('show');
            }

        });
        $('#send_cancel_message').on('click',function(){
            var id_status=parseInt($('#status_order').val());
            var id_order=parseInt($('#order_id').val());
            var message=$('#message_cancel #message').val();
            $('#message_cancel #message').val('');
            $('#message_cancel').modal('hide');
            if(message==''){
                $.jGrowl("Message requis pour changer le status !!", {
                    header: 'Erreur !',
                    theme: 'bg-danger'
                });
            }
            else
                $(this).prop('disabled',true);
                editStatusOrder('#status_order',id_status,id_order,message);
                $(this).prop('disabled',false);
        });

    $("#send_mail").on('click',function(){

        var id_order=parseInt($('#order_id').val());
        var subject=$('#subject').val();
        var message=$('#message').val();
        $(this).prop('disabled',true);
        $.ajax({
            method: 'POST',
            url: Routing.generate('order_send_mail'),
            data: { order_id: id_order,message:message,subject:subject},
            dataType: 'json',
        }).done(function( jsonData ) {
            console.log(jsonData);
            if(jsonData){
                if(!jsonData.error){
                    $.jGrowl('Mail envoyé avec succés', {
                        header: 'Success !',
                        theme: 'bg-success'
                    });
                }else{
                    $.jGrowl("Mail et object requis", {
                        header: 'Erreur !',
                        theme: 'bg-danger'
                    });
                }

            }else{
                $.jGrowl("Une erreur s'est produite !!", {
                    header: 'Erreur !',
                    theme: 'bg-danger'
                });
            }

        }).always(function (data) {
            $('#form_send_mail')[0].reset();
            $('#send_mail').prop('disabled',false);
        });
    });
    initAjaxProcess('#form_add_currency','.currency','#add_currency');
    initAjaxProcess('#form_add_brand','.brandSelect','#add_brand');
    //Add currency Ajx process
    /*$('#form_add_currency').on('submit',function (e) {

        e.preventDefault();

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize()
        })
            .done(function (data) {
                if (typeof data.success !== 'undefined') {
                    console.log(data);
                    $(".currency").append('<option value="'+data.unity_id+'" selected="selected">'+data.unity_symbol+'</option>');
                    $('#form_add_currency')[0].reset();
                    $.jGrowl('Ajout effectué avec success', {
                        header: 'Success !',
                        theme: 'bg-success'
                    });
                    $("#add_currency").modal('hide');
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                $.jGrowl("Une erreur s'est produite !!", {
                    header: 'Erreur !',
                    theme: 'bg-danger'
                });

            });
    });*/
});

function editStatusOrder(element,id_status,id_order,message_cancel){
    $(element).prop('disabled',true);
    $.ajax({
        method: 'POST',
        url: Routing.generate('orders_status',{'id':id_order}),
        data: { id_status: id_status,message_cancel:message_cancel},
        dataType: 'json',
    }).done(function( jsonData ) {

        if(jsonData){
            if(jsonData.message){
                $.jGrowl("Message requis pour changer le status !!", {
                    header: 'Erreur !',
                    theme: 'bg-danger'
                });
            }else{
                $("#status_info_order").text(jsonData.data);
                $.jGrowl('Mise à jour affectué avec succés', {
                    header: 'Success !',
                    theme: 'bg-success'
                });
            }


        }else{
            $.jGrowl("Une erreur s'est produite !!", {
                header: 'Erreur !',
                theme: 'bg-danger'
            });
        }

    }).always(function (data) {
        $('#status_order').prop('disabled',false);
    });
}

function initAjaxProcess(element,selectelement,modal){

    $(element).on('submit',function (e) {

        e.preventDefault();

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize()
        })
            .done(function (data) {
                if (typeof data.success !== 'undefined') {
                    console.log(data);
                    $(selectelement).append('<option value="'+data.id+'" selected="selected">'+data.name+'</option>');
                    $(element)[0].reset();
                    $.jGrowl('Ajout effectué avec success', {
                        header: 'Success !',
                        theme: 'bg-success'
                    });
                    $(modal).modal('hide');
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                $.jGrowl("Une erreur s'est produite !!", {
                    header: 'Erreur !',
                    theme: 'bg-danger'
                });

            });
    });
}