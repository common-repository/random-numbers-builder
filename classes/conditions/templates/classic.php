<div class="row form-group">
	<div class="col-md-6"><?php _e('Number', YRN_TEXT_DOMAIN); ?></div>
	<div class="col-md-6"><input type="text" name="yrn-conditions[[currentIndex]][yrn-numbers]" value="<?php echo esc_attr($typeObj->getConditionsOptionValue('yrn-numbers')); ?>"></div>
</div>
<div class="row form-group">
	<div class="col-md-6"><?php _e('Label', YRN_TEXT_DOMAIN); ?></div>
	<div class="col-md-6"><input type="text" name="yrn-conditions[[currentIndex]][yrn-label]" value="<?php echo esc_attr($typeObj->getConditionsOptionValue('yrn-label')); ?>"></div>
</div>