<?php
namespace RelationSelectFieldsBundle\Model\Document\Tag;
use Pimcore\Model;

/**
 * @method \Pimcore\Model\Document\Tag\Dao getDao()
 */
class ManyToOneRelationSelect extends Model\Document\Tag\Relation {

    /**
     * @return string
     * @see TagInterface::getType
     *
     */
    public function getType() {
        return 'relation_select';
    }

}
