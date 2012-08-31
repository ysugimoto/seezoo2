(function() {
	
	var FL = getInstance(),
		DOM = FL.DOM;
	
	function init() {
		var nav = DOM('header nav').get(0);
		
		DOM('a.toggle').get(0)
			.event('click', function(ev) {
				nav.toggleClass('anim-show', 'anim-hide');
			});
	}
	
	FL.ready(init);
	
})();