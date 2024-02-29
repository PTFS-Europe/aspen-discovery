<?php
class GrapesJS_NewBlankPage extends Action {
	/** @var NewBlankPage */
	private $newBlankPage;

	function __construct() {
		parent::__construct();

		require_once ROOT_DIR . '/sys/GrapesJS/NewBlankPage.php';

		global $interface;

		$id = strip_tags($_REQUEST['id']);
		$this->newBlankPage = new NewBlankPage();
		$this->newBlankPage->id = $id;

	// 	if (!$this->newBlankPage->find(true)) {
	// 		$interface->assign('module', 'Error');
	// 		$interface->assign('action', 'Handle404');
	// 		require_once ROOT_DIR . "/services/Error/Handle404.php";
	// 		$actionClass = new Error_Handle404();
	// 		$actionClass->launch();
	// 		die();
	// 	} elseif (!$this->canView()) {
	// 		$interface->assign('module', 'Error');
	// 		$interface->assign('action', 'Handle401');
	// 		$interface->assign('followupModule', 'GrapesJS');
	// 		$interface->assign('followupAction', 'NewBlankPage');
	// 		$interface->assign('id', $id);
	// 		require_once ROOT_DIR . "/services/Error/Handle401.php";
	// 		$actionClass = new Error_Handle401();
	// 		$actionClass->launch();
	// 		die();
	// 	}
	}

	function launch() {
		global $interface;

		$title = $this->newBlankPage->title;
		// $title = 'New Blank Page';
		$interface->assign('id', $this->newBlankPage->id);
		$interface->assign('contents', $this->newBlankPage->getFormattedContents());
		$interface->assign('title', $title);

		$this->display('new-blank-page.tpl', $title, '', false);
	}

	function canView(): bool {
		return $this->newBlankPage->canView();
	}

	function getBreadcrumbs(): array {
		$breadcrumbs = [];
		// $breadcrumbs[] = new Breadcrumb('/', 'Home');
		// if ($this->newBlankPage != null) {
		// 	$breadcrumbs[] = new Breadcrumb('', $this->newBlankPage->title, true);
		// 	if (UserAccount::userHasPermission([
		// 		'Administer All Basic Pages',
		// 		'Administer Library Basic Pages',
		// 	])) {
		// 		// $breadcrumbs[] = new Breadcrumb('/WebBuilder/BasicPages?id=' . $this->newBlankPage->id . '&objectAction=edit', 'Edit', true);
		// 	}
		// }
		return $breadcrumbs;
	}
}