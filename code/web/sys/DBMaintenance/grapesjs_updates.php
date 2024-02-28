<?php
/** @noinspection SqlResolve */
function getGrapesJSUpdates() {
    return [
        'createGrapesJSModule' => [
			'title' => 'Create GrapesJS module',
			'description' => 'Setup modules for GrapesJS Integration',
			'sql' => [
				"INSERT INTO modules (name, indexName, backgroundProcess) VALUES ('GrapesJS', 'grapesjs', '')",
			],
		],
        //create web builder module
        'grapesjs_module_monitoring_and_indexing' => [
			'title' => 'GrapesJS Module - Monitoring, indexing',
			'description' => 'Update GrapesJS module to monitor logs and start indexer',
			'sql' => [
				"UPDATE modules set backgroundProcess='web_indexer', logClassPath='/sys/WebsiteIndexing/WebsiteIndexLogEntry.php', logClassName='WebsiteIndexLogEntry' WHERE name = 'GrapesJS'",
			],
		],
        'grapesjs_pages' => [
			'title' => 'GrapesJS Pages',
			'description' => 'Setup GrapesJS Pages',
			'sql' => [
				"CREATE TABLE grapesjs_new_blank_page (
					id INT(11) AUTO_INCREMENT PRIMARY KEY,
					title VARCHAR(100) NOT NULL,
					urlAlias VARCHAR(100),
					requireLogin TINYINT(1) DEFAULT 0,
					requireLoginUnlessInLibrary TINYINT(1) DEFAULT 0,
					contents MEDIUMTEXT
				) ENGINE = InnoDB",
			],
		],
		'grapesjs_by_library' => [
			'title' => 'GrapesJS add Library Scoping',
			'description' => 'Add the ability to scope the grapesJS content',
			'sql' => [
				"CREATE TABLE library_grapesjs_new_blank_page (
					id  INT(11) AUTO_INCREMENT PRIMARY KEY,
					libraryId INT(11) NOT NULL,
					newBlankPageId INT(11) NOT NULL,
					INDEX libraryId(libraryId),
					INDEX newBlankPageId(newBlankPageId)
				) ENGINE = InnoDB",

			],
		],
    ];
}