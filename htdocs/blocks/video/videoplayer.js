(function() {
	var FL   = getInstance(),
		sc    = document.getElementsByTagName('script'),
		delay = window.setTimeout,
		len   = sc.length,
		param = {},
		i,
		j,
		v,
		thisScript,
		srcQuery,
		qlen,
		param,
		target,
		spt,
		video,
		obj;

	for ( i = len - 1; i >= 0; i-- ) {
		if (sc[i].src.indexOf('blocks/video/videoplayer.js') > 0) {
			thisScript = sc[i];
			break;
		}
	}

	if ( ! thisScript ) {
		return;
	}
	
	// generate_param
	srcQuery = thisScript.src.slice(thisScript.src.indexOf('?') + 1).split('&');
	qlen     = srcQuery.length;
	
	for ( j = 0; j < qlen; j++) {
		spt = srcQuery[j].split('=');
		param[spt[0]] = spt[1];
	}
	target = DOM.id('block_id_' + param.bid);
	video  = param.v;
	obj    = FL.load.swf(FL.config.baseUrl() + 'blocks/video/videoplayer.swf', 'sz_video', {
				width   : param.w,
				height  : param.h,
				scale   : 'exacfit',
				quality : 'high',
				menu    : 'true'
			}, true);
	
	if ( target.first().hasClass('invisible') ) {
		return;
	}
	target.html(obj);
	v = document.getElementById('sz_video');

	// !! wait a few miutes untill finish export External Interface
	delay(function() {
		if ( ! v.playVideo ) {
			delay(arguments.callee, 500);
		} else {
			v.playVideo(video);
		}
	}, 500);
})();