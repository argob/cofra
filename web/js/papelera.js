	var seleccionado;
	function selecionar(obj){
		//seleccionado = $($(obj).children()[0]).children().html();
                seleccionado = $($(obj).children()[0]).html(); // sin <a>
                

	}	
	function ajax_arg(ruta){
		if(isFinite(seleccionado)){
			$.post(ruta, 
					null ,
					function(data){ dibujar(data);}
					);   
		}else{
			alert('seleccione un item');
		}	

	}
	function ajax(ruta){
			$.post(ruta, 
					null ,
					function(data){ dibujar(data);}
					);   	
	}	
	function dibujar(data){

			$('#divajax').html('');
			$('#divajax').html(data);

	}
	function elegir(obj){
		selecionar(obj);
		pintar(obj);
		
	}
			
	var global;
	var tabla;
        function pintar(obj){
		tabla = '#papelera'	;
		global= obj;
		cantidad_lineas = $($(tabla).children('tbody')).children().length
		lineas = $($(tabla).children('tbody')).children()
		for(var i=0;i<cantidad_lineas;i++){			
			if(i%2){
				$(lineas[i]).attr('class', 'even');
			}else{
				$(lineas[i]).attr('class', 'odd');
				
			}
		}
		//$(obj).css('background-color', '#666')
		$(obj).attr('class', 'seleccionada');
	
	
	}
	function mensaje(){

	}
	
	function borrar(ruta){
		if(isFinite(seleccionado)){
			if(confirm('Esta Seguro que desea borrar el proveedor seleccionado?'))irApagina(ruta,0);
			}
	}	
        function ActivarTabla(tabla){
            cantidad = $($($(tabla).children()[1]).children()).length;
            for(i=0;i<cantidad;i++){
                    $($($(tabla).children()[1]).children()[i]).click(function(){elegir(this)})
            }
        }
        function irApagina(direccion,sinVerificar){
            if(isFinite(seleccionado)|| sinVerificar == true){ // si no se selecciono ninguno no hace nada, seleccionado esta en undefined.
                    location.href = "http://" + location.host + direccion;
            } else{
                    alert('Seleccione una opcion');
            }
        }
