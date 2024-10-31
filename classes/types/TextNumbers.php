<?php
namespace yrn;

class TextNumbers extends Numbers {
	public function __construct() {
		add_filter('yrnGeneralMetaboxes', array($this, 'addMetabox'), 10, 1);
	}

	public function addMetabox($metaboxes) {
		$metaboxes['mainOptions'] = array('title' => 'Main options', 'position' => 'normal', 'prioritet' => 'high', 'currentObj' => $this);
		
		return $metaboxes;
	}
	
	public function options() {
		require_once YSTP_TYPES_VIEWS_PATH.'options.php';
	}

	private function includeMedia() {
		ScriptsIncluder::loadScript('countUp.js');
		ScriptsIncluder::loadScript('YrnCountUp.js');
    }
	
	public function getViewContent() {
	    $this->includeMedia();
	    $id = $this->getId();
		$options = $this->getOptionValue('yrn-conditions');
		$savedOptions = $this->getSavedData();
		$savedOptions = json_encode($savedOptions, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
		$counts = count($options);
		$rowCount = 12/floor($counts);
		$dataId = time();
		ob_start();
		?>
		<div class="yrn-options-wrapper yrn-options-wrapper-<?php echo $id; ?> row" data-options='<?php echo $savedOptions; ?>'>
			<?php foreach ($options as $key => $option): ?>
				<div class="col-md-<?php echo $rowCount; ?>">
					<div class="yrn-number-wrapper" id="<?php echo $dataId.$key; ?>" data-id="<?php echo $dataId.$key; ?>">
						<?php //echo $option['yrn-numbers']; ?>
					</div>
					<div class="yrn-label-wrapper">
						<?php echo $option['yrn-label']; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();
		
		$viewContent = '<div class="yrn-bootstrap-wrapper">'.$content.'</div>';
		
		return $viewContent;
	}
}