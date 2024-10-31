<div class="yrn-bootstrap-wrapper">
	<div class="row form-group">
        <div class="col-md-6">
            <label for="YRN-button-text" class="ycd-label-of-input"><?php _e('Text', YRN_TEXT_DOMAIN); ?></label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" id="YRN-button-text" name="YRN-button-text[]" value="<?php echo esc_attr($this->getOptionValue('YRN-button-text')); ?>">
        </div>
    </div>
</div>