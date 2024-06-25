<?php

class RewardType extends DataObject {
    public $__table = 'reward_type';
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
                'label' => 'Type of Reward',
                'description' => 'The type of reward',
            ]
        ];
    }

    static function getRewardTypeList(): array {
        $rewardType = new RewardType();
        $rewardType->orderBy('name');
        $rewardType->find();
        $rewardTypeList = [];
        while ($rewardType->fetch()) {
            $currentRewardType = new stdClass();
            $currentRewardType->id = $rewardType->id;
            $currentRewardType->naem = $rewardType->name;
            $rewardTypeList[$currentRewardType->id] = $rewardType->name;
        }
        return $rewardTypeList;
    }
}