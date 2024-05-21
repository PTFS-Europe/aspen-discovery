<?php

function getUpdates24_06_00(): array {
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

		//other

		//alexander - PTFS Europe
		'library_delete_last_list_used_entries' => [
			'title' => 'Library delete last list used history',
			'description' => 'Add an option to delete lastListUsed',
			'continueOnError' =>true,
			'sql' => [
				'ALTER TABLE library ADD COLUMN deleteLastListUsedEntries TINYINT(1) DEFAULT 0',
			],
		],


	];
}