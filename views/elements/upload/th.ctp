<?php 
$defaults = array(
	'pathMethod'		=> 'primaryKey',
	'path'				=> 'webroot{DS}files{DS}{model}{DS}{field}{DS}',
	'fields'			=> array('dir' => 'dir', 'type' => 'type', 'size' => 'size'),
	'mimetypes'			=> array(),
	'extensions'		=> array(),
	'maxSize'			=> 2097152,
	'minSize'			=> 8,
	'maxHeight'			=> 0,
	'minHeight'			=> 0,
	'maxWidth'			=> 0,
	'minWidth'			=> 0,
	'prefixStyle'		=> true,
	'thumbnails'		=> true,
	'thumbsizes'		=> array(),
	'thumbnailQuality'	=> 75,
	'thumbnailMethod'	=> 'php',
	'exclude_fields' 	=> array(),
	'include_fields' 	=> array(),
);

$options = (!empty($options)) ? am($defaults, $options) : $defaults;
extract($options);
?>
<?php foreach ($options as $key => $option): ?>
	<?php
	$end_of_field = strpos($key, '_file');
	if ($end_of_field === false) continue;
	if(!empty($include_fields) && !in_array($key, $include_fields)) continue;
	if(!empty($exclude_fields) && in_array($key, $exclude_fields)) continue;
	$field = substr($key, 0, $end_of_field);
	
	?>
	<th class='<?php echo $key; ?>'>
		<?php
		echo Inflector::humanize($field);
		?>
	</th>
<?php
?>

<?php endforeach ?>
