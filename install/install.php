<?php
	error_reporting ( E_WARNING );

	define ( "MODULES_DIR", '../' . realpath ( dirname ( __FILE__ ) ) . '/system/application/modules/' );
	define( "ADMIN_EMAIL", "" );
	include ( "form_validation.php" );
	include ( "../system/application/libraries/ws_settings_writer.php" );
	include "install.functions.php";
	$form_validation 	= new Form_validation ();

	$error = FALSE;

	if ( Verify_files_before_install () !== TRUE ) {
		$error = Verify_files_before_install ();
	}
	else {
		$error = create_config_file ();
	}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>W-script Installation</title>
	<link href="../templates/default/css/reset.css" rel="stylesheet" type="text/css" />
	<link href="../templates/default/css/default.css" rel="stylesheet" type="text/css" />
	<link href="../templates/default/css/forms.css" rel="stylesheet" type="text/css" />
	<link href="css/buttons.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../templates/default/js/jquery132.js"></script>
	<script type="text/javascript" src="../templates/default/js/jquery-ui-171.js"></script>
	<script type="text/javascript">
		jQuery(document).ready ( function () {
			setTimeout
			(
				load_installer,
				1000
			);
		});

		function load_installer () {
			$( '#installer_main' ).show ( 'slow' );
		}
	</script>
	<style type="text/css">
		form.appnitro li {width:99%!important;padding-top:8px}
		form.appnitro li h3 {padding:0!important;text-align:center;}
		form.appnitro li h3, h1 {border:none!important}
	</style>
</head>

<body style="background-image:none!important;background-color:#383939!important;">
<!-- MENU -->
<center style="margin:60px auto;width:800px;background:transparent url('../templates/default/images/ajax-loader.gif') no-repeat 50% 50%;min-height:500px">
	<div id="installer_main" class="content" style="display:none;text-align:left">
	<h1 style="text-align:center;font-family:verdana,arial;color:#ccc;font-size:14px"><img src="img/ws.png" /></h1>
	<form method="post" class="appnitro">
	<fieldset style="background: #555;padding:15px 35px 10px 15px;border:4px solid #000!important;-moz-border-radius: 10px;-webkit-border-radius: 10px;border-radius: 10px;">
<?php
		if ( $error != FALSE ) {
			echo '<p class="error">' . $error . '</p>';
		}
?>
		<input name="USE_SMTP" type="hidden" id="USE_SMTP" value="0" />
		<input name="MAIL_IS_HTML" type="hidden" id="MAIL_IS_HTML" value="1" />
		<ul style="width:49%;float:left">
			<!-- ==================================	DATABASE ============================= -->
			<li>
				<h3><img src="img/ds.png" /></h3>
			</li>
			<li>
				
				<label class="description" for="HOSTNAME">Hostname:</label>
				<div align="left">
					<input name="HOSTNAME" type="text" class="element text large" id="HOSTNAME" value="<?= $form_validation->getField_value ( 'HOSTNAME', 'localhost' ) ?>" />
					<?=$form_validation->printField_error ( 'HOSTNAME' )?>
				</div>
			</li>
			
			<li>
				<label class="description" for="DATABASE">Database Name:</label>
				<div align="left">
					<input name="DATABASE" type="text" class="element text large" id="DATABASE" value="<?= $form_validation->getField_value ( 'DATABASE' ) ?>" />
					<?=$form_validation->printField_error ( 'DATABASE' )?>
				</div>
			</li>
			
			<li>
				<label class="description" for="DBUSER">Database User:</label>
				<div align="left">
					<input name="DBUSER" type="text" class="element text large" id="DBUSER" value="<?= $form_validation->getField_value ( 'DBUSER' ) ?>" />
					<?=$form_validation->printField_error ( 'DBUSER' )?>
				</div>
			</li>
			
			<li>
				<label class="description" for="DBPASS">Database Password:</label>
				<div align="left">
					<input name="DBPASS" type="password" class="element text large" id="DBPASS" value="<?= $form_validation->getField_value ( 'DBPASS' ) ?>" />
					<?=$form_validation->printField_error ( 'DBPASS' )?>
				</div>
			</li>
			
			<li>
				<label class="description" for="DBPREFIX">Database Prefix:</label>
				<div align="left">
					<input name="DBPREFIX" type="text" class="element text large" id="DBPREFIX" value="<?= $form_validation->getField_value ( 'DBPREFIX', 'wscript_' ) ?>" />
					<?=$form_validation->printField_error ( 'DBPREFIX' )?>
				</div>
			</li>
		</ul>

		<!-- ==================================	SITE SETTINGS ============================= -->
		<ul style="width:49%;float:right">
			<li>
				<h3><img src="img/gs.png" /></h3>
			</li>
		
			<li>
				
				<label class="description" for="DEFAULT_USERNAME">Admin Username:</label>
				<div align="left">
					<input name="DEFAULT_USERNAME" type="text" class="element text large" id="DEFAULT_USERNAME" value="<?= $form_validation->getField_value ( 'DEFAULT_USERNAME' ) ?>" />
					<?=$form_validation->printField_error ( 'DEFAULT_USERNAME' )?>
				</div>
			</li>
			<li>
				
				<label class="description" for="DEFAULT_PASSWORD">Admin Password:</label>
				<div align="left">
					<input name="DEFAULT_PASSWORD" type="password" class="element text large" id="DEFAULT_PASSWORD" value="<?= $form_validation->getField_value ( 'DEFAULT_PASSWORD' ) ?>" />
					<?=$form_validation->printField_error ( 'DEFAULT_PASSWORD' )?>
				</div>
			</li>
	
			<li>
				<label class="description" for="ADMIN_EMAIL">Admin Email:</label>
				<div align="left">
				<input name="ADMIN_EMAIL" type="text" class="element text large" id="ADMIN_EMAIL" value="<?= $form_validation->getField_value ( 'ADMIN_EMAIL' ) ?>" />
				<?=$form_validation->printField_error ( 'ADMIN_EMAIL' )?>
				</div>
			</li>
			
			<li>
				<label class="description" for="SITE_NAME">Site Name:</label>
				<div align="left">
				<input name="SITE_NAME" type="text" class="element text large" id="SITE_NAME" value="<?= $form_validation->getField_value ( 'SITE_NAME' ) ?>" />
				<?=$form_validation->printField_error ( 'SITE_NAME' )?>
				</div>
			</li>
			
			<li>
				<label class="description" for="SITE_SLOGAN">Site Slogan:</label>
				<div align="left">
				<input name="SITE_SLOGAN" type="text" class="element text large" id="SITE_SLOGAN" value="<?= $form_validation->getField_value ( 'SITE_SLOGAN' ) ?>" />
				<?=$form_validation->printField_error ( 'SITE_SLOGAN' )?>
				</div>
			</li>
		</ul>
<div class="clear"></div>
		<ul>
			<li style="padding-top:20px">
				<?= __button ( 'Perform Installation' ) ?>
			</li>
		</ul>
	</fieldset>
	</form>

	<div class="clear"></div>
	</div>
	<!-- FOOTER -->
</center>
</body>

</html>