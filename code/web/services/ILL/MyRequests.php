<?php
require_once ROOT_DIR . '/services/MyAccount/MyAccount.php';

class ILL_MyRequests extends MyAccount {

	function launch() {
		global $interface;

		if (UserAccount::isLoggedIn()) {

			// get the patron - uses logic from MaterialsRequest_MyRequests::launch() (should we?)
			$user = UserAccount::getActiveUserObj();
			$linkedUsers = $user->getLinkedUsers();
			$patronId = empty($_REQUEST['patronId']) ? $user->id : $_REQUEST['patronId'];
			$interface->assign('patronId', $patronId);

			$patron = $user->getUserReferredTo($patronId);
			if (count($linkedUsers) > 0) {
				array_unshift($linkedUsers, $user);
				$interface->assign('linkedUsers', $linkedUsers);
			}
			$interface->assign('selectedUser', $patronId); // needs to be set even when there is only one user so that the patronId hidden input gets a value in the reading history form.

			// get the list of requests for the patron
			$requests = $this->getRequestsBy($patron);
			$interface->assign('ILLRequests', $requests);

			$requestTemplate = 'my-requests.tpl'; // could get this from a catalogConnection (if set first)
			$title = 'My Materials Requests';

			$this->display($requestTemplate, $title);
		} else {
			header('Location: /MyAccount/Home?followupModule=MaterialsRequest&followupAction=MyRequests');
			exit;
		}
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

		// handle any curl errors
		$err = curl_error($curl);
		if (!empty($err)) {
			return new AspenError('an error occurred while sending your request: ' . $err);
		}

		// extract the request body into an associative array
		$headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$body = substr($response, $headerSize);
		$requestList = json_decode($body, true);

		// shut down the connection
		curl_close($curl);
		return $requestList;
	}

	function getBreadcrumbs(): array {
		$breadcrumbs = [];
		return $breadcrumbs;
	}
}
