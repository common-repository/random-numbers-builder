function YRNScroll() {
	this.data = {};
	this.status = false;
}

YRNScroll.init = function() {
	var scrolls = jQuery('.YRN-content');

	if(!scrolls.length) {
		return false;
	}

	for(var i in scrolls) {
		var currentIndex = parseInt(i);
		if(scrolls.hasOwnProperty(currentIndex)) {
			var scroll = scrolls[currentIndex];
			var data = jQuery(scroll).data('options');
			var obj = new YRNScroll();
			obj.data = data;
			obj.currentElement = scroll;
			obj.id = jQuery(scroll).data('id');
			obj.start();
		}
	}
};

YRNScroll.prototype.ScrollCurrentPosition = function() {
	var scrollTop = jQuery(window).scrollTop();
	var docHeight = jQuery(document).height();
	var winHeight = jQuery(window).height();

	var scrollPercent = (scrollTop) / (docHeight - winHeight);
	var scrollPercentRounded = Math.round(scrollPercent*100);

	return scrollPercentRounded
};

YRNScroll.prototype.start = function() {
	var that = this;
	var allowToShow = this.allowToShow();

	if(allowToShow) {
		that.show();
	}

	jQuery(window).on('scroll', function() {

		var allowToShow = that.allowToShow();

		if(allowToShow && !that.status) {
			that.show();
		}
	});
};

YRNScroll.prototype.allowToShow = function() {
	var data = this.data;
	var measure = data['YRN-show-after-measure'];
	var showAfter = data['YRN-show-after'];

	if(measure == '%') {
		var conditionValue = this.ScrollCurrentPosition();
	}
	else {
		var conditionValue = jQuery(window).scrollTop();
	}

	if(conditionValue < showAfter) {
		this.status = false;
		this.hide();
		return false;
	}

	return true;
};

YRNScroll.prototype.hide = function() {
	var current = this.currentElement;
	jQuery(current).find('.YRN-content-html').first().removeAttr('style')
};

YRNScroll.prototype.action = function() {
	var currentButton = jQuery('.YRN-content-'+this.id);
	currentButton.bind('click', function() {
		jQuery('html, body').animate({
			scrollTop: 0
		}, 1000);
	});
};

YRNScroll.prototype.show = function() {
	this.status = true;
	this.action();
	var current = this.currentElement;
	var inlineStyles = jQuery(current).data('styles');
	jQuery(current).find('.YRN-content-html').first().attr('style', inlineStyles)
};

jQuery(document).ready(function() {
	YRNScroll.init();
});