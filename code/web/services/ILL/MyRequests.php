<?php
require_once ROOT_DIR . '/services/MyAccount/MyAccount.php';

class ILL_MyRequests extends MyAccount {

	function launch() {
	}

	private function getRequestsBy($user) {
		$catalog = CatalogFactory::getCatalogConnectionInstance();
		$reqUrl = $catalog->getStaffClientBaseURL() . '/api/v1/ill/requests' . "?q={\"patron_id\":" . ($user->unique_ils_id) . "}";

		$credentials = $user->ils_username . ':' . $user->ils_password; // $user->ils_password is likely to be null -> TODO: need to find a way to safely get and pass this credential

		// fetch the ILL requests made by the user
		$curl = curl_init($reqUrl);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, $credentials);
		$response = curl_exec($curl);

	}

	function getBreadcrumbs(): array {
		$breadcrumbs = [];
		return $breadcrumbs;
	}
}
