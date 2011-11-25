<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
	<?php echo link_tag("static/site.css")?>	
    </head>
    <body>
	
	<?php echo form_open("gallery/savenew"); ?>

	<h2>Jauna galerija</h2>
	
	<div id="errors">
	    <?php echo validation_errors(); ?>
	</div>
	
	<fieldset>
	
	    <dl>
		<dt>Nosaukums</dt>
		<dd>
		    
		   <?php 
		    echo form_input("title", $gallery->title);
		    echo form_error('title'); 
		   ?></dd>
	    
		<dt>Apraksts</dt>
		<dd>
		    <?php echo form_textarea("description", $gallery->description);
			  echo form_error('description'); 
		    ?>
		</dd>
		
		<dt>Publisks?</dt>
		<dd>
		<?php echo form_checkbox("public", $gallery->public);?>
		</dd>
	    </dl>
	    <div class="button">
	    <?php echo form_submit("save", "Saglabāt galeriju");?>
	    </div>
	</fieldset> 
	</form>
	
	
    </body>
</html>
