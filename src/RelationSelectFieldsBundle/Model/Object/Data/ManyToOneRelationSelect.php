<?php
namespace RelationSelectFieldsBundle\Model\Object\Data;
use Pimcore\Model\DataObject\ClassDefinition;

class ManyToOneRelationSelect extends ClassDefinition\Data\ManyToOneRelation {

    /**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = "manyToOneRelationSelect";
    /**
     * @var boolean
     */
    public $recursive = false;
    /**
     * @var string
     */
    public $displayFieldName = null;
    /**
     * @var string
     */
    public $objectFolder = null;
    /**
     * @var string
     */
    public $assetFolder = null;
    /**
     * @var string
     */
    public $documentFolder = null;

    /**
     * @return boolean
     */
    public function getRecursive() {
        return $this->recursive;
    }

    /**
     * @param boolean $recursive
     * @return $this
     */
    public function setRecursive($recursive) {
        $this->recursive = $recursive;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayFieldName() {
        return $this->displayFieldName;
    }

    /**
     * @param string $displayFieldName
     * @return $this
     */
    public function setDisplayFieldName($displayFieldName) {
        $this->displayFieldName = $displayFieldName;
        return $this;
    }

    /**
     * @return string
     */
    public function getObjectFolder() {
        return $this->objectFolder;
    }

    /**
     * @param string $objectFolder
     * @return $this
     */
    public function setObjectFolder($objectFolder) {
        $this->objectFolder = $objectFolder;
        return $this;
    }

    /**
     * @return string
     */
    public function getAssetFolder() {
        return $this->assetFolder;
    }

    /**
     * @param string $assetFolder
     * @return $this
     */
    public function setAssetFolder($assetFolder) {
        $this->assetFolder = $assetFolder;
        return $this;
    }

    /**
     * @return string
     */
    public function getDocumentFolder() {
        return $this->documentFolder;
    }

    /**
     * @param string $documentFolder
     * @return $this
     */
    public function setDocumentFolder($documentFolder) {
        $this->documentFolder = $documentFolder;
        return $this;
    }

}
