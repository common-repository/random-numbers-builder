<?php
$type = $typeObj->getOptionValue('yrn-type');
if(empty($type)) {
	$type = @$_GET['yrn-type'];
}

if(empty($type)) {
    $type = YRN_DEFAULT_TYPE;
}
?>
<input type="hidden" name="yrn-type" value="<?php echo esc_attr($type); ?>">