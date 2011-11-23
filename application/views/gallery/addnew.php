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
	
	<fieldset>
	
	    <dl>
		<dt>Nosaukums</dt>
		<dd>
		    
		   <?php 
		    echo form_input("title", $defaulttitle);
		   ?></dd>
	    
		<dt>Apraksts</dt>
		<dd>
		    <?php echo form_textarea("description");?>
		</dd>
		
		<dt>Publisks?</dt>
		<dd>
		    <?php echo form_checkbox("public", "on");?>
		</dd>
	    </dl>
	    <div class="button">
	    <?php echo form_submit("save", "Saglabāt galeriju");?>
	    </div>
	</fieldset> 
	</form>
	
	
    </body>
</html>
