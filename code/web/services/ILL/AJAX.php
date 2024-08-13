<?php

require_once ROOT_DIR . '/Action.php';
require_once ROOT_DIR . '/sys/ILL/Request.php';

/**
 * ILLRequest AJAX Page, handles sending ILL Requests from Aspen to Koha.
 */
class ILL_AJAX extends Action
{
	function launch()
	{
		$method = $_GET['method'];
		if (method_exists($this, $method)) {
			header('Content-type: application/json');
			header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			$result = $this->$method();
			echo json_encode($result);
		} else {
			echo json_encode(['error' => 'invalid_method']);
		}
	}

	function postFormData()
	{
		$user = UserAccount::getActiveUserObj();
	
		$reqBody = [
			"ill_backend_id" => "FreeForm", // temporarily hard-coded
			"patron_id" => $user->id,
			"library_id" => $user->_homeLibrary->subdomain, 
			"extended_attributes" => [
				['type' => 'article_title', 'value' => $_POST['article_title']],
				['type' => 'associated_id', 'value' => $_POST['associated_id']],
				['type' => 'author', 'value' => $_POST['author']],
				['type' => 'issn', 'value' => $_POST['issn']],
				['type' => 'issue', 'value' => $_POST['issue']],
				['type' => 'pages', 'value' => $_POST['pages']],
				['type' => 'publisher', 'value' => $_POST['publisher']],
				['type' => 'pubmedid', 'value' => $_POST['pubmedid']],
				['type' => 'title', 'value' => $_POST['title']],
				['type' => 'volume', 'value' => $_POST['volume']],
				['type' => 'year', 'value' => $_POST['year']]
			]
		];

		$reqUrl = 'http://localhost:8081/api/v1/ill/requests'; // might need to replace localhost with IP if curl err 7 encountered -> TODO: store the url elsewhere 
		$credentials = $user->ils_username . ':' . $user->ils_password; // $user->ils_password is likely to be null -> TODO: need to find a way to safely get and pass this credential 

		$curl = curl_init($reqUrl);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($reqBody));
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, $credentials);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);

		$err = curl_error($curl);
		if(!empty($err)) {
			return new AspenError('an error occurred while sending your request: ' . $err );
		}
		
		header('Location: ' . 'http://localhost:8083/ILL/NewRequestForm'); // goes back to the new request form -> TODO: add a success message to display
	}

	function getBreadcrumbs(): array
	{
		return [];
	}
}
