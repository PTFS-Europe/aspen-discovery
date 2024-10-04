<?php

function getUpdates24_11_00(): array {
    return [
        'add_regular_expression_for_iTypes_to_treat_as_eContent' => [
			'title' => 'Add Regular Expression For iTypes To Treat As eContent',
			'description' => 'Add treatItemsAsEcontent to give control over iTypes to be treated as eContent',
			'sql' => [
				"ALTER TABLE indexing_profiles ADD COLUMN treatItemsAsEcontent VARCHAR(512) DEFAULT 'ebook|ebk|eaudio|evideo|online|oneclick|eaudiobook|download'",
			],
		], //add_treatItemsAsEcontent_field
    ];
}