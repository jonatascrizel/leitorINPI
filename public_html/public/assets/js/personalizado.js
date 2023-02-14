

function checkall(elem_class){
    //verifica se tem a informaçaõ de tudo marcado
    var checked = !$("#"+elem_class).data('checked');
    //marca ou desmarca todos da categoria
    $("."+elem_class).prop('checked', checked);
    //altera informação do title
    $("#"+elem_class).prop('title', checked ? 'Desmarcar todos dessa categoria' : 'Marcar todos dessa categoria' );
    //aplica a ação em todos os checkbox da categoria
    $("#"+elem_class).data('checked', checked);
}

function validaFormXML(){
    $('#arquivoXML').removeAttr('accept');
    if($('#formFiltros').valid()) {
       //waitingDialog.show('Processando. Por gentileza, aguarde.', {progressType: 'danger'});
       $('#btn_sbm_xml').attr('disabled','disabled');
       $('#btn_sbm_xml').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Processando...</span>');
       $('#formFiltros').submit();
    } else {
        $('#arquivoXML').attr('accept', '.xml');
        swal("Verifique os campos em vermelho.", 'Preencha todos os dados corretamente', "error");
    }
}

function validaFormXLS(){
    $('#arquivoXLSX').removeAttr('accept');
    if($('#formCadastro').valid()) {
       //waitingDialog.show('Processando. Por gentileza, aguarde.', {progressType: 'danger'});
       $('#btn_xlsx').attr('disabled','disabled');
       $('#btn_xlsx').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Processando...</span>');
       $('#formCadastro').submit();
    } else {
        $('#arquivoXLSX').attr('accept', '.xml');
        swal("Verifique os campos em vermelho.", 'Preencha todos os dados corretamente', "error");
    }
}

function validaForm(){
    if($('#formCadastro').valid()) {
        $('#formCadastro').submit();
    } else {
        swal("Verifique os campos em vermelho.", 'Preencha todos os dados corretamente', "error");
    }
}

$(window).on('load', function(){

    $.validator.setDefaults({
        errorElement: "em",
        errorPlacement: function ( error, element ) {
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).addClass( "is-invalid" );//.removeClass( "is-valid" );
            $(element).parents('.form-group').addClass("text-danger");
        },
        unhighlight: function ( element, errorClass, validClass ) {
            $( element ).removeClass( "is-invalid" );//.addClass( "is-valid" )
            $(element).parents('.form-group').removeClass("text-danger");
        },
        rules:{
            'arquivoXML':{required:true, extension: "xml"},
            //'filtros[]':{required: true, minlength: 1}
        }
    });


});