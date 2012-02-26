<?php
$this->load->helper('form');
$this->load->view("shared/app_header");
?>
<article id="main">
    <h3>Attēla rediģēšana: <?php _eh($pic->title) ?></h3>

    <?php echo form_open("/photo/update/" . $pic->id); ?>

    <div id="errors">
	<?php echo validation_errors(); ?>
    </div>
    
    <dl>
	<dt>Nosaukums</dt>
	<dd><?php echo form_input("title", $pic->title) ?> <?php echo form_error('password'); ?> </dd>
	<dt>Apraksts</dt>
	<dd><?php echo form_textarea("description", $pic->description) ?></dd>
        <dt>Galerija</dt>
	<dd><?php echo form_dropdown("gallery_id", $user_galleries, $pic->gallery_id) ?></dd>
    </dl>
    <?php echo form_submit("save", "Saglabāt izmaiņas") ?>
    <?php echo form_close(); ?>	

</article>

<?php
$this->load->view("shared/app_footer");
?>