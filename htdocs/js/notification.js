getInstance().ready(function() {
	if ( ! document.getElementById('sz_notification_msg') ) {
		return;
	}
	setTimeout(function() {
		DOM.id('sz_notification_msg').animate('fade', { speed : 20 });
	}, 2000);
})