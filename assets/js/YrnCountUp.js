function YrnCountUp() {
    this.options = [];
}

YrnCountUp.prototype.init = function () {
    var numbers = jQuery('.yrn-options-wrapper');

    if (!numbers.length) {
        return false;
    }
    var that = this;
    numbers.each(function () {
        var currentNumbers = jQuery(this);
        var options = currentNumbers.data('options');
        that.options = options;
        if (options['yrn-enable-animation']) {
            that.run(currentNumbers);
        }
    });
};

YrnCountUp.prototype.run = function (currentNumbers) {
    var options = this.options;
    var savedConditions = options['yrn-conditions'];
    var currentIndex = 1;
    jQuery('.yrn-number-wrapper', currentNumbers).each(function () {
        var endNumber = savedConditions[currentIndex]['yrn-numbers'];
        var currentId = jQuery(this).data('id');
        var element = document.getElementById(currentId);
        var animationSpeed = parseInt(options['yrn-animation-speed']);

        var demo = new countUp.CountUp(element, parseInt(endNumber),
            {

                // start value
                startVal: 0,

                // number of decimal places
                decimalPlaces: 0,

                // duration in seconds
                duration: animationSpeed,

                // smooth easing for large numbers above this if useEasing
                smartEasingThreshold: 999,

                // amount to be eased for numbers above threshold
                smartEasingAmount: 333,

                // toggle easing
                useEasing : true,

                // 1,000,000 vs 1000000
                useGrouping : true,

                // character to use as a separator
                separator : ',',

                // character to use as a decimal
                decimal : '.',

                // optional custom easing closure function, default is Robert Penner's easeOutExpo
                easingFn: null,

                // optional custom formatting function, default is self.formatNumber below
                formattingFn: null,

                // optional text before the result
                prefix: '',

                // optional text after the result
                suffix: '',

                // optionally pass an array of custom numerals for 0-9
                numerals: []

            });

        demo.start()
        currentIndex += 1;
    });
};

jQuery(document).ready(function () {
    var obj = new YrnCountUp();
    obj.init();
});