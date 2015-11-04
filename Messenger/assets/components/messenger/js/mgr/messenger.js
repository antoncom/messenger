var messenger = function (config) {
	config = config || {};
	messenger.superclass.constructor.call(this, config);
};
Ext.extend(messenger, Ext.Component, {
	page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('messenger', messenger);

messenger = new messenger();