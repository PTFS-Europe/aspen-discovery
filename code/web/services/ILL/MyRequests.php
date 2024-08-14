<?php
require_once ROOT_DIR . '/services/MyAccount/MyAccount.php';

class ILL_MyRequests extends MyAccount {

	function launch() {

	}

	function getBreadcrumbs(): array {
		$breadcrumbs = [];
		return $breadcrumbs;
	}
}
