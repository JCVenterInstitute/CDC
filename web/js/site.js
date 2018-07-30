/*
$(function(){
$("#login-form").validate({
	  submitHandler: function(form){
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "includes/dologin.php",
			data: $('#login-form').serialize(),
			beforeSend: function(){
				$('#login-response').show();
			},
			success: function(response) {
				if(response.status === true) window.location.href="index.php";
				else $("#login-response").html(response.message);
			}
		});
	}
});
});*/
