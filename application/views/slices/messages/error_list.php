<div class='alert alert-danger'>
<?php
if( !empty($messages['error_title']) ){
?>
	<div><strong><?php echo $messages['error_title']; ?></strong></div>
<?php
}
?>
	
	<ul>
<?php
	foreach( $messages['errors'] as $err ){
		echo "<li>{$err}</li>\n";
	}
?>
	</ul>
</div>