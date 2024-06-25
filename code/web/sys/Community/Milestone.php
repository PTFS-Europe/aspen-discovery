<?php

require_once ROOT_DIR . '/sys/Community/Reward.php';
class Milestone extends DataObject {
    public $__table = 'milestone';
    public $id;
    public $name;
    public $reward;

    public static function getObjectStructure($context = ''): array {
        $rewardsList = Reward::getRewardList();
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
            'reward' => [
                'property' => 'reward',
                'type' => 'enum',
                'label' => 'Reward',
                'maxLength' => 100,
                'description' => 'The reward for achieving this milestone',
                'values' => $rewardsList,
            ],
        ];
    }

    static function getMilestoneList(): array {
        $milestone = new Milestone();
        $milestone->orderBy('name');
        $milestone->find();
        $milestoneList = [];
        while ($milestone->fetch()) {
            $currentMilestone = new stdClass();
            $currentMilestone->id = $milestone->id;
            $currentMilestone->name = $milestone->name;
            $milestoneList[$currentMilestone->id] = $currentMilestone->name;
        }
        return $milestoneList;
    }
}