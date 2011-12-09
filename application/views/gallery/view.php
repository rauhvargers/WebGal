<?php
    $viewdata["scripts"] =array("/static/jquery.js", "/static/gallery_ajax.js");
    $this->load->view("shared/app_header", $viewdata);
?>
<article id="main">
    <h2>Galerija &laquo;<?php echo htmlspecialchars($gallery->title);?>&raquo;</h2>
    <ul id="galleryItems">
	<?php	
	foreach ($gallery->photos as $photo) {
	    ?><li>
		<?php echo anchor("photo/view/".$photo->id, htmlspecialchars($photo->title));?>
	    </li>
	    <?php
	}
	?>
    </ul>
    <?php echo anchor("photo/addnew/".$gallery->id, "Pievienot fotogrāfiju"); ?>
</article>
<?php
    $this->load->view("shared/app_footer");
?>