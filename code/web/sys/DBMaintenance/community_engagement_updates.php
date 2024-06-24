<?php
/**@noinspection SqlResolve*/
function getCommunityEngagementUpdates() {
    return [
        'community_builder_module' => [
			'title' => 'Community Module',
			'description' => 'Create Community Module',
			'sql' => [
				"INSERT INTO modules (name, indexName, backgroundProcess) VALUES ('Community', '', '')",
			],
		],
        'create_campaigns' => [
            'title' => 'Create Campaigns',
            'description' => 'Add table for campaigns',
            'sql' => [
                "CREATE TABLE campaign (
                    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) NOT NULL
                ) ENGINE = InnoDB",
            ],
        ],
        'create_milestones' => [
            'title' => 'Create Milestones',
            'description' => 'Add table for milestones',
            'sql' => [
                "CREATE TABLE milestone (
                    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) NOT NULL
                ) ENGINE = InnoDB",
            ],
        ],
    ];
}