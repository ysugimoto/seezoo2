/**
 * =======================================================================================================
 * 
 * Seezoo login Controller
 * 
 * login action set up
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * 
 * =======================================================================================================
 */

(function() {
	
	var FL = getInstance(),
		DOM = FL.DOM;
	
	FL.load.library('validation');
	FL.image.preLoad(FL.config.baseUrl() + 'images/login/login_on.gif');
	
	FL.event.set(document, 'DOMReady', init);


	function init() {
		var base = FL.config.baseUrl();
		
		FL.ready('validation', function() {
			__validation();
			FL.validation.run(DOM.tag('form').get(0));
		});
		DOM.id('btn').event('mouseover', function() {
			DOM(this).attr('src', base + 'images/login/login_on.gif');
		}).event('mouseout', function() {
			DOM(this).attr('src', base + 'images/login/login_btn.gif');
		});
		
		DOM('table input').event('focus', function() {
			DOM(this).addStyle('backgroundColor', '#ffc');
		}).event('blur', function() {
			DOM(this).addStyle('backgroundColor', '#fff');
		});

		DOM.id('forgotten').event('click', function(ev) {
			ev.preventDefault();
			DOM.id('forgotten_block').toggleShow();
		});
		DOM.id('uid').method('focus');
	}
	
	/**
	 * =======================================================================================================
	 * __validation
	 * set up validation library parameter like CodeIgniter
	 * @access private
	 * @execute call
	 * =======================================================================================================
	 */
	function __validation() {
		var field = {
			'user_name' : 'ID',
			'password' : 'パスワード'
		};

		FL.validation.setFields(field);

		var rules = {
			'user_name' : 'trim|required',
			'password' : 'trim|required'
		};

		FL.validation.setRules(rules);
	};

})();
