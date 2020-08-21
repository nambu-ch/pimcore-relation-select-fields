<?php
namespace RelationSelectFieldsBundle;
use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class RelationSelectFieldsBundle extends AbstractPimcoreBundle {

    public function getEditmodeJsPaths() {
        return [
            "/bundles/relationselectfields/admin/js/document-tags/many-to-one-relation-select.js",
            "/bundles/relationselectfields/admin/js/document-tags/many-to-many-relation-select.js",
        ];
    }

    public function getJsPaths() {
        return [
            "/bundles/relationselectfields/admin/js/classfields/many-to-one-relation-select/data.js",
            "/bundles/relationselectfields/admin/js/classfields/many-to-one-relation-select/tag.js",
            "/bundles/relationselectfields/admin/js/classfields/many-to-many-relation-select/data.js",
            "/bundles/relationselectfields/admin/js/classfields/many-to-many-relation-select/tag.js",
        ];
    }

}
