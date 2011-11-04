<?php
    $this->load->view("shared/app_header");
?>
<article id="main">
	<?php
		foreach ($rowset as $row) {
                    echo anchor("/photo/view/".$row->id, $row->title);
		}
	?>
</article>

<?php
    $this->load->view("shared/app_footer");
?>