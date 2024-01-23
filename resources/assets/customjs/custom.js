use "strict";

function test(id){
	$.ajax({
		url:"http://192.168.0.113/api/dsrs/"+id,
		success:function(response){

			console.log(response);
		},
		error:function{

			console.log(error);
		}
	});
}