(function() {
	
	var FL = getInstance(),
		DOM = FL.DOM;
	
	function init() {
		DOM('div.sz_accordion_wrapper').foreach(function() {
			var e = DOM(this);
			
			if ( e.hasClass('edit') ) {
				return false;
			}
			
			e.prev().event('click', function(ev) {
				var initG = e.first().get().__initGoogleMap;
				
				if ( e.prop('__isOpen') > 1 ) {
					if ( e.hasClass('open_animate') ) {
						e.animate('blindUp',
									{
										speed : 10,
										callback : function() {
											showMap(initG);
										}
									}
								);
					} else {
						e.hide();
						showMap(initG);
					}
					e.prop('__isOpen', 1);
				} else {
					if ( e.hasClass('open_animate') ) {
						e.animate('blindDown',
									{
										speed : 10,
										callback : function() {
											e.addStyle({
												overflow : '',
												height : 'auto'
											});
											showMap(initG);
										}
									}
								);
					} else {
						e.show();
						showMap(initG);
					}
					e.prop('__isOpen', 2);
				}
			});
			
			// initial body hide
			e.hide().prop('__isOpen', 1);
		});
	}
	
	function showMap(initG) {
		if ( ! initG ) {
			return;
		}
		var i = 0,
			len = initG.length;
		
		for ( ; i < len; ++i ) {
			initG[i]();
		}
		initG = null;
	}
	
	document.body ? init()
					: FL.event.set(document, 'DOMReady', init);
})();