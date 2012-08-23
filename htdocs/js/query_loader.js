/**
 * Flint query loader script
 * load library and execute method from src query parameters
 * 
 * @package Flint
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 */
(function() {
	var FL = getInstance(),sc = document.getElementsByTagName('script'),
		len = sc.length, i, thisScript, srcQuery, param = {}, spt, j = 0;

	for (i = len - 1; i >= 0; i--) {
		if (sc[i].src.indexOf('query_loader.js') > 0) {
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

	FL.load.library(param.lib);
	FL.ready(param.lib, function() {
		FL[param.lib][param.method]();
	});
})();