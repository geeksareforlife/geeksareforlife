jQuery(document).ready(function($) {
	//set element ID/classes in vars
	var social = '.topbar-description';
	var nav = 'nav#primary-navigation';
	var headerMain = '.header-main';
	var header = '#masthead';
	var main = '#main';

	//Function to fixing margin for #main
	var marginFix = function() {
		$(main).css({
			marginTop: $(header).height() + 'px',
		});
	};
	//do marginFix and again on window resize
	marginFix();
	$( window ).resize(function() {
		marginFix();
	});
}); //end jQuery noConflict wrapper