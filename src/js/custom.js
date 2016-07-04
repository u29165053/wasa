$(function(){

    //Ajax load
    $(document).on('click', '.ajax-load', function(e){
        e.preventDefault();
        href = $(this).attr("data-href");
        m = $(this).attr("rel-method");
        d = $(this).attr("rel-data");
        $.ajax({
            method:m,
            data:d,
            url: href,
            success: function(data){
                $("#mainContent").html(data);
            }
        });
    });


    $(document).on("submit", 'form.ajax-submit', function(event) { 
       event.preventDefault();
       $(".successMsg").hide();
       $(".errorMsgs").hide();
       d = $(this).serialize();
       url = $(this).attr('action');
       m = $(this).attr('method');
       $.ajax({
            url: url,
            method: m,
            data: d,
            dataType: 'json',
            success: function(data){
                console.log(data);
                if(data.error == 1){
                    output = '<div class="alert alert-danger" role="alert">'+data.msg+"</div>";
                    $(".errorMsgs").html(output);
                    $(".errorMsgs").show();

                }else{
                    output = '<div class="alert alert-success" role="alert">'+data.msg+"</div>";
                    $(".successMsg").html(output);
                    $(".successMsg").show();
                }
            }
       });
    });

    $("#uploadMalware").fileinput({
        uploadUrl: "actions/uploadFile.php", // server upload action
        uploadAsync: true,
        maxFileCount: 1,
        showUploadedThumbs: false,
        showPreview: false,
        elErrorContainer: "#error"
    });

    $("#uploadMalware").on('fileuploaded', function(event, data, previewId, index) {
        if(data.response.error == 1){
           var ht = data.response.msg;
        }else{
    	   var link = '<a href="http://'+data.response.muestra+'">muestra del malware</a>';
    	   var pwd = '<span class="label label-default">'+data.response.pwd+'</span>';
    	   var ht = '<p>Puedes descargar la '+link+', o ver el '+data.response.url+'. La contraseña es '+pwd+'</p>.';
            
        }
	    $("#successMsg").html(ht);
	    $("#successMsg").show();
	    console.log(data.response);
	});

	$('#uploadMalware').on('fileclear', function(event, id) {
	    $("#successMsg").hide();
    });
});