<?php
namespace yrn;
$defaultData = AdminHelper::defaultData();
?>
<div class="yrn-bootstrap-wrapper">
	<div class="row form-group">
		<label class="col-md-6"><?php _e('Numbers font size'); ?></label>
		<div class="col-md-6">
			<?php echo AdminHelper::selectBox($defaultData['fontSize'], esc_attr((string)$typeObj->getOptionValue('yrn-numbers-font-size')), array('name' => 'yrn-numbers-font-size', 'class' => 'js-yrn-select'))?>
		</div>
	</div>
    <div class="row form-group">
		<label class="col-md-4"><?php _e('Numbers margin'); ?></label>
		<div class="col-md-2">
            <label for="yrn-numbers-margin-top"><?php _e('Top'); ?></label>
            <input class="form-control" type="text" name="yrn-numbers-margin-top" value="<?php echo esc_attr($typeObj->getOptionValue('yrn-numbers-margin-top')); ?>">
		</div>
        <div class="col-md-2">
            <label for="yrn-numbers-margin-right"><?php _e('Right'); ?></label>
            <input class="form-control" type="text" name="yrn-numbers-margin-right" value="<?php echo esc_attr($typeObj->getOptionValue('yrn-numbers-margin-right')); ?>">
		</div>
        <div class="col-md-2">
            <label for="yrn-numbers-margin-bottom"><?php _e('Bottom'); ?></label>
            <input class="form-control" type="text" name="yrn-numbers-margin-bottom" value="<?php echo esc_attr($typeObj->getOptionValue('yrn-numbers-margin-bottom')); ?>">
		</div>
        <div class="col-md-2">
            <label for="yrn-numbers-margin-left"><?php _e('Left'); ?></label>
            <input class="form-control" type="text" name="yrn-numbers-margin-left" value="<?php echo esc_attr($typeObj->getOptionValue('yrn-numbers-margin-left')); ?>">
		</div>
	</div>
	<div class="row form-group">
		<label class="col-md-6"><?php _e('Label font size'); ?></label>
		<div class="col-md-6">
			<?php echo AdminHelper::selectBox($defaultData['fontSize'], esc_attr((string)$typeObj->getOptionValue('yrn-label-font-size')), array('name' => 'yrn-label-font-size', 'class' => 'js-yrn-select'))?>
		</div>
	</div>
    <div class="row form-group">
        <label class="col-md-4"><?php _e('Label margin'); ?></label>
        <div class="col-md-2">
            <label for="yrn-label-margin-top"><?php _e('Top'); ?></label>
            <input class="form-control" type="text" name="yrn-label-margin-top" value="<?php echo esc_attr($typeObj->getOptionValue('yrn-label-margin-top')); ?>">
        </div>
        <div class="col-md-2">
            <label for="yrn-label-margin-right"><?php _e('Right'); ?></label>
            <input class="form-control" type="text" name="yrn-label-margin-right" value="<?php echo esc_attr($typeObj->getOptionValue('yrn-label-margin-right')); ?>">
        </div>
        <div class="col-md-2">
            <label for="yrn-label-margin-bottom"><?php _e('Bottom'); ?></label>
            <input class="form-control" type="text" name="yrn-label-margin-bottom" value="<?php echo esc_attr($typeObj->getOptionValue('yrn-label-margin-bottom')); ?>">
        </div>
        <div class="col-md-2">
            <label for="yrn-label-margin-left"><?php _e('Left'); ?></label>
            <input class="form-control" type="text" name="yrn-label-margin-left" value="<?php echo esc_attr($typeObj->getOptionValue('yrn-label-margin-left')); ?>">
        </div>
    </div>
    <div class="row form-group">
        <label class="col-md-6"><?php _e('Enable animation'); ?></label>
        <div class="col-md-6">
            <label class="yrn-switch">
                <input type="checkbox" id="yrn-enable-animation" class="yrn-accordion-checkbox" name="yrn-enable-animation" <?php echo $typeObj->getOptionValue('yrn-enable-animation'); ?>>
                <span class="yrn-slider yrn-round"></span>
            </label>
        </div>
    </div>
    <div class="yrn-accordion-content yrn-hide-content">
        <div class="row form-group">
        <label class="col-md-6"><?php _e('Duration'); ?></label>
        <div class="col-md-6">
            <input class="form-control" type="text" name="yrn-animation-speed" value="<?php echo esc_attr($typeObj->getOptionValue('yrn-animation-speed')); ?>">
        </div>
    </div>
    </div>
</div>