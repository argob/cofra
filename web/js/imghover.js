/**
 *  jquery.popupt
 *  (c) 2008 Semooh (http://semooh.jp/)
 *
 *  Dual licensed under the MIT (MIT-LICENSE.txt)
 *  and GPL (GPL-LICENSE.txt) licenses.
 *
 **/
(function($){
	$.fn.extend({
		imghover: function(opt){
			return this.each(function() {
        opt = $.extend({
            prefix: '',
            suffix: '_o',
            src: '',
            btnOnly: true,
            fade: true,
            fadeSpeed: 500
          }, opt || {});

        var node = $(this);
				if(!node.is('img')&&!node.is(':image')){
          var sel = 'img,:image';
          if (opt.btnOnly) sel = 'a '+sel;
          node.find(sel).imghover(opt);
          return;
        }

        var orgImg = node.attr('src');

        var hoverImg;
        if(opt.src){
          hoverImg = opt.src;
        }else{
          hoverImg = orgImg;
          if(opt.prefix){
            var pos = hoverImg.lastIndexOf('/');
            if(pos>0){
              hoverImg = hoverImg.substr(0,pos-1)+opt.prefix+hoverImg.substr(pos-1);
            }else{
              hoverImg = opt.prefix+hoverImg;
            }
          }
          if(opt.suffix){
            var pos = hoverImg.lastIndexOf('.');
            if(pos>0){
              hoverImg = hoverImg.substr(0,pos)+opt.suffix+hoverImg.substr(pos);
            }else{
              hoverImg = hoverImg+opt.suffix;
            }
          }
        }

        if(opt.fade){
          var offset = node.offset();
          var hover = node.clone(true);
          hover.attr('src', hoverImg);
          hover.css({
            position: 'absolute',
            left: offset.left,
            top: offset.top,
            zIndex: 1000
          }).hide().insertAfter(node);
          node.mouseover(
            function(){
              var offset=node.offset();
              hover.css({left: offset.left, top: offset.top});
              hover.fadeIn(opt.fadeSpeed);
              node.fadeOut(opt.fadeSpeed,function(){node.show()});
            }
          );
          hover.mouseout(
            function(){
              node.fadeIn(opt.fadeSpeed);
              hover.fadeOut(opt.fadeSpeed);
            }
          );
        }else{
          node.hover(
            function(){node.attr('src', hoverImg)},
            function(){node.attr('src', orgImg)}
          );
        }
			});
		}
	});
})(jQuery);
