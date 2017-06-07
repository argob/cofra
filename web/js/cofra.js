/* 
 * Requiere jQuery y mockjax.
 * Autocompleta el texto ingresado en un textbox con las palabras del buffer.
 * El buffer se llena mediante ajax a la url indicada que debe cumplir con formato json.
 * Al seleccionar una opcion sugerida se selecciona el combo indicado, deben usar el mismo id.
 */

function CofraAutocompletar(url,textbox_id,combo_id, funcionSelect){
	var othis = this;
	this.combo = '#'+combo_id;
	this.url = url;
	this.textbox_id = '#' + textbox_id;
	
	this.funcion = function () {
	    'use strict';
	    $.ajax({
		url: this.url,
		dataType: 'json'
	    }).done(function (source) {

		var itemsArray = $.map(source, function (value, key) { return { value: value, data: key }; }),
		    items = $.map(source, function (value) { return value; });

		// Setup jQuery ajax mock:
		$.mockjax({
		    url: '*',
		    responseTime:  200,
		    response: function (settings) {
			var query = settings.data.query,
			    queryLowerCase = query.toLowerCase(),
			    suggestions = $.grep(items, function(item) {
				 return item.toLowerCase().indexOf(queryLowerCase) !== -1;
			    }),
			    response = {
				query: query,
				suggestions: suggestions
			    };

			this.responseText = JSON.stringify(response);
		    }
		});
		
		$(othis.textbox_id).autocomplete({
		    lookup: itemsArray,
		    onSelect: function (suggestion) {
					if(combo_id != null ){
					    $(othis.combo).children('option[value="' +suggestion.data+'"]').attr('selected',true);
					}
					
					if(typeof funcionSelect != 'undefined'){
					    funcionSelect(othis,suggestion);					    
					}
					
		    }
		});
	    });

	};
	this.funcion(this.textbox_id);
}

/* para los mensajes notify, antes era 'create' */
function nuevoMensaje( template, vars, opts ){ 
	return $container.notify("create", template, vars, opts);
}

function CofraBuscador(url,textbox_id,combo_id, funcionSelect){
	var buscador = this;
	this.combo = '#'+combo_id;
	this.url = url;
	this.textbox_id = '#' + textbox_id;
	
	this.funcion = function () {
	    'use strict';
	    $.ajax({
		url: this.url,
		dataType: 'json'
	    }).done(function (source) {

		var itemsArray = $.map(source, function (value, key) { return { value: value, data: key }; }),
		    items = $.map(source, function (value) { return value; });

		// Setup jQuery ajax mock:
		$.mockjax({
		    url: '*',
		    responseTime:  200,
		    response: function (settings) {
			var query = settings.data.query,
			    queryLowerCase = query.toLowerCase(),
			    suggestions = $.grep(items, function(item) {
				 return item.toLowerCase().indexOf(queryLowerCase) !== -1;
			    }),
			    response = {
				query: query,
				suggestions: suggestions
			    };

			this.responseText = JSON.stringify(response);
		    }
		});
		
		$(buscador.textbox_id).filtrolista({
		    lookup: itemsArray,
		    onSelect: function (suggestion) {
					if(combo_id != null ){
					    $(buscador.combo).children('option[value="' +suggestion.data+'"]').attr('selected',true);
					}
					
					if(typeof funcionSelect != 'undefined'){
					    funcionSelect(buscador,suggestion);					    
					}
					
		    }
		});
	    });

	};
	this.funcion(this.textbox_id);
}