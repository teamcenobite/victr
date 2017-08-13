	<title><?php if (!empty($title)) echo $title; ?></title>
	<?php echo chrome_frame(); ?>
	<?php echo view_port(); ?>
	<?php echo apple_mobile('black-translucent'); ?>
	<?php echo $meta; ?>

	<meta name="author" content="Scott Kovach">
	<meta property="og:site_name" content="Victr Repo Demo">
	<meta property="og:type" content="website">
	<meta property="og:title" content="<?php if(!empty($og['title'])){echo $og['title'];}else{echo "victr.webdropstudio.com";}?>">
	<meta property="og:description" content="<?php if(!empty($og['description'])){echo $og['description'];}else{echo "";}?>"> 
	<meta property="og:image" content="<?php if(!empty($og['image'])){echo $og['image'];}else{echo "http://wwww.webdropstudio.com/";}?>">
	<meta property="og:url" content="<?=$_SERVER['REQUEST_URI']?>">
       
	<link href="/assets/css/font-awesome/css/font-awesome.css" rel="stylesheet">
	<link href="/assets/js/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="/assets/css/styles.css?v=<?= time() ?>" rel="stylesheet">

	<?php echo $css; ?>

	<script
  src="https://code.jquery.com/jquery-1.12.4.min.js"
  integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
  crossorigin="anonymous"></script>
	<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
  crossorigin="anonymous"></script> 	
