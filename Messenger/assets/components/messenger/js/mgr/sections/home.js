messenger.page.Home = function (config) {
	config = config || {};
	Ext.applyIf(config, {
		components: [{
			xtype: 'messenger-panel-home', renderTo: 'messenger-panel-home-div'
		}]
	});
	messenger.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(messenger.page.Home, MODx.Component);
Ext.reg('messenger-page-home', messenger.page.Home);