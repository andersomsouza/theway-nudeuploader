function uniqueId() { return new Date().getTime(); }
function atualizaContador(){
//    $( "#nrContador" ).load( './contador.txt&uid='+uniqueId() );

    $.ajax({
        url: './contador.txt',
        cache: false,
        dataType: "html",
        success: function (data) {
            $("#nrContador").html(data);
            return false;
        }
    });


}
$(document).ready(function(){$('body').css('height',window.innerHeight+"px");
                             atualizaContador();
                            });
$(window).resize(function(){$('body').css('height',window.innerHeight+"px");});
$("#propaganda").load("propaganda.txt");
$('#file-upload').change(function() {
    var filepath = this.value;
    var m = filepath.match(/([^\/\\]+)$/);
    var filename = m[1];
    $('#filename').html(filename);

});

$('#formEnvio').on('submit',function(evt){
    evt.preventDefault();
    //desabilito o botao de enviar e faço o logo girar
    $('input[type=\"submit\"]').attr('disabled','disabled');
    $('#logo').removeClass('fadeIn');
    $('#logo').addClass('w3-spin');
    var url=$(this).attr("action");
    $.ajax({
        url: url,
        type: $(this).attr("method"),
        dataType: "html",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function (data, status)
        { 

            if(data.indexOf('SUCESSO') !== -1){  
                $('#filename').html("Selecione sua foto");
                $('#file-upload').val("");
                alert("Sua foto foi enviada com sucesso! (6)");}
            else{ 
                alert("Erro ao enviar seu nude :( \r\nERRO: "+data);}
        },
        error: function (xhr, desc, err)
        {

            alert("Erro ao enviar seu nude :(");
        }
    }).always(function(){

        $('#logo').removeClass('w3-spin');
        $('input[type=\"submit\"]').attr('disabled',null);
        atualizaContador();

    });     
})