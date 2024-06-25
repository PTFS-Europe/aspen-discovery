<?php

class Reward extends DataObject {
    public $__table = 'reward';
    public $id;
    public $name;
    public $rewardType;

    public static function getObjectStructure($context = ''): array {
        return [
            'id' => [
                'property' => 'id',
                'type' => 'label',
                'label' => 'id',
                'description' => 'The unique id',
            ],
            'name' => [
                'property' => 'rewardName',
                'type' => 'text',
                'label' => 'Reward',
                'maxLength' => 100,
                'description' => 'An option for a reward for hitting a milestone',
            ],
            'rewardType' => [
                'property' => 'rewardType',
                'type' => 'enum',
                'label' => 'rewardType',
                'description' => 'The type of reward',
            ],
        ];
    }

    static function getRewardList(): array {
        $reward = new Reward();
        $reward->orderBy('name');
        $reward->find();
        $rewardsList = [];
        while ($reward->fetch()) {
            $currentReward = new stdClass();
            $currentReward->id = $reward->id;
            $currentReward->name = $reward->name;
            $rewardsList[$currentReward->id] = $currentReward->name;
        }
        return $rewardsList;
    }
}