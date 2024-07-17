<?php

function getUpdates24_08_00(): array {
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

		//kirstien - ByWater

		//kodi - ByWater

		//katherine - ByWater

		//alexander - PTFS-Europe
		'update_cookie_management_preferences' => [
			'title' => 'Update Cookie Management Preferences',
			'description' => 'Update cookie management preferences for user tracking',
			'continueOnError' => false,
			'sql' => [
				"ALTER TABLE user ADD COLUMN userCookiePreferenceAxis360 TINYINT(1) DEFAULT 0",
			],
		], //update_user_tracking_cookies
		'update_cookie_management_preferences_more_options' => [
			'title' => 'Update Cookie Management Preferences: More Options',
			'description' => 'Update cookie management preferences for user tracking - adding more options',
			'continueOnError' => false,
			'sql' => [
				"ALTER TABLE user ADD COLUMN userCookiePreferenceEbscoEds TINYINT(1) DEFAULT 0",
				"ALTER TABLE user ADD COLUMN userCookiePreferenceEbscoHost TINYINT(1) DEFAULT 0",
				"ALTER TABLE user ADD COLUMN userCookiePreferenceSummon TINYINT(1) DEFAULT 0",
				"ALTER TABLE user ADD COLUMN userCookiePreferenceEvents TINYINT(1) DEFAULT 0",
				"ALTER TABLE user ADD COLUMN userCookiePreferenceHoopla TINYINT(1) DEFAULT 0",
				"ALTER TABLE user ADD COLUMN userCookiePreferenceOpenArchives TINYINT(1) DEFAULT 0",
				"ALTER TABLE user ADD COLUMN userCookiePreferenceOverdrive TINYINT(1) DEFAULT 0",
				"ALTER TABLE user ADD COLUMN userCookiePreferencePalaceProject TINYINT(1) DEFAULT 0",
				"ALTER TABLE user ADD COLUMN userCookiePreferenceSideLoad TINYINT(1) DEFAULT 0",
			],
		], //update_user_tracking_cookies
		'add_cookie_management_preferences' => [
			'title' => 'Cookie Management for Cloud Libray and Website',
			'description' => 'Add Cookie Management preferences for website and cloud library',
			'continueOnError' => false,
			'sql' => [
				"ALTER TABLE user ADD COLUMN userCookiePreferenceCloudLibrary TINYINT(1) DEFAULT 0",
				"ALTER TABLE user ADD COLUMN userCookiePreferenceWebsite TINYINT(1) DEFAULT 0",
			],
		], //add_user_tacking_cookie_preferences

		//pedro - PTFS-Europe

		//other

	];
}