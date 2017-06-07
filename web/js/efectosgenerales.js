
var global;

function efectosLoad (efecto, nombreobjeto){
    
    switch (efecto){
        case 'slideaizquierda'://slide de derecha a izquierda
            $(nombreobjeto).hide();
            $(nombreobjeto).show("slide", { direction: "right" }, 0);
        break;
        case 'slideaderecha'://slide de izquierda a derecha
            $(nombreobjeto).hide();
            $(nombreobjeto).show("slide", { direction: "left" }, 0);
        break;
        case 'slideaabajo'://slide de arriba hacia abajo
            $(nombreobjeto).hide();
            $(nombreobjeto).slideDown(700);
        break;
        case 'fade'://se desvanece
            $(nombreobjeto).hide();
            $(nombreobjeto).fadeIn(350);
        break;
        case 'imghover':
            $(".imghover").imghover({suffix: "-over"});
        break;
    }
}

function efectosUnLoad (efecto, nombreobjeto, destino){
    switch (efecto){
        case 'slideaderecha'://slide de derecha a izquierda
            $(nombreobjeto).hide("slide", { direction: "rigth" }, function(){location.href=destino;});
        break;
        case 'slideaizquierda'://slide de izquierda a derecha
            $(nombreobjeto).hide("slide", { direction: "left" }, function(){location.href=destino;});
        break;
        case 'slideaabajo'://slide de izquierda a derecha
            $(nombreobjeto).hide("slide", { direction: "down" }, function(){location.href=destino;});
        break;
        case 'slideaarriba'://slide de izquierda a derecha
            $(nombreobjeto).hide("slide", { direction: "up" }, function(){location.href=destino;});
        break;
        case 'fade':
            $(nombreobjeto).hide($(nombreobjeto).fadeOut(350), function(){location.href=destino;});
        break;
    }

}

function getCookie(name){
    var cname= name + "=";               
    var dc= document.cookie;             
    if (dc.length > 0) {              
        begin= dc.indexOf(cname);       
        if (begin !== -1) {           
            begin += cname.length;       
            end= dc.indexOf(";", begin);
            if (end === -1) end = dc.length;
            return unescape(dc.substring(begin, end));
        } 
    }
    return null;
}    

function efectoConImghover(efecto, nombreobjeto){
    document.cookie='lista=0';
    efectosLoad(efecto, nombreobjeto);
    $(document).ready(function(){
        if ( getCookie('lista') !== "0"){
            efectosLoad('imghover', nombreobjeto);
        }
        document.cookie='lista=1';
    });
}
    