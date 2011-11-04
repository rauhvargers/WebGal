<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title><?php echo htmlspecialchars($pagetitle); ?></title>
		<meta name="viewport" content="width=device-width; initial-scale=1.0" />
		<link rel="shortcut icon" href="/favicon.ico" /><br/>
		<?php
			echo link_tag('static/site.css?version=1.2', 'stylesheet', 'text/css'); 
		?>
		
	</head>
	<body>
		<div>
			<header>
				<h1>FotoPortāls</h1>
			</header>
			<nav>
				<ul>
					<li><?php echo anchor('/', 'FotoPortāls', 'title="Sākumlapa"'); ?></li>
					<li><?php echo anchor('/gallery', 'Galerijas', 'title="Skatīt visas galerijas"'); ?></li>
					<li><?php echo anchor('/user/index', 'Lietotāji', 'title="Skatīt lietotāju sarakstu"'); ?></li>
					<?php if ($show_loginform == TRUE ){ ?>
						<li><?php echo anchor('/user/auth/', 'Pierakstīties', 'title="Autentificēties sistēmā"'); ?></li>
					<?php } elseif ($show_logoutform == TRUE) { ?>
						<li><?php echo anchor('/user/auth/', 'Atteikties', 'title="Autentificēties sistēmā"'); ?></li>
					<?php } ?>
					
				</ul>				
			</nav>