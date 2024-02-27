<?php
class NewBlankPage extends DB_LibraryLinkedObject {

    public $_table = 'grapesjs_new_blank_page';
    public $id;
    public $title;
	public $urlAlias;
	public $requireLogin;
	public $requireLoginUnlessInLibrary;
	public $teaser;
	public $contents;
	public $lastUpdate;

    public function getUniquenessFields(): array {
        return [
            'id',
        ];
    }

    static function getObjectStructure($context = ''): array {

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
			'teaser' => [
				'property' => 'teaser',
				'type' => 'textarea',
				'label' => 'Teaser',
				'description' => 'Teaser for display on portals',
				'maxLength' => 512,
				'hideInLists' => true,
			],
			'contents' => [
				'property' => 'contents',
				'type' => 'markdown',
				'label' => 'Page Contents',
				'description' => 'The contents of the page',
				'hideInLists' => true,
			],
            'lastUpdate' => [
				'property' => 'lastUpdate',
				'type' => 'timestamp',
				'label' => 'Last Update',
				'description' => 'When the page was changed last',
				'default' => 0,
			],
        ];
    }
}