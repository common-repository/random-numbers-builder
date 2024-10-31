<?php
namespace yrn;
global $YRN_TYPES;
$types = Numbers::getScrollTypes();
?>
<div class="yrn-bootstrap-wrapper">
	<div class="row">
		<div class="col-xs-6">
			<h2><?php _e('Choose Scroll type', SG_POPUP_TEXT_DOMAIN); ?></h2>
		</div>
	</div>
	<div class="YRN-wrapper">
		<?php foreach ($types as $typeObj): ?>
			<?php $type = $typeObj->getName(); ?>
			<?php
				$isAvaliable = $typeObj->isAvailable();
				if (!$isAvaliable) {
					continue;
				}
			?>
		<a class="create-popup-link popups-div YRN-<?php echo $type; ?>-div" href="<?php echo AdminHelper::buildCreateCountdownUrl($typeObj); ?>">
			<?php echo $YRN_TYPES['titles'][$type]; ?>
		</a>
		<?php endforeach; ?>
	</div>
</div>
