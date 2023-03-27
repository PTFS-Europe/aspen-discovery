<?php
/** @noinspection PhpUnused */
function getUpdates23_04_00(): array {
	$curTime = time();
	return [
		/*'name' => [
			'title' => '',
			'description' => '',
			'continueOnError' => false,
			'sql' => [
				''
			]
		], //sample*/

		//mark
		'allow_multiple_themes_for_libraries' => [
			'title' => 'Allow Multiple Themes for Libraries',
			'description' => 'Allow Multiple Themes for Libraries',
			'sql' => [
				"CREATE TABLE IF NOT EXISTS library_themes (
					id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
					libraryId INT(11) NOT NULL,
					themeId  INT(11) NOT NULL,
					weight INT(11) DEFAULT 0,
					INDEX libraryToTheme(libraryId, themeId)
				) ENGINE INNODB",
				'INSERT INTO library_themes (libraryId, themeId) (SELECT libraryId, theme from library)'
			],
		],
		'allow_multiple_themes_for_locations' => [
			'title' => 'Allow Multiple Themes for Locations',
			'description' => 'Allow Multiple Themes for Locations',
			'sql' => [
				"CREATE TABLE IF NOT EXISTS location_themes (
					id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
					locationId INT(11) NOT NULL,
					themeId  INT(11) NOT NULL,
					weight INT(11) DEFAULT 0,
					INDEX libraryToTheme(locationId, themeId)
				) ENGINE INNODB",
				'INSERT INTO location_themes (locationId, themeId) (SELECT locationId, theme from location)'
			],
		],
		'add_display_name_to_themes' => [
			'title' => 'Add Display name to themes',
			'description' => 'Add Display name to themes',
			'sql' => [
				"ALTER TABLE themes add column displayName VARCHAR(60) NOT NULL",
				"UPDATE themes set displayName = themeName",
			],
		],
		'allow_users_to_change_themes' => [
			'title' => 'Allow Users to change themes',
			'description' => 'Allow Users to change themes',
			'sql' => [
				"ALTER TABLE user add column preferredTheme int(11) default -1",
			],
		],
		'shared_content_in_greenhouse' => [
			'title' => 'Add shared content to the greenhouse',
			'description' => 'Allow libraries to share content within the community',
			'sql' => [
				"CREATE TABLE IF NOT EXISTS shared_content (
					id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
					type VARCHAR(50) NOT NULL,
					name VARCHAR(100) NOT NULL,
					description TEXT,
					sharedFrom VARCHAR(50) NOT NULL,
					sharedByUserName VARCHAR(256) NOT NULL,
					shareDate int(11), 
					approved TINYINT(1) DEFAULT 0,
					approvalDate int(11),
					approvedBy int(11),
					data TEXT                                          
				) ENGINE INNODB",
			],
		],

		//kirstien
		'add_ecommerce_deluxe' => [
			'title' => 'Add eCommerce vendor Certified Payments by Deluxe',
			'description' => 'Create Certified Payments by Deluxe settings table',
			'continueOnError' => true,
			'sql' => [
				'CREATE TABLE IF NOT EXISTS deluxe_certified_payments_settings (
					id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
					name VARCHAR(50) NOT NULL UNIQUE,
					sandboxMode TINYINT(1) DEFAULT 0,
					applicationId VARCHAR(500) NOT NULL,
					securityId VARCHAR(500) NOT NULL
				) ENGINE INNODB',
				'ALTER TABLE library ADD COLUMN deluxeCertifiedPaymentsSettingId INT(11) DEFAULT -1',
			],
		],
		//add_ecommerce_deluxe
		'permissions_ecommerce_deluxe' => [
			'title' => 'Add permissions for Certified Payments by Deluxe',
			'description' => 'Create permissions for administration of Certified Payments by Deluxe',
			'continueOnError' => true,
			'sql' => [
				"INSERT INTO permissions (sectionName, name, requiredModule, weight, description) VALUES ('eCommerce', 'Administer Certified Payments by Deluxe', '', 10, 'Controls if the user can change Certified Payments by Deluxe settings. <em>This has potential security and cost implications.</em>')",
				"INSERT INTO role_permissions(roleId, permissionId) VALUES ((SELECT roleId from roles where name='opacAdmin'), (SELECT id from permissions where name='Administer Certified Payments by Deluxe'))",
			],
		],
		// permissions_ecommerce_deluxe

		//kodi
		'permissions_create_events_communico' => [
			'title' => 'Alters permissions for Events',
			'description' => 'Create permissions for Communico',
			'sql' => [
				"INSERT INTO permissions (sectionName, name, requiredModule, weight, description) VALUES ('Events', 'Administer Communico Settings', 'Events', 20, 'Allows the user to administer integration with Communico for all libraries.')",
				"INSERT INTO role_permissions(roleId, permissionId) VALUES ((SELECT roleId from roles where name='opacAdmin'), (SELECT id from permissions where name='Administer Communico Settings'))",
			],
		],
		// permissions_create_events_communico
		'communico_settings' => [
			'title' => 'Define events settings for Communico integration',
			'description' => 'Initial setup of the Communico integration',
			'sql' => [
				'CREATE TABLE IF NOT EXISTS communico_settings (
					id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
					name VARCHAR(100) NOT NULL UNIQUE,
					baseUrl VARCHAR(255) NOT NULL,
					clientId VARCHAR(36) NOT NULL,
					clientSecret VARCHAR(36) NOT NULL
				) ENGINE INNODB',
			],
		],

		// communico_settings
		'communico_events' => [
			'title' => 'Communico Events Data',
			'description' => 'Setup tables to store events data for Communico',
			'sql' => [
				'CREATE TABLE IF NOT EXISTS communico_events (
					id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
					settingsId INT NOT NULL,
					externalId varchar(36) NOT NULL,
					title varchar(255) NOT NULL,
					rawChecksum BIGINT,
					rawResponse MEDIUMTEXT,
					deleted TINYINT default 0,
					UNIQUE (settingsId, externalId)
				)',
			],
		],
		// communico_events
		'user_list_entry_length' => [
			'title' => 'User List Entry sourceId Length',
			'description' => 'Increase allowed length for sourceId in user list entries.',
			'sql' => [
				"ALTER TABLE user_list_entry CHANGE COLUMN sourceId sourceId VARCHAR(50) NOT NULL",
			],
		],
		// user_list_entry_length

		//other
	];
}