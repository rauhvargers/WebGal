<?php
    $this->load->helper('form');
    $this->load->view("shared/app_header");
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
	<dt>Nosaukums</dt>
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
		    echo form_dropdown("gallery_id", $user_galleries, $gal->id);?>
	    </dd>
    </dl>
     <?php echo form_submit("save", "Saglabāt attēlu")?>
     <?php echo form_close(); ?>	
      
</article>

<?php
    $this->load->view("shared/app_footer");
?>