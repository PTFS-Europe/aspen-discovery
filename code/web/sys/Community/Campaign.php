<?php

require_once ROOT_DIR . '/sys/Community/Milestone.php';
class Campaign extends DataObject {
    public $__table = 'campaign';
    public $id;
    public $name;
    public $description;
    public $milestoneOne;
    public $milestoneTwo;
    public $startDate;
    public $endDate;

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
            'description' => [
                'property' => 'description',
                'type' => 'text',
                'label' => 'Description',
                'description' => 'A description of the campaign',
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
                'startDate' => [
                    'property' => 'startDate',
                    'type' => 'date',
                    'label' => 'Campaign Start Date',
                    'description' => 'The date the campaign starts',
                ],
                'endDate' => [
                    'property' => 'endDate',
                    'type' => 'date',
                    'label' => 'Campaign End Date',
                    'description' => 'The date the campaign ends',
                ],
         ];
    }
}