<?php

require_once ROOT_DIR . '/sys/Community/Milestone.php';
class Campaign extends DataObject {
    public $__table = 'campaign';
    public $id;
    public $name;
    public $description;
    public $milestones;
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
                'type' => 'oneToMany',
                'label' => 'Milestones',
                'description' => 'List of milestones for this campaign',
                'sortable' => false,
                'storeDb' => true,
                'canAddNew' => true,
                'canDelete' => true,
                'hideInLists' => true,
                'structure' => [
                    'milestoneId' => [
                        'property' => 'milestoneId',
                        'type' => 'enum',
                        'label' => 'Milestone',
                        'description' => 'Select a milestone',
                        'values' => $milestoneList,
                        'required' => true,
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