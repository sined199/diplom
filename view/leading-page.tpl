<div class="page page-1" id="first">
	<div class="container">
		<div class="col-xs-12">
			<h1 class="pagetitle">Rost-d UP</h1>
		</div>
		<div class="col-xs-12 pretitle">
			<h3 class="wow slideInLeft">управляй своими проектами</h3>
			<h3 class="wow slideInRight">вместе с нами</h3>
		</div>
		<div class="col-xs-5">
			<div class="auth-block-home">
				<span>Создай свой первый проект</span><h2>прямо сейчас</h2>
				<button class="start" data-url="/diplom/auth">Начать работу</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("body").css("overflow","hidden");
		$("button.start").click(function(){
			url = $(this).attr("data-url");
			location.href=url;
		})
	})
	var keys = {37: 1, 38: 1, 39: 1, 40: 1};
	var Queue = null;
	var id = 0;
	blocks = new Array("first","second");

	function preventDefault(e) {
	  e = e || window.event;
	  if (e.preventDefault)
	      e.preventDefault();
	  e.returnValue = false;  
	}

	function preventDefaultForScrollKeys(e) {
	    if (keys[e.keyCode]) {
	        preventDefault(e);
	        return false;
	    }
	}

	function disableScroll() {
	  if (window.addEventListener) // older FF
	      window.addEventListener('DOMMouseScroll', preventDefault, false);
	  window.onwheel = preventDefault; // modern standard
	  window.onmousewheel = document.onmousewheel = preventDefault; // older browsers, IE
	  window.ontouchmove  = preventDefault; // mobile
	  document.onkeydown  = preventDefaultForScrollKeys;
	}

	function enableScroll() {
	    if (window.removeEventListener)
	        window.removeEventListener('DOMMouseScroll', preventDefault, false);
	    window.onmousewheel = document.onmousewheel = null; 
	    window.onwheel = null; 
	    window.ontouchmove = null;  
	    document.onkeydown = null;  
	}

	function slideOnBlock(tag){
	    Queue = 1;
	    ofs = $("#"+tag);
	    disableScroll();
	    $("#"+id).addClass("circle-change");
	    setTimeout(function(){
	      enableScroll();
	      Queue = null;
	        //$("body").css("overflow","auto");
	    },1000);  
	    $('body').animate({ scrollTop: $(ofs).offset().top}, 1000);

	}
	$(document).ready(function(){
	  slideOnBlock(blocks[0]);

	  $(".mouse").click(function(){
	    $("#"+id).removeClass("circle-change");
	    id=1;
	    slideOnBlock(blocks[id]);
	  })

	  var elem = document.getElementsByTagName('body');
	    if (elem[0].addEventListener) {
	      if ('onwheel' in document) {
	        // IE9+, FF17+
	        elem[0].addEventListener("wheel", onWheel);
	      } else if ('onmousewheel' in document) {
	        // устаревший вариант события
	        elem[0].addEventListener("mousewheel", onWheel);
	      } else {
	        // Firefox < 17
	        elem[0].addEventListener("MozMousePixelScroll", onWheel);
	      }
	    } else { // IE8-
	      elem[0].attachEvent("onmousewheel", onWheel);
	    }
	    // Это решение предусматривает поддержку IE8-
	    function onWheel(e) { 
	      e = e || window.event;
	      // deltaY, detail содержат пиксели
	      // wheelDelta не дает возможность узнать количество пикселей
	      // onwheel || MozMousePixelScroll || onmousewheel
	      var delta = e.deltaY || e.detail || e.wheelDelta;
	      if(delta == "100"){
	        if(Queue == null){
	          if(id!=blocks.length-1){
	            $("#"+id).removeClass("circle-change");
	            id++;
	            slideOnBlock(blocks[id]);
	          }
	        }          
	      }
	      else if(delta == "-100"){
	        if(id!="0"){
	          $("#"+id).removeClass("circle-change");
	          id--;
	          slideOnBlock(blocks[id]);
	        }
	      }      
	    }

	    $(".circle").click(function(){
	      id_block = $(this).attr("id");
	      if(id_block!=id){
	        $("#"+id).removeClass("circle-change");
	        id = id_block;
	        slideOnBlock(blocks[id_block]);
	      }
	    })
	})
</script>