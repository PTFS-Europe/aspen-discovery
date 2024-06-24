<?php
class Milestone extends DataObject {
    public $__table = 'campaign';
    public $id;
    public $name;

    public static function getObjectStructure($context = ''): array {
        return [
            'id' => [
                'property' => 'id',
                'type' => 'label',
				'label' => 'Id',
				'description' => 'The unique id',
            ],
            'name' => [
				'property' => 'name',
				'type' => 'text',
				'label' => 'Name',
				'maxLength' => 50,
				'description' => 'A name for the milestone',
				'required' => true,
			],
        ];
    }
}