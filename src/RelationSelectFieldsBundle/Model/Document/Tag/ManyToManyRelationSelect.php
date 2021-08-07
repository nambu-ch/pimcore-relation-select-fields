<?php
namespace RelationSelectFieldsBundle\Model\Document\Tag;
use Pimcore\Model;

/**
 * @method \Pimcore\Model\Document\Editable\Dao getDao()
 */
class ManyToManyRelationSelect extends Model\Document\Editable\Relations implements \Iterator {

    /**
     * @return string
     * @see TagInterface::getType
     *
     */
    public function getType() {
        return 'relations_select';
    }

}
