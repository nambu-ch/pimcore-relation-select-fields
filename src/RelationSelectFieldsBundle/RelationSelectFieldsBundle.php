<?php
namespace RelationSelectFieldsBundle;
use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;

class RelationSelectFieldsBundle extends AbstractPimcoreBundle {

    use PackageVersionTrait;

    const PACKAGE_NAME = 'nambu-ch/pimcore-relation-select-fields';

    protected function getComposerPackageName()
    {
        return self::PACKAGE_NAME;
    }

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
