<?php
use YRN\AdminHelper;
use yrn\conditions\ClassicConditionsBuilder;
$defaultData = AdminHelper::defaultData();
?>
<div class="yrn-bootstrap-wrapper">
    <div class="yrn-all-options-wrapper">
	    <?php
            $classicBuilder = new ClassicConditionsBuilder();
            $classicBuilder->setTypeObj($typeObj);
            echo $classicBuilder->render();
	    ?>
    </div>
	
    <div class="yrn-hidden-options yrn-hide">
	    <?php
            $defaultData = $typeObj->getOptionValue('yrn-classic-default');
            $hiddenContent = $classicBuilder->addContent($defaultData);
            $hiddenContent = str_replace('yrn-conditions[[currentIndex]]', 'yrn-conditions-hidden[[currentIndex]]', $hiddenContent);
            echo $hiddenContent;
	    ?>
    </div>
    <div class="yrn-add-new">
        <button class="yrn-add-new-btn btn btn-primary"><?php _e('Add new', YRN_TEXT_DOMAIN); ?></button>
    </div>
</div>