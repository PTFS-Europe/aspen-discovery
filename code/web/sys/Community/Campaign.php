<?php

require_once ROOT_DIR . '/sys/Community/Milestone.php';
class Campaign extends DataObject {
    public $__table = 'campaign';
    public $id;
    public $name;
    public $milestoneOne;
    public $milestoneTwo;

    public static function getObjectStructure($context = ''): array {
        $milestoneList = Milestone::getMilestoneList();
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
				'description' => 'A name for the campaign',
				'required' => true,
			],
            'milestones' => [
                'property' => 'milestones',
                'type' => 'section',
                'label' => 'milestones',
                'hideInLists' => true,
                'properties' => [
                    'milestoneOne' => [
                        'property' => 'milestoneOne',
                        'type' => 'enum',
                        'label' => 'Milestone One',
                        'description' > 'A milestone to meet for this campaign',
                        'values' => $milestoneList,
                    ],
                    'milestoneTwo' => [
                        'property' => 'milestoneTwo',
                        'type' => 'enum',
                        'label' => 'Milestone Two',
                        'description' > 'A milestone to meet for this campaign',
                        'values' => $milestoneList,
                    ],
                ],
            ],
        ];
    }
}