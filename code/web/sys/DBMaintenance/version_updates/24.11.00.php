<?php

function getUpdates24_11_00(): array {
    $curTime = time();
    return [
        //alexander - PTFS Europe
		'optional_show_title_on_grapes_pages' => [
			'title' => 'Optional Show Title On Grapes Pages',
			'description' => 'Make displaying a given title on a grapes page optional',
			'continueOnError' => false,
			'sql' => [
				'ALTER TABLE grapes_web_builder ADD COLUMN showTitleOnPage TINYINT NOT NULL DEFAULT 1'
			],
		],//optional_show_title_on_grapes_pages
    ];
}