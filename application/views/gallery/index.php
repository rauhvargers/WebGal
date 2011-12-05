<?php
    $this->load->view("shared/app_header");
?>

<article id="main">
    <h2>Galeriju saraksts</h2>
    
    <ul>
    <?php foreach ($galleries as $key => $value) { ?>
    	<li><?php echo anchor("gallery/view/".$key, htmlspecialchars($value));?></li>
    <?php } ?>	
    </ul>
 </article>

<?php
    $this->load->view("shared/app_footer");
?>