<?php echo'<?xml version="1.0" encoding="UTF-8" ?>' ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Reģistrējies, draugs - kļūs labāk!</title>
	<style type="text/css">
	    label{display:block}
	    p.error{color:red;}
	</style>
    </head>
    <body>

	<?php echo form_open('users/register'); ?>
	<fieldset>
	    <legend>Tavi dati</legend>  
	    <?php if (isset($errormsg)) { ?><p class="error"><?php echo $errormsg ?></p><?php } ?>
	    <label for="username">Lietotājs</label><input type="text" name="username" id="username" />
	    <label for="password">Vēlamā parole</label><input type="password" name="password" id="password" />
	    <label for="mail">E-pasts</label><input type="text" name="mail" id="mail" />
	    <input type="submit" value="Reģistrējiet mani, lūdzu!" />
	</fieldset>

	</form>
    </body>
</html>