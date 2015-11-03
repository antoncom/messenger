Ext.onReady(function() {
	messenger.config.connector_url = OfficeConfig.actionUrl;

	var grid = new messenger.panel.Home();
	grid.render('office-messenger-wrapper');

	var preloader = document.getElementById('office-preloader');
	if (preloader) {
		preloader.parentNode.removeChild(preloader);
	}
});