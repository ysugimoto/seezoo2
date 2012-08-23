getInstance().ready(function MemberLoginController() {

	var FL = getInstance(),
		DOM = FL.DOM,
		Animation = FL.Animation,
		fg;
	
	if ( document.getElementById('pass_forgot') ) {
		fg = DOM.id('forgotten');
		
		DOM.id('pass_forgot').click(function(ev) {
			ev.preventDefault();
			fg.toggleShow();
		})
	}
	
	if ( document.getElementById('msg_notify') ) {
		setTimeout(function() {
			DOM.id('msg_notify')
				.animate('fade', {afterHide : false});
		}, 3000);
	}
	
	DOM('a.popup_link').event('click', function(ev) {
		ev.preventDefault();
		var uri = this.href,
			features = [
			            'width=800',
			            'height=600',
			            'location=no',
			            'menubar=no',
			            'resizable=yes',
			            'scrollbars=yes',
			            'toolbar=no'
			            ].join(',');
		
		window.open(uri, 'twitterLoginWindow', features);
	});
	
	// export to global namespace
	FL.alias('handleSocialLoggedIn', function(status) {
		if ( status > 0 ) {
			location.href = FL.config.siteUrl();
		}
	});
	
});
