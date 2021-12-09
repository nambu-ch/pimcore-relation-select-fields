
pimcore.registerNS("pimcore.document.editables.relation_select");
pimcore.document.editables.relation_select = Class.create(pimcore.document.editable, {

    store: null,

    initialize: function(id, name, config, data, inherited) {
        this.id = id;
        this.name = name;
        this.setupWrapper();
        this.config = this.parseConfig(config);
        this.config.name = id + "_editable";

        this.data = data;
    },
    
    render: function () {
        this.setupWrapper();
        var jsonParams = this.parseConfig(this.config);

        if (this.data) {
            this.config.value = this.data.id;
        }
        if (jsonParams.types) {
            jsonParams.types = JSON.stringify(jsonParams.types);
        }
        if (jsonParams.subtypes) {
            jsonParams.subtypes = JSON.stringify(jsonParams.subtypes);
        }
        if (jsonParams.classes) {
            jsonParams.classes = jsonParams.classes.join(",");
        }

        this.store = new Ext.data.JsonStore({
            proxy: {
                type: 'ajax',
                url: '/admin/object-fields/relation-select/objects-list?type=toone',
                extraParams: jsonParams,
                reader: {
                    type: 'json',
                    rootProperty: 'options',
                    successProperty: 'success',
                    messageProperty: 'message'
                }
            },
            fields: ["key", "value"],
            listeners: {
                load: function(store, records, success, operation) {
                    if (!success) {
                        pimcore.helpers.showNotification(t("error"), t("error_loading_options"), "error", operation.getError());
                    }
                }.bind(this)
            },
            autoLoad: true
        });

        this.config.height = 'auto';
        this.config.width = '100%';
        this.config.store = this.store;
        this.config.triggerAction = "all";
        this.config.displayField = "display";
        this.config.valueField = "id";
        this.config.tpl = new Ext.XTemplate(
            '<tpl for="."><li role="option" unselectable="on" class="x-boundlist-item" data-recordid="{value}" style="display:flex;">',
            '  {key}<div class="combo-texture" style="margin-left:auto;font-style:italic;font-size:80%;">{type}</div>',
            '</li></tpl>'
        );
        this.element = Ext.create('Ext.form.ComboBox', this.config);

        this.element.render(this.id);
    },
    

    getValue: function () {
        var selectedId = this.element.getValue();
        return this.store.data.items.filter(function(item) {
            return (selectedId === item.id);
        })[0].data;
    },

    isDirty: function() {
        return true;
    },

    getType: function () {
        return "relation_select";
    }
});
