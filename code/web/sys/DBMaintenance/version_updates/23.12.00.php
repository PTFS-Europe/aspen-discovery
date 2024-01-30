<?php

function getUpdates23_12_00(): array {
	$curTime = time();
	return [
		/*'name' => [
			 'title' => '',
			 'description' => '',
			 'continueOnError' => false,
			 'sql' => [
				 ''
			 ]
		 ], //name*/

		//mark - ByWater

		'disable_circulation_actions' => [
			'title' => 'Disable Circulation Actions',
			'description' => 'Add an option to disable circulation actions for a user.',
			'continueOnError' => false,
			'sql' => [
				'ALTER TABLE user ADD COLUMN disableCirculationActions TINYINT(1) DEFAULT 0'
			]
		], //name

		//kirstien - ByWater

		//kodi - ByWater
		'rename_axis360_permission' => [
			'title' => 'Rename Permission: Administer Axis 360',
			'description' => 'Rename permission "Administer Axis 360" to "Administer Boundless"',
			'continueOnError' => true,
			'sql' => [
				"UPDATE permissions SET description = 'Allows the user configure Boundless integration for all libraries.' WHERE name = 'Administer Axis 360'",
				"UPDATE permissions SET name = 'Administer Boundless' WHERE name = 'Administer Axis 360'",
			]
		], //rename_axis360_permission
		'rename_axis360_module' => [
			'title' => 'Rename Axis 360 Module',
			'description' => 'Rename Axis 360 module to Boundless',
			'continueOnError' => true,
			'sql' => [
				"UPDATE modules SET name = 'Boundless' WHERE name = 'Axis 360'",
			]
		], //rename_axis360_module

		//lucas - Theke
		'show_quick_poll_results' => [
			'title' => 'Display Quick Poll Results',
			'description' => 'Allows the user to show the results of quick polls to those patrons who are not logged in, as well as to choose whether to show graphs, tables or both.',
			'continueOnError' => true,
			'sql' => [
				'ALTER TABLE  web_builder_quick_poll ADD COLUMN showResultsToPatrons TINYINT(1) DEFAULT 0',
			],
		], // show_quick_poll_results
		//Alexander - PTFS
		'display_list_author_control' => [
			'title' => 'User List Author Control',
			'description' => 'Add a setting to allow users to control whether their name appears on public lists they have created.',
			'continueOnError' => true,
			'sql' => [
				'ALTER TABLE  user_list ADD COLUMN displayListAuthor TINYINT(1) DEFAULT 1',
				'ALTER TABLE user ADD COLUMN displayListAuthor TINYINT(1) DEFAULT 1',
			],
		],
		'store_place_of_publication' => [
            'title' => 'Place of Publication',
            'description' => 'Store information about the place of publication',
            'sql' => [
                "CREATE TABLE  indexed_place_of_publication (
                    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					placeOfPublication VARCHAR(500) collate utf8_bin UNIQUE
				) ENGINE INNODB",   
            ],
        ],
        //indexed_information_places_of_publication
        'add_place_of_publication_to_grouped_work' => [
            'title' => 'Add Place of Publication to Grouped Work',
            'description' => 'Add Place of Publication to Grouped Work',
            'sql' => [
                "ALTER TABLE grouped_work_records ADD COLUMN placeOfPublicationId INT(11) DEFAULT 1",
			],
		], //Add places of publication to grouped work
	];
}