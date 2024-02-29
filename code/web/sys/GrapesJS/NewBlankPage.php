<?php
require_once ROOT_DIR . '/sys/DB/LibraryLinkedObject.php';
require_once ROOT_DIR . '/sys/GrapesJS/LibraryNewBlankPage.php';
class NewBlankPage extends DB_LibraryLinkedObject {
	// class NewBlankPage extends DataObject {

    public $__table = 'grapesjs_new_blank_page';
    public $id;
    public $title;
	public $urlAlias;
	public $requireLogin;
	public $requireLoginUnlessInLibrary;
	public $teaser;
	public $contents;
	// public $lastUpdate;
	private $_libraries;
	private $_audiences;
	private $_categories;
	private $_allowAccess;
	private $_allowableHomeLocations;

    public function getUniquenessFields(): array {
        return [
            'id',
        ];
    }

    static function getObjectStructure($context = ''): array {
		$libraryList = Library::getLibraryList(!UserAccount::userHasPermission('Administer All Basic Pages'));
        return [
            'id' => [
                'property' => 'id',
				'type' => 'label',
				'label' => 'Id',
				'description' => 'The unique id within the database',
            ],
            'title' => [
				'property' => 'title',
				'type' => 'text',
				'label' => 'Title',
				'description' => 'The title of the page',
				'size' => '40',
				'maxLength' => 100,
			],
			'urlAlias' => [
				'property' => 'urlAlias',
				'type' => 'text',
				'label' => 'URL Alias (no domain, should start with /)',
				'description' => 'The url of the page (no domain name)',
				'size' => '40',
				'maxLength' => 100,
			],
			// 'teaser' => [
			// 	'property' => 'teaser',
			// 	'type' => 'textarea',
			// 	'label' => 'Teaser',
			// 	'description' => 'Teaser for display on portals',
			// 	'maxLength' => 512,
			// 	'hideInLists' => true,
			// ],
			'contents' => [
				'property' => 'contents',
				'type' => 'grapesjs',
				'label' => 'Page Contents',
				'description' => 'The contents of the page',
				'hideInLists' => true,
			],
			'requireLogin' => [
				'property' => 'requireLogin',
				'type' => 'checkbox',
				'label' => 'Require login to access',
				'description' => 'Require login to access page',
				'onchange' => 'return AspenDiscovery.GrapesJS.updateGrapesJSFields();',
				'default' => 0,
			],
			'requireLoginUnlessInLibrary' => [
				'property' => 'requireLoginUnlessInLibrary',
				'type' => 'checkbox',
				'label' => 'Allow access without logging in while in library',
				'description' => 'Require login to access page unless in library',
				'default' => 0,
			],
			'libraries' => [
				'property' => 'libraries',
				'type' => 'multiSelect',
				'listStyle' => 'checkboxSimple',
				'label' => 'Libraries',
				'description' => 'Define libraries that use these settings',
				'values' => $libraryList,
				'hideInLists' => true,
			]
        ];
    }

	public function getFormattedContents() {
		require_once ROOT_DIR . '/sys/Parsedown/AspenParsedown.php';
		$parsedown = AspenParsedown::instance();
		$parsedown->setBreaksEnabled(true);
		return $parsedown->parse($this->contents);
	}

	public function insert($context = '') {
		$this->lastUpdate = time();
		$ret = parent::insert();
		if ($ret !== FALSE) {
			// $this->saveLibraries();
			// $this->saveAudiences();
			// $this->saveCategories();
			// $this->saveAccess();
			// $this->saveAllowableHomeLocations();
		}
		return $ret;
	}

	public function delete($useWhere = false) {
		$ret = parent::delete($useWhere);
		if ($ret && !empty($this->id)) {
			// $this->clearLibraries();
			// $this->clearAudiences();
			// $this->clearCategories();
			// $this->clearAccess();
			// $this->clearAllowableHomeLocations();
		}
		return $ret;
	}

	public function getLibraries(): ?array {
		if (!isset($this->_libraries) && $this->id) {
			$this->_libraries = [];
			$libraryLink = new LibraryNewBlankPage();
			$libraryLink->newBlankPageId = $this->id;
			$libraryLink->find();
			while ($libraryLink->fetch()) {
				$this->_libraries[$libraryLink->libraryId] = $libraryLink->libraryId;
			}
		}
		return $this->_libraries;
	}

	public function saveLibraries() {
		if (isset($this->_libraries) && is_array($this->_libraries)) {
			// $this->clearLibraries();

			foreach ($this->_libraries as $libraryId) {
				$libraryLink = new LibraryNewBlankPage();

				$libraryLink->newBlankPageId = $this->id;
				$libraryLink->libraryId = $libraryId;
				$libraryLink->insert();
			}
			unset($this->_libraries);
		}
	}

	public function canView(): bool {
		global $locationSingleton;

		$requireLogin = $this->requireLogin;
		$allowInLibrary = $this->requireLoginUnlessInLibrary;

		if ($requireLogin) {
			$activeLibrary = $locationSingleton->getActiveLocation();
			$user = UserAccount::getLoggedInUser();
			if ($allowInLibrary && $activeLibrary != null) {
				return true;
			}
			if (!$user) {
				// return false;
				$okToAccess = true;
			} else {
				$okToAccess = true;
			}

			if ($okToAccess) {
				return $okToAccess;
			}
		} else {
			return true;
		}
	}
}


