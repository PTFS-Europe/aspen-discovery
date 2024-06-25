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
                    name VARCHAR(100) NOT NULL DEFAULT ''
                ) ENGINE = InnoDB",
            ],
        ],
        'create_milestones' => [
            'title' => 'Create Milestones',
            'description' => 'Add table for milestones',
            'sql' => [
                "CREATE TABLE milestone (
                    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) NOT NULL DEFAULT ''
                ) ENGINE = InnoDB",
            ],
        ],
        'add_milestones_to_campaign' => [
            'title' => 'Add Milestones to Campaign',
            'description' => 'Add milestone selection to campaigns',
            'sql' => [
                "ALTER TABLE campaign ADD COLUMN milestoneOne INT(11) DEFAULT -1",
                "ALTER TABLE campaign ADD COLUMN milestoneTwo INT(11) DEFAULT -1",
            ],
        ],
        'add_start_and_end_date_to_campaign' => [
            'title' => 'Add Start and End Date to Campaign',
            'description' => 'Add start and end dates to campaigns',
            'sql' => [
                "ALTER TABLE campaign ADD COLUMN startDate INT NULL",
                "ALTER TABLE campaign ADD COLUMN endDate INT NULL",
            ],
        ],
        'add_reward_to_milestone' => [
            'title' => 'Add Reward to Milestone',
            'description' => 'Add reward to milestone',
            'sql' => [
                "ALTER TABLE milestone ADD COLUMN reward INT(11) DEFAULT -1",
            ],
        ],
        'add_a_rewards_table' => [
            'title' => 'Add a Rewards Table',
            'description' => 'Add a table to store rewards',
            'sql' => [
                "CREATE TABLE reward (
                    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) NOT NULL DEFAULT '',
                    rewardType INT(11) DEFAULT -1
                ) ENGINE = InnoDB",
            ],
        ],
        'add_reward_type_table' => [
            'title' => 'Add a Reward Type Table',
            'description' => 'Add a table to store reward types',
            'sql' => [
                "CREATE TABLE reward_type (
                    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    rewardType INT(11) DEFAULT -1
                ) ENGINE = InnoDB",
            ],
        ],
        'alter_reward_type_column_type' => [
            'title' => 'Alter Reward Type Column',
            'description' => 'Alter the data type of the reward type column',
            'sql' => [
                "ALTER TABLE reward_type MODIFY COLUMN rewardType VARCHAR(100) NOT NULL",
            ],
        ],
        'alter_reward_type_table' => [
            'title' => 'Alter Reward Type Column',
            'description' => 'Alter the data type of the reward type column',
            'sql' => [
                "ALTER TABLE reward_type DROP COLUMN rewardType",
                "ALTER TABLE reward_type ADD COLUMN name VARCHAR(100) NOT NULL DEFAULT ''",
            ],
        ],
    ];
}