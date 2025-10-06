<?php
namespace RelationSelectFieldsBundle\Model\Object\Data;
use Pimcore\Model\DataObject\ClassDefinition;

class ManyToManyRelationSelect extends ClassDefinition\Data\ManyToManyRelation {

    /**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = "manyToManyRelationSelect";
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
    public function getRecursive(): bool {
        return $this->recursive;
    }

    /**
     * @param boolean $recursive
     * @return $this
     */
    public function setRecursive($recursive): static {
        $this->recursive = $recursive;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayFieldName(): string {
        return $this->displayFieldName;
    }

    /**
     * @param string $displayFieldName
     * @return $this
     */
    public function setDisplayFieldName($displayFieldName): static {
        $this->displayFieldName = $displayFieldName;
        return $this;
    }

    /**
     * @return string
     */
    public function getObjectFolder(): string {
        return $this->objectFolder;
    }

    /**
     * @param string $objectFolder
     * @return $this
     */
    public function setObjectFolder($objectFolder): static {
        $this->objectFolder = $objectFolder;
        return $this;
    }

    /**
     * @return string
     */
    public function getAssetFolder(): string {
        return $this->assetFolder;
    }

    /**
     * @param string $assetFolder
     * @return $this
     */
    public function setAssetFolder($assetFolder): static {
        $this->assetFolder = $assetFolder;
        return $this;
    }

    /**
     * @return string
     */
    public function getDocumentFolder(): string {
        return $this->documentFolder;
    }

    /**
     * @param string $documentFolder
     * @return $this
     */
    public function setDocumentFolder($documentFolder): static {
        $this->documentFolder = $documentFolder;
        return $this;
    }

}
