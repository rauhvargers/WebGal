<?php
    $this->load->view("shared/app_header");
?>
<article id="main">
    <h2>Galerija &laquo;<?php echo htmlspecialchars($gallery->title);?>&raquo;</h2>
    <ul>
	<?php	
	foreach ($gallery->photos as $photo) {
	    ?><li>
		<?php echo anchor("photo/view/".$photo->id, htmlspecialchars($photo->title));?>
	    </li>
	    <?php
	}
	?>
    </ul>
</article>
<?php
    $this->load->view("shared/app_footer");
?>