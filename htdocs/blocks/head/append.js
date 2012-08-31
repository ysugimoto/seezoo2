(function() {
	// toggle page_link type
	if ( document.getElementById('content_type') ) {
		toggles = DOM.id('content_type').parent();
		toggles.event('click', function() {
			var chk = this.getOne('input').get().checked;
			
			toggles.next()[chk ? 'hide' : 'show']()
					.next()[chk ? 'show' : 'hide']();
		}, toggles);
	}
})();