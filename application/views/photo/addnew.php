<?php

    $data["scripts"]=array(
	"/static/jquery-ui/js/jquery-1.6.2.min.js",
	"/static/jquery-ui/js/jquery-ui-1.8.16.custom.min.js"
    );
    
    $data["stylesheets"] = array(
	"static/jquery-ui/css/ui-lightness/jquery-ui-1.8.16.custom.css"
    );
    
    $this->load->helper('form');
    $this->load->view("shared/app_header",$data);
?>
<article id="main">
    <h3>Jauns attēls</h3>
    
    <?php echo form_open("/photo/savenew"); 
	  echo form_hidden("gallery_id", $gal->id);
    ?>
    
    
    <div id="errors">
     <?php echo validation_errors(); ?>
    </div>
    <dl>
	<dt><?php echo lang("title")?></dt>
	    <dd><?php 
		    echo form_input("title", $pic->title );
		    echo form_error('title'); 
		 ?></dd>
	<dt>Apraksts</dt>
	    <dd><?php 
		    echo form_textarea("description", $pic->description);
		    echo form_error("description");
		 ?></dd>
        <dt>Galerija</dt>
	    <dd><?php 
		    //echo form_dropdown("gallery_id", $user_galleries, $gal->id);
		    echo form_input(array("name"=>"gallery_name", "id"=>"gallery_name", "value"=>""));
		    echo form_input(array("name"=>"gallery_id", "id"=>"gallery_id", "value"=>""));
		    ?>
	    </dd>
    </dl>
     <?php echo form_submit("save", "Saglabāt attēlu")?>
     <?php echo form_close(); ?>	
      
</article>
<script>
    $(document).ready(function(){
	$("#gallery_name").autocomplete({
	    source:"<?php echo site_url("gallery/usergalleries/json")?>",
	    select: function( event, ui ) {
		$("#gallery_name").val(ui.item.label);
		$("#gallery_id").val(ui.item.value);
		return false;
	    }, 
	    focus: function( event, ui ) {
		$( "#gallery_name" ).val( ui.item.label );
		$( "#gallery_id" ).val( ui.item.value );
		return false;
	    }
	    
	})
    });
</script>    
<?php
    $this->load->view("shared/app_footer");
?>