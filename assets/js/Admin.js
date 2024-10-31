function YRNAdmin() {
}

YRNAdmin.prototype.init = function() {
	this.select2();
	this.addNewNumbers();
	this.removeNumbers();
	this.toggleOptions();
	this.copySortCode();
	this.accordionContent();
};

YRNAdmin.prototype.accordionContent = function() {

    var that = this;
    var accordionCheckbox = jQuery('.yrn-accordion-checkbox');

    if (!accordionCheckbox.length) {
        return false;
    }
    accordionCheckbox.each(function () {
        that.doAccordion(jQuery(this), jQuery(this).is(':checked'));
    });
    accordionCheckbox.each(function () {
        jQuery(this).bind('change', function () {
            var attrChecked = jQuery(this).is(':checked');
            var currentCheckbox = jQuery(this);
            that.doAccordion(currentCheckbox, attrChecked);
        });
    });
};

YRNAdmin.prototype.doAccordion = function(checkbox, isChecked) {
    var accordionContent = checkbox.parents('.row').nextAll('.yrn-accordion-content').first();
    jQuery(window).trigger('yrnAccordionTriggered');
    if(isChecked) {
        accordionContent.removeClass('yrn-hide-content');
    }
    else {
        accordionContent.addClass('yrn-hide-content');
    }
};

YRNAdmin.prototype.copySortCode = function() {
	jQuery('.download-shortcode').bind('click', function() {
		var currentId = jQuery(this).data('id');
		var copyText = document.getElementById('yrn-shortcode-input-'+currentId);
		copyText.select();
		document.execCommand('copy');

		var tooltip = document.getElementById('yrn-tooltip-'+currentId);
		tooltip.innerHTML = yrn_admin_localized.copied;
	});

	jQuery('.download-shortcode').mouseleave(function() {
		var currentId = jQuery(this).data('id');
		var tooltip = document.getElementById('yrn-tooltip-'+currentId);
		tooltip.innerHTML = yrn_admin_localized.copyToClipboard;
	});
};


YRNAdmin.prototype.toggleOptions = function () {
	var options = jQuery('.yrn-current-header');
	
	if (!options.length) {
		return;
	}

	options.bind('click', function () {
		var id = jQuery(this).data('index');
		var currentWrapper = jQuery('.yrn-current-wrapper-'+id);
		jQuery('.yrn-content-wrapper', currentWrapper).toggleClass('yrn-hide');
		jQuery('.yrn-toggle-indicator', currentWrapper).toggleClass('yrn-rotate-180');
	});
};

YRNAdmin.prototype.removeNumbers = function () {
	var remove = jQuery('.yrn-remove-condition');

	if (!remove.length) {
		return false;
	}

	remove.bind('click', function () {
		var id = jQuery(this).data('id');
		var currentElement = jQuery('.yrn-current-wrapper-'+id);
		if (!currentElement.length) {
			return false;
		}
		currentElement.remove();
	});
};

YRNAdmin.prototype.addNewNumbers = function () {
	var addNewButton = jQuery('.yrn-add-new-btn');

	if (!addNewButton.length) {
		return false;
	}
	var that = this;
	addNewButton.bind('click', function (e) {
		e.preventDefault();
		that.addNewRole();
	});
};

YRNAdmin.prototype.getMaxIndex = function () {
	var maxIndex = 0;

	jQuery('.yrn-current-numbers-wrapper').each(function () {
		var index = jQuery(this).data('index');
		if (index > maxIndex) {
			maxIndex = index;
		}
	});
	return maxIndex;
};

YRNAdmin.prototype.addNewRole = function () {
	var getMaxIndex = this.getMaxIndex();
	getMaxIndex += 1;
	var currentOptions = jQuery('.yrn-hidden-options');
	var html = currentOptions.html();
	html = html.replace(/yrn-conditions-hidden/g, 'yrn-conditions');
	html = html.replace(/\[currentIndex\]/g, getMaxIndex);

	jQuery('.yrn-all-options-wrapper').append(html);
};

YRNAdmin.prototype.select2 = function() {
	var select2 = jQuery('.js-yrn-select');

	if(!select2.length) {
		return false;
	}

	select2.select2();
};

jQuery(document).ready(function() {
	var obj = new YRNAdmin();
	obj.init();
});