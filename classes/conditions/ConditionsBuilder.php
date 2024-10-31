<?php
namespace yrn\conditions;

abstract class ConditionsBuilder {
	private $savedOptions;
	private $templateName = 'classic';
	
	public function setSavedOptions($savedOptions) {
		$this->savedOptions = $savedOptions;
	}
	
	public function getSavedOptions() {
		return $this->savedOptions;
	}
	
	private function getHeaderHtml() {
		ob_start();
		?>
		<div class="yrn-current-header yrn-current-header-[currentIndex]" data-index="[currentIndex]">
			<span class="yrn-option-label"> Option [currentIndex]</span> <span class="toggle-indicator yrn-toggle-indicator"></span>
            <span class="yrn-remove-condition" data-id="[currentIndex]">Remove</span>
		</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();
		
		return $content;
	}
	
	private function getContentHtml($opions) {
		$typeObj = $this->getTypeObj();
		$typeObj->setCurrentConditionOptions($opions);
		$templateName = $this->templateName;
		$content = '<div class="yrn-content-wrapper">';
        ob_start();
        include dirname(__FILE__).'/templates/'.$templateName.'.php';
        $content .= ob_get_contents();
        ob_end_clean();
        $content .= '</div>';
        
        return $content;
    }
    
    public function addContent($options) {
	    $content = '<div class="yrn-current-wrapper yrn-current-numbers-wrapper yrn-current-wrapper-[currentIndex] " data-index="[currentIndex]">';
	    $content .= $this->getHeaderHtml();
	    $content .= $this->getContentHtml($options);
	    $content .= '</div>';
	
	    return $content;
    }
	
	public function getContent() {
	    $savedOption = $this->getSavedOptions();
	    if (empty($savedOption)) {
	        return '';
        }
        $filteredContent = '';
        foreach ($savedOption as $index => $options) {
            if (empty($options)) {
                continue;
            }
	        $content = $this->addContent($options);
	        
	        $content = str_replace('[currentIndex]', $index, $content);
	        $filteredContent .= $content;
        }
        
        return $filteredContent;
    }
	
	public abstract function render();
}