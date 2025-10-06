<?php
namespace RelationSelectFieldsBundle\Model\Document\Tag;
use Pimcore\Model;

/**
 * @method \Pimcore\Model\Document\Editable\Dao getDao()
 */
class ManyToOneRelationSelect extends Model\Document\Editable\Relation {

    /**
     * @return string
     * @see TagInterface::getType
     *
     */
    public function getType(): string {
        return 'relation_select';
    }

}
