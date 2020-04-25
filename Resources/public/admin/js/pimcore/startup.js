pimcore.registerNS("pimcore.plugin.RelationSelectFieldsBundle");

pimcore.plugin.RelationSelectFieldsBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.RelationSelectFieldsBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        // alert("RelationSelectFieldsBundle ready!");
    }
});

var RelationSelectFieldsBundlePlugin = new pimcore.plugin.RelationSelectFieldsBundle();
