(function() {
	var FL = getInstance(),
		sc = document.getElementsByTagName('script'),
		len = sc.length, i, thisScript, srcQuery, param = {}, j = 0, spt, target, map, point;
	
	for (i = len - 1; i >= 0; i--) {
		if (sc[i].src.indexOf('googlemap_init') > 0) {
			thisScript = sc[i];
			break;
		}
	}

	if (!thisScript) { return;}
	// generate_param
	srcQuery = thisScript.src.slice(thisScript.src.indexOf('?') + 1).split('&');
	for (; j < srcQuery.length; j++) {
		spt = srcQuery[j].split('=');
		param[spt[0]] = spt[1];
	}

	var setUp = function() {
		var w,
			isH, 
			target = document.getElementById('sz_googlemap_area' +param.id),
			p = DOM(target).parent();
		
		/**
		 * tricks
		 * If this block in tab contents and that is hidden,
		 * add callStack to function Array and calls tab change
		 */
		if (p.isHidden() && p.hasClass('sz_tab_block_contents')) {
			p.show();
			w = DOM(target).prop('offsetWidth');
			p.hide();
			target.style.height = Math.round(w * 2 / 3) + 'px';
			target.style.width = w + 'px';
			target.__created = false;
			addCallStack(target, p);
		} else if ( p.parent().hasClass('sz_accordion_wrapper') ) {
			p.parent().show();
			w = DOM(target).prop('offsetWidth');
			p.parent().hide();
			target.style.height = Math.round(w * 2 / 3) + 'px';
			target.style.width = w + 'px';
			target.__created = false;
			addCallStack(target, p.parent());
		} else {
			w = target.offsetWidth;
			target.style.height = Math.round(w * 2 / 3) + 'px';
			target.style.width = w + 'px';
			generate(target);
		}
		
		// orientationchange for smartphone
		if ( "orientationchange" in window ) {
			FL.event.set(window, 'orientationchange', function() {
				var w = target.panretNode.offsetWidth;
				
				target.style.width = w + 'px';
				target.style.height = Math.round(w * 2 / 3) + 'px';
			})
		}

	};
	
	function addCallStack(target, p) {
		if (FL.ut.isArray(target.parentNode.__initGoogleMap)) {
			target.parentNode.__initGoogleMap.push(function() {
				p.show();
				if (target.__created === false) {
					generate(target);
					target.__created = true;
				}
			});
		} else {
			target.parentNode.__initGoogleMap = [function() {
				p.show();
				if (target.__created === false) {
					generate(target);
					target.__created = true;
				}
			}];
		}
	}
	
	function generate(target) {
		try {
			if (param.v == 2) {
				generateMap2(target);
			} else if (param.v == 3) {
				generateMap3(target);
			} else {
				throw Error('invalid maps API version!');
			}
		} catch(e) { alert(e.message);}
	}
	
	// generate map for version 2
	function generateMap2(e) {
		map = new google.maps.Map2(e);
		point = new google.maps.LatLng(parseFloat(param.lat),parseFloat(param.lng));
		map.setCenter(point, parseInt(param.z, 10));
		map.addOverlay(new google.maps.Marker(point));
		map.addControl(new google.maps.SmallMapControl());
	}
	
	// generate map for version 3
	function generateMap3(e) {
		point = new google.maps.LatLng(parseFloat(param.lat),parseFloat(param.lng));
		map = new google.maps.Map(e, {
			zoom : parseInt(param.z, 10),
			center : point,
			mapTypeId : google.maps.MapTypeId.ROADMAP
		});
		new google.maps.Marker({
			position : point,
			map : map
		});
	}

	(!document.body) ? FL.event.set(document, 'DOMReady', setUp) : setUp();
})();