// JavaScript Document
	$(function() {
			load(1);
		});
		function load(page){
			var query=$("#q").val();
			var per_page=10;
			var parametros = {"action":"ajax","page":page,'query':query,'per_page':per_page};
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'ajax/listar_Fichas.php',
				data: parametros,
				 beforeSend: function(objeto){
				$("#loader").html("Cargando...");
			  },
				success:function(data){
					$(".outer_div").html(data).fadeIn('slow');
					$("#loader").html("");
				}
			})
		}

	$('#deleteFichaModal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget) // Button that triggered the modal
		var id = button.data('id') 
		alert(id);
		  $('#delete_id').val(id)
		})
		
	$("#delete_ficha").submit(function(event){
		var parametros=$(this).serialize();
			$.ajax({
					type: "POST",
					url: "ajax/borrarFicha.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados").html("Enviando...");
					  },
					success: function(datos){
					$("#resultados").html(datos);
					load(1);
					$('#deleteFichaModal').modal('hide');
				  }
			});
		  event.preventDefault();
		});
