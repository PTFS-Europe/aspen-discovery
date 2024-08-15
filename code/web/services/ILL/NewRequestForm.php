<?php

require_once ROOT_DIR . '/Action.php';

class ILL_NewRequestForm extends Action{
    function launch() {
        global $interface;
        $title = 'ILL Request Form';
        $interface->assign('title', $title);
        $this->display('Form.tpl', $title);
    }

    function getBreadcrumbs(): array {
		$breadcrumbs = [];
		$breadcrumbs[] = new Breadcrumb('/Union/Search', 'Search Results');
        $breadcrumbs[] = new Breadcrumb('/ILL/RequestForm', 'ILL Request Form');
		return $breadcrumbs;
	}
}