<?php
namespace RelationSelectFieldsBundle\Controller\Admin;
use Pimcore\Bundle\AdminBundle\Controller\AdminAbstractController;
use Pimcore\Model\Asset;
use Pimcore\Model\Document;
use Pimcore\Model\DataObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/object-fields/relation-select")
 */
class RelationSelectController extends AdminAbstractController {

    /**
     * Produces the json to feed the roles selector
     * Used by pimcore.object.tags.classSelector
     *
     * @param Request $request
     * @Route("/objects-list")
     * @return JsonResponse
     */
    public function optionsAction(Request $request) {
        $recursive = $request->get("recursive", false) === true || $request->get("recursive") == "true";
        $objectsAllowed = $request->get("objectsAllowed") === true || $request->get("objectsAllowed") == "true";
        $assetsAllowed = $request->get("assetsAllowed") === true || $request->get("assetsAllowed") == "true";
        $documentsAllowed = $request->get("documentsAllowed") === true || $request->get("documentsAllowed") == "true";
        $types = json_decode($request->get("types", "{}"), true);
        if (is_array($types)) {
            if (in_array("object", $types)) {
                $objectsAllowed = true;
            }
            if (in_array("asset", $types)) {
                $assetsAllowed = true;
            }
            if (in_array("document", $types)) {
                $documentsAllowed = true;
            }
        }
        if (!$assetsAllowed && !$documentsAllowed) {
            // if nothing is allowed, use objects
            $objectsAllowed = true;
        }

        $options = [];
        if ($request->get("insertEmpty", "0") === "1") {
            $option = [
                "value"     => "",
                "key"       => "-- Choose --",
                "display"   => "-- Choose --",
                "id"        => "",
                "published" => 1,
                "index"     => 0,
                "type"      => "",
                "subtype"   => "",
            ];
            $options[] = $option;
        }
        if ($objectsAllowed) {
            $options = array_merge(
                $options,
                $this->getObjectOptions($request, $recursive)
            );
        }
        if ($assetsAllowed) {
            $options = array_merge(
                $options,
                $this->getAssetOptions($request, $recursive)
            );
        }
        if ($documentsAllowed) {
            $options = array_merge(
                $options,
                $this->getDocumentOptions($request, $recursive)
            );
        }

        return new JsonResponse($options);
    }

    private function getObjectOptions(Request $request, bool $recursive): array {
        $displayFieldName = $request->get("displayFieldName");
        $classes = $request->get("classes", "");
        $classes = preg_split('/[,]+/', $classes, -1, PREG_SPLIT_NO_EMPTY);
        $objectFolder = $request->get("objectFolder");
        $folder = null;
        if (intval($objectFolder) > 0) {
            $folder = DataObject::getById(intval($objectFolder));
        } else if (!empty($objectFolder)) {
            $folder = DataObject::getByPath($objectFolder);
        }

        $options = [];
        $conditions = [];
        $conditionParams = [];
        if ($folder instanceof DataObject) {
            if ($recursive) {
                $conditions[] = "path LIKE :path";
                $conditionParams["path"] = $folder->getFullPath()."/%";
            } else {
                $conditions[] = "parentId LIKE :parentId";
                $conditionParams["parentId"] = $folder->getId();
            }
        }
        if (count($classes) > 0) {
            $conditions[] = "className IN ('".join("', '", $classes)."')";
        }
        if (!empty($request->get("query"))) {
            $conditions[] = "(key LIKE :query OR o_id IN (SELECT id FROM search_backend_data WHERE maintype = 'object' AND data LIKE :query))";
            $conditionParams["query"] = "%".$request->get("query")."%";
        }

        $objects = new DataObject\Listing();
        $objects->setCondition(join(" AND ", $conditions), $conditionParams);
        $objects->load();

        foreach ($objects as $object) {
            if (!$object instanceof DataObject\Concrete) continue;
            //
            /** @var DataObject\Concrete $object */
            $option = [
                "value"     => $object->getId(),
                "key"       => $object->getKey(),
                "display"   => $object->getKey(),
                "id"        => $object->getId(),
                "published" => $object->getPublished(),
                "index"     => 0,
                "type"      => "object",
                "subtype"   => "object",
            ];
            if (!empty($displayFieldName) && method_exists($object, "get".ucfirst($displayFieldName))) {
                $option["key"] = $object->{"get".ucfirst($displayFieldName)}();
                $option["display"] = $object->{"get".ucfirst($displayFieldName)}();
            }
            if (count($classes) > 1) {
                $option["key"] .= " (".$object->getClassName().")";
                $option["display"] .= " (".$object->getClassName().")";
            }
            if ($request->get("type") === "tomany") {
                $option["fullpath"] = $object->getFullPath();
            } else {
                $option["path"] = $object->getFullPath();
            }

            $options[] = $option;
        }
        return $options;
    }

    private function getAssetOptions(Request $request, bool $recursive): array {
        $assetTypes = $request->get("assetTypes", "");
        $assetTypes = preg_split('/[,]+/', $assetTypes, -1, PREG_SPLIT_NO_EMPTY);
        $assetFolder = $request->get("assetFolder");
        $folder = null;
        if (intval($assetFolder) > 0) {
            $folder = Asset::getById($assetFolder);
        } else if (!empty($assetFolder)) {
            $folder = Asset::getByPath($assetFolder);
        }

        $options = [];
        $conditions = [];
        $conditionParams = [];
        if ($folder instanceof Asset) {
            if ($recursive) {
                $conditions[] = "path LIKE :path";
                $conditionParams["path"] = $folder->getFullPath()."/%";
            } else {
                $conditions[] = "parentId LIKE :parentId";
                $conditionParams["parentId"] = $folder->getId();
            }
        }
        if (count($assetTypes) > 0) {
            $conditions[] = "type IN (:assetTypes)";
            $conditionParams["assetTypes"] = $assetTypes;
        }
        if (!empty($request->get("query"))) {
            $conditions[] = "(filename LIKE :query OR id IN (SELECT id FROM search_backend_data WHERE maintype = 'asset' AND data LIKE :query))";
            $conditionParams["query"] = "%".$request->get("query")."%";
        }

        $assets = new Asset\Listing();
        $assets->setCondition(join(" AND ", $conditions), $conditionParams);
        $assets->load();

        foreach ($assets as $asset) {
            /** @var Asset $asset */
            $option = [
                "value"   => $asset->getId(),
                "key"     => $asset->getFilename(),
                "display" => $asset->getFilename(),
                "id"      => $asset->getId(),
                "type"    => "asset",
                "subtype" => "asset",
            ];
            if ($request->get("type") === "tomany") {
                $option["fullpath"] = $asset->getFullPath();
            } else {
                $option["path"] = $asset->getFullPath();
            }

            $options[] = $option;
        }
        return $options;
    }

    private function getDocumentOptions(Request $request, bool $recursive): array {
        $displayFieldName = $request->get("displayFieldName");
        $documentTypes = $request->get("documentTypes", "");
        $documentTypes = preg_split('/[,]+/', $documentTypes, -1, PREG_SPLIT_NO_EMPTY);
        $documentFolder = $request->get("documentFolder");
        $folder = null;
        if (intval($documentFolder) > 0) {
            $folder = Document::getById($documentFolder);
        } else if (!empty($documentFolder)) {
            $folder = Document::getByPath($documentFolder);
        }

        $options = [];
        $conditions = [];
        $conditionParams = [];
        if ($folder instanceof Document) {
            if ($recursive) {
                $conditions[] = "path LIKE :path";
                $conditionParams["path"] = $folder->getFullPath()."/%";
            } else {
                $conditions[] = "parentId LIKE :parentId";
                $conditionParams["parentId"] = $folder->getId();
            }
        }
        if (count($documentTypes) > 0) {
            $conditions[] = "type IN (:documentTypes)";
            $conditionParams["documentTypes"] = $documentTypes;
        }
        if (!empty($request->get("query"))) {
            $conditions[] = "(`key` LIKE :query OR id IN (SELECT id FROM search_backend_data WHERE maintype = 'document' AND data LIKE :query))";
            $conditionParams["query"] = "%".$request->get("query")."%";
        }

        $documents = new Document\Listing();
        $documents->setCondition(join(" AND ", $conditions), $conditionParams);
        $documents->load();

        foreach ($documents as $document) {
            /** @var Document $document */
            $option = [
                "value"   => $document->getId(),
                "key"     => $document->getKey(),
                "display" => $document->getKey(),
                "id"      => $document->getId(),
                "type"    => "document",
                "subtype" => "document",
            ];
            if (!empty($displayFieldName) && method_exists($document, "get".ucfirst($displayFieldName))) {
                $option["key"] = $document->{"get".ucfirst($displayFieldName)}();
                $option["display"] = $document->{"get".ucfirst($displayFieldName)}();
            }
            if ($request->get("type") === "tomany") {
                $option["fullpath"] = $document->getFullPath();
            } else {
                $option["path"] = $document->getFullPath();
            }

            $options[] = $option;
        }
        return $options;
    }

}
