<?php
function getGrapesJSUpdates() {
    return [
        'createGrapesJSModule' => [
			'title' => 'Create GrapesJS module',
			'description' => 'Setup modules for GrapesJS Integration',
			'sql' => [
				"INSERT INTO modules (name, indexName, backgroundProcess) VALUES ('GrapesJS', 'grapes_js', '')",
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
				"CREATE TABLE grapesjs_page (
					id INT(11) AUTO_INCREMENT PRIMARY KEY,
					title VARCHAR(100) NOT NULL,
					urlAlias VARCHAR(100),
					showSidebar TINYINT(1),
					contents MEDIUMTEXT
				) ENGINE=INNODB",
			],
		],
    ];
}