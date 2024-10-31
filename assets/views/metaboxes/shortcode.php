<?php
$postId = @$_GET['post'];
?>
<div class="yrn-bootstrap-wrapper">
<label><?php _e('shortcode'); ?></label>
<?php
if (empty($postId)) : ?>
	<p><?php _e('Please do save the Random numbers, to getting the shortcode', YRN_TEXT_DOMAIN); ?>.</p>
<?php else: ?>
    <p>
    <div class="yrn-tooltip"><span class="yrn-tooltiptext" id="yrn-tooltip-<?php echo $postId; ?>"> <?php echo __('Copy to clipboard', YRN_TEXT_DOMAIN); ?></span><input type="text" class="download-shortcode" id="yrn-shortcode-input-<?php echo $postId; ?>"" data-id="<?php echo $postId; ?>" value="[yrn_numbers id=<?php echo $postId; ?>]" readonly="" onfocus="this.select()"></div>
    </p>
<?php endif; ?>
<label>
	<?php _e('Current version'); ?>
</label>
<p class="current-version-text" style="color: #3474ff;"><?php echo YRN_VERSION_TEXT; ?></p>
<label>
	<?php _e('Last update date'); ?>
</label>
<p style="color: #11ca79;"><?php echo YRN_LAST_UPDATE_DATE; ?></p>
<label>
	<?php _e('Next update date'); ?>
</label>
<p style="color: #efc150;"><?php echo YRN_NEXT_UPDATE_DATE; ?></p>
</div>