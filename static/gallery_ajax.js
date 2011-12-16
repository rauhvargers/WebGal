

$(document).ready(function(){
    
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
			    var save = $("<img src='/netbeans/WebGal/static/save_icon.gif' class='save' />");
			    save.attr("itemlink" , self.attr("href") );
			    save.on("click", saveEdits);
			    self.parent().append(save);
			    
			    //un saitīte pazūd
			    self.replaceWith(inp);
			    
			}
			
		    );
		});
    
});


function saveEdits(){
    var self = $(this);
    var href = self.attr("itemlink");
    var inputbox = self.parent().find("input");
    
    var newLink = $("<a />");
    var newText = inputbox.val();
    newLink.attr("href", href);
    newLink.text(newText);
    inputbox.replaceWith(newLink);
    self.remove();
    
    //un tagad patiesībā tikai sāksim
    var itemid = href.match(/\d+\/?$/)[0];
    var posturl = "http://localhost/netbeans/WebGal/photo/updatetitle/" + itemid;
    
    $.ajax( {
		url: posturl,
		type: "POST",
		data : {id: itemid, title: newText}
	    })
	 .success(function(){alert("save ok!");})
	 .fail(function(){alert("save not ok!");})

}