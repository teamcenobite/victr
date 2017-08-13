<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
  <meta charset="utf-8">  
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<?php echo $head_scripts; ?>
</head>
<body>
    
<?php echo $header; ?>

<div class="container">
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<?php if(ENVIRONMENT != 'production'){ ?>
				<div class='alert alert-warning margin-bottom-10 text-center hidden'>You are currently in the dev environment</div>
			<?php } ?>		
			<?php echo $content; ?>
		</div>
	</div>
</div>

<?php echo $footer; ?>
<?php echo $foot_scripts; ?>
</body>
</html>