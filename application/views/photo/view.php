<?php
    $this->load->view("shared/app_header");
?>
<article id="main">
    
    
    <details>
	<h2>Attēls &laquo;<?php echo htmlspecialchars($pic->title)?>&raquo;</h2>	
	<summary>
	    <p><?php echo htmlspecialchars($pic->description);?></p>
	</summary>
        <img src="<?php echo site_url("image/view/".$pic->id) ?>" title="<?php echo htmlspecialchars($pic->title)?>" />
    </details>
    
	
</article>

<?php
    $this->load->view("shared/app_footer");
?>