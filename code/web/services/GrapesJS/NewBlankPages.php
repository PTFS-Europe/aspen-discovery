<?php
require_once ROOT_DIR . '/sys/GrapesJS/NewBlankPage.php';
require_once ROOT_DIR . '/services/Admin/ObjectEditor.php';
require_once ROOT_DIR . '/Action.php';

class GrapesJS_NewBlankPages extends ObjectEditor{

    function getObjectType(): string {
        return 'NewBlankPage';
    }

    function getToolName(): string {
		return 'NewBlankPages';
	}

	function getModule(): string {
		return 'GrapesJS';
	}

	function getPageTitle(): string {
		return 'GrapesJS New Blank Pages';
	}

    function getAllObjects($page, $recordsPerPage): array {
        $object = new NewBlankPage();
		$object->orderBy($this->getSort());
		$this->applyFilters($object);
		$object->limit(($page - 1) * $recordsPerPage, $recordsPerPage);
		$userHasExistingObjects = true;
		if (!UserAccount::userHasPermission('Administer All Basic Pages')) {
			$userHasExistingObjects = $this->limitToObjectsForLibrary($object, 'LibraryBasicPage', 'newBlankPageId');
		}
		$objectList = [];
		if ($userHasExistingObjects) {
			$object->find();
			while ($object->fetch()) {
				$objectList[$object->id] = clone $object;
			}
		}
		return $objectList;
    }

    function getDefaultSort(): string {
		return 'title asc';
	}

	function getObjectStructure($context = ''): array {
		return NewBlankPage::getObjectStructure($context);
	}

	function getPrimaryKeyColumn(): string {
		return 'id';
	}

	function getIdKeyColumn(): string {
		return 'id';
	}

    function getAdditionalObjectActions($existingObject): array {
		$objectActions = [];
		if (!empty($existingObject) && $existingObject instanceof NewBlankPage && !empty($existingObject->id)) {
			$objectActions[] = [
				'text' => 'View',
				'url' => empty($existingObject->urlAlias) ? '/GrapesJS/NewBlankPage?id=' . $existingObject->id : $existingObject->urlAlias,
			];
		}
		return $objectActions;
	}

    function getInstructions(): string {
		return 'https://help.aspendiscovery.org/help/GrapesJS/pages';
    }

	function getInitializationJs(): string {
		return 'AspenDiscovery.GrapesJS.updateGrapesJSFields()';
	}

    function getBreadcrumbs(): array {
		$breadcrumbs = [];
		$breadcrumbs[] = new Breadcrumb('/Admin/Home', 'Administration Home');
		$breadcrumbs[] = new Breadcrumb('/Admin/Home#grapesjs', 'GrapesJS');
		$breadcrumbs[] = new Breadcrumb('/GrapesJS/NewBlankPages', 'GrapesJS New Blank Pages');
		return $breadcrumbs;
	}

    function canView(): bool {
		return UserAccount::userHasPermission([
			'Administer All Basic Pages',
			'Administer Library Basic Pages',
		]);
	}

	function canBatchEdit(): bool {
		return UserAccount::userHasPermission([
			'Administer All Basic Pages',
		]);
	}

	function getActiveAdminSection(): string {
		return 'grapesjs';
	}

	public function canCopy() {
		return $this->canAddNew();
	}


    // function __construct() {
    //     global $interface;
    //     echo('lalasldljfwelww');
    //     $this->display('new-blank-page.tpl', 'New Blank Page', 'Search/home-sidebar.tpl', false);
    // }
    
    // function launch() {
    //     global $interface;
    //     $this->display('new-blank-page.tpl', 'New Blank Page', 'Search/home-sidebar.tpl', false);
    // }
    // function getBreadcrumbs():array {
    //     $breadcrumbs = [];
    //     return $breadcrumbs;
    // }
}