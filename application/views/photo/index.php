<?php
    $this->load->view("shared/app_header");
?>
<article id="main">
        Attēla apskate
        <pre>
	<?php
                print_r($pic);
	
                echo $this->config->item('webgal_file_base');
                
	?>
        </pre>
        
        
</article>

<?php
    $this->load->view("shared/app_footer");
?>