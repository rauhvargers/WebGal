<?php
    $this->load->view("shared/app_header");
?>
<!--script src="http://localhost/netbeans/WebGal/static/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
    $.get("http://localhost/netbeans/WebGal/gallery",
	    function(data){
		alert(data);
	    });
 </script-->
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