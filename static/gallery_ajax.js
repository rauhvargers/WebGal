
/*
/$(document).ready(function(){
    var image = $("<img src='/netbeans/WebGal/static/edit_icon.png' class='edit' />");
    $("#galleryItems li").append(image);
    $("#galleryItems li img")
		.css("cursor", "pointer")
		.bind("click", function(){
		    var self =$(this);
		    var hrefs =self.closest("li").find("a");
		    hrefs.map( 
			function (){
			    var self = $(this)
			    var txt= self.text();
			    var inp = $("<input type='text' class='edit' />");
			    inp.val(txt);

			    //pieliek saglabāšanas ikonu aiz sevis
			    var save ="<img src='/netbeans/WebGal/static/save_icon.gif' class='save' />";
			    self.parent().append(save);
			    
			    //un saitīte pazūd
			    self.replaceWith(inp);
			    
			}
			
		    );
		});
    
});
*/