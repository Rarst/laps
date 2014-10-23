
jQuery.fn.lapstooltip = jQuery.fn.tooltip.noConflict();

jQuery(document).ready(function () {
	jQuery(".laps-timeline .event").lapstooltip({
		container: '#wpadminbar',
		placement: 'bottom',
		html     : true,
		animation: false
	});
});
