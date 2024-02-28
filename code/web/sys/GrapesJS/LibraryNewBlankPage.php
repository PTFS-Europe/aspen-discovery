<?php
require_once ROOT_DIR . '/sys/DB/DataObject.php';
 class LibraryNewBlankPage extends DataObject {
	public $__table = 'library_grapesjs_new_blank_page';
	public $id;
	public $libraryId;
	public $newBlankPageId;
}