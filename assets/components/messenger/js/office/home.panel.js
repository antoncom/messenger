messenger.panel.Home = function (config) {
	config = config || {};
	Ext.apply(config, {
		baseCls: 'modx-formpanel',
		layout: 'anchor',
		/*
		 stateful: true,
		 stateId: 'messenger-panel-home',
		 stateEvents: ['tabchange'],
		 getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
		 */
		hideMode: 'offsets',
		items: [{
			xtype: 'modx-tabs',
			defaults: {border: false, autoHeight: true},
			border: false,
			hideMode: 'offsets',
			items: [{
				title: _('messenger_items'),
				layout: 'anchor',
				items: [{
					html: _('messenger_intro_msg'),
					cls: 'panel-desc',
				}, {
					xtype: 'messenger-grid-items',
					cls: 'main-wrapper',
				}]
			}]
		}]
	});
	messenger.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(messenger.panel.Home, MODx.Panel);
Ext.reg('messenger-panel-home', messenger.panel.Home);
