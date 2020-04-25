
pimcore.registerNS("pimcore.object.tags.manyToOneRelationSelect");
pimcore.object.tags.manyToOneRelationSelect = Class.create(pimcore.object.tags.manyToOneRelation, {

    type: "manyToOneRelationSelect",
    dataChanged: false,

    initialize: function (data, layoutConf) {
        this.fieldConfig = layoutConf;
        this.mode = 'remote';
        this.fieldConfig.index = 15008;
        if (data) {
            this.data = data;
        }

        var storeParams = {
            objectFolder: this.fieldConfig.objectFolder,
            assetFolder: this.fieldConfig.assetFolder,
            documentFolder: this.fieldConfig.documentFolder,
            displayFieldName: this.fieldConfig.displayFieldName,
            recursive: this.fieldConfig.recursive,
            objectsAllowed: this.fieldConfig.objectsAllowed,
            assetsAllowed: this.fieldConfig.assetsAllowed,
            documentsAllowed: this.fieldConfig.documentsAllowed,
            classes: this.fieldConfig.classes.map(function(i) { return i.classes; }).join(','),
            assetTypes: this.fieldConfig.assetTypes.map(function(i) { return i.assetTypes; }).join(','),
            documentTypes: this.fieldConfig.documentTypes.map(function(i) { return i.documentTypes; }).join(','),
        };

        this.store = new Ext.data.JsonStore({
            proxy: {
                type: 'ajax',
                url: '/admin/object-fields/relation-select/objects-list?type=toone',
                extraParams: storeParams,
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
    },

    getLayoutEdit: function () {
        var options = {
            name: this.fieldConfig.name,
            triggerAction: "all",
            editable: false,
            fieldLabel: this.fieldConfig.title,
            store: this.store,
            componentCls: "object_field",
            displayField: "key",
            valueField: "id",
            labelWidth: this.fieldConfig.labelWidth ? this.fieldConfig.labelWidth : 100,
            autoLoadOnValue: true,
            value: this.data ? this.data.id : null,
            listeners: {
                select: function (el) {
                    console.log("many to one changed");
                    this.dataChanged = true;
                }.bind(this)
            },
            tpl: new Ext.XTemplate(
                '<tpl for="."><li role="option" unselectable="on" class="x-boundlist-item" data-recordid="{value}" style="display:flex;">',
                '  {key}<div class="combo-texture" style="margin-left:auto;font-style:italic;font-size:80%;">{type}</div>',
                '</li></tpl>'
            ),
        };

        options.width = 300;
        if (this.fieldConfig.width) {
            options.width = this.fieldConfig.width;
        }

        options.width += options.labelWidth;

        if (this.fieldConfig.height) {
            options.height = this.fieldConfig.height;
        }

        if (typeof this.data == "string" || typeof this.data == "number") {
            options.value = this.data;
        }

        this.component = Ext.create('Ext.form.ComboBox', options);

        return this.component;
    },

    getGridColumnEditor:function (field) {
        return null;
    },

    getGridColumnFilter:function (field) {
        return null;
    },

    getValue: function() {
        var selectedId = this.component.getValue();
        return this.store.data.items.filter(function(item) {
            return (selectedId === item.id);
        })[0].data;
    },

    isDirty:function () {
        if (this.component) {
            if (!this.component.rendered) {
                return false;
            } else {
                return this.dataChanged;
            }
        }
    }

});
