<?php
    $this->load->view("shared/app_header");
?>
<article id="main">
    <ul>
    <?php
		foreach ($rowset as $row) {
                    echo "<li>".anchor("/photo/view/".$row->id, $row->title)."</li>";
		}
	?>
	</ul>
    
</article>

<?php
    $this->load->view("shared/app_footer");
?>