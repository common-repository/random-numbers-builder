'use strict';

function WpYrnBlockMin() {}

WpYrnBlockMin.prototype.init = function () {
	if (typeof wp == 'undefined' || typeof wp.element == 'undefined' || typeof wp.blocks == 'undefined' || typeof wp.editor == 'undefined' || typeof wp.components == 'undefined') {
		return false;
	}
	var localizedParams = YRN_GUTENBERG_PARAMS;

	var __ = wp.i18n;
	var createElement = wp.element.createElement;
	var registerBlockType = wp.blocks.registerBlockType;
	var InspectorControls = wp.editor.InspectorControls;
	var _wp$components = wp.components,
		SelectControl = _wp$components.SelectControl,
		TextareaControl = _wp$components.TextareaControl,
		ToggleControl = _wp$components.ToggleControl,
		PanelBody = _wp$components.PanelBody,
		ServerSideRender = _wp$components.ServerSideRender,
		Placeholder = _wp$components.Placeholder;

	registerBlockType('randomnumbers/numbers', {
		title: localizedParams.title,
		description: localizedParams.description,
		keywords: ['random numbers', 'numbers', 'Random', 'Numbers'],
		category: 'widgets',
		icon: 'slides',
		attributes: {
			countdownId: {
				type: 'number'
			}
		},
		edit: function edit(props) {
			var _props$attributes = props.attributes,
				_props$attributes$cou = _props$attributes.countdownId,
				countdownId = _props$attributes$cou === undefined ? '' : _props$attributes$cou,
				_props$attributes$dis = _props$attributes.displayTitle,
				displayTitle = _props$attributes$dis === undefined ? false : _props$attributes$dis,
				_props$attributes$dis2 = _props$attributes.displayDesc,
				displayDesc = _props$attributes$dis2 === undefined ? false : _props$attributes$dis2,
				setAttributes = props.setAttributes;

			const countdownOptions = [];
			let allNumbers = YRN_GUTENBERG_PARAMS.allCountdowns;
			for(var id in allNumbers) {
				var currentdownObj = {
					value: id,
					label: allNumbers[id]
				}
				countdownOptions.push(currentdownObj);
			}
			countdownOptions.unshift({
				value: '',
				label: YRN_GUTENBERG_PARAMS.numbers_select
			})
			var jsx = void 0;

			function selectCountdown(value) {
				setAttributes({
					countdownId: value
				});
			}

			function setContent(value) {
				setAttributes({
					content: value
				});
			}

			function toggleDisplayTitle(value) {
				setAttributes({
					displayTitle: value
				});
			}

			function toggleDisplayDesc(value) {
				setAttributes({
					displayDesc: value
				});
			}

			jsx = [React.createElement(
				InspectorControls,
				{ key: 'pollbuilder-gutenberg-form-selector-inspector-controls' },
				React.createElement(
					PanelBody,
					{ title: 'countdown builder title' },
					React.createElement(SelectControl, {
						label: 'Select random number',
						value: countdownId,
						options: countdownOptions,
						onChange: selectCountdown
					}),
					React.createElement(ToggleControl, {
						label: 'Select poll',
						checked: displayTitle,
						onChange: toggleDisplayTitle
					}),
					React.createElement(ToggleControl, {
						label: '',
						checked: displayDesc,
						onChange: toggleDisplayDesc
					})
				)
			)];

			if (countdownId) {
				return '[yrn_numbers id="' + countdownId + '"]';
			} else {
				jsx.push(React.createElement(
					Placeholder,
					{
						key: 'yrn-gutenberg-form-selector-wrap',
						className: 'yrn-gutenberg-form-selector-wrapper' },
					React.createElement(SelectControl, {
						key: 'yrn-gutenberg-form-selector-select-control',
						value: countdownId,
						options: countdownOptions,
						onChange: selectCountdown
					}),
					React.createElement(SelectControl, {
						key: 'ypl-gutenberg-form-selector-select-control',
						onChange: selectCountdown
					})
				));
			}

			return jsx;
		},
		save: function save(props) {

			return '[yrn_numbers id="' + props.attributes.countdownId + '"]';
		}
	});
};

jQuery(document).ready(function () {
	var block = new WpYrnBlockMin();
	block.init();
});