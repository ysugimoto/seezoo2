// generate tab views
(function() {
	
	var FL = getInstance();
	
	var handleReady = function() {
		DOM('ul.sz_tab_block').foreach(function() {
			var e = DOM(this),
				lis = e.detect('li a'),
				conts = e.parent().detect('div.sz_tab_block_contents'),
				currentT = lis.get(0),
				currentC = conts.get(0),
				prevNext = DOM('div.sz_tab_block_area p.sz_tab_block_next_prev');
			
			lis.foreach(function(num) {
				DOM(this).event('click', function(ev) {
					currentT.removeClass('active');
					currentC.addClass('tab_init_hide').hide();
					currentT = DOM(this).addClass('active');
					currentC = conts.get(num).removeClass('tab_init_hide').show();
					/**
					 * tricks
					 * If changed tab has Google map Initialize Stacks,
					 * call all functions Array!
					 */
					if (FL.ut.isArray(currentC.get().__initGoogleMap)) {
						var i = -1,
							a = currentC.get().__initGoogleMap;
						
						while (a[++i]) {
							a[i]();
						}
					}
				});
			});
			if ( prevNext.length > 0 ) {
				prevNext.event('click', function(ev) {
					if ( ev.target.tagName !== 'A' || !ev.target.rel ) {
						return;
					}
					ev.preventDefault();
					var e = ev.target,
						target = parseInt(e.rel.slice(4), 10),
						c = e.className;
					
					if ( c === 'tab_block_prev' ) {
						// contents previous
						lis.get(--target).fire('click');
					} else if (c === 'tab_block_next' ) {
						// content next
						lis.get(++target).fire('click');
					}
				});
			}
		});
	};
	
	document.body ? handleReady()
			       : FL.event.set(document, 'DOMReady', handleReady);
})();