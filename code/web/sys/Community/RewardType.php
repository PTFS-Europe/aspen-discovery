<?php

class RewardType extends DataObject {
    public $__table = 'reward_type';
    public $id;
    public $name;

    private static $rewardTypes = [
        1 => 'Physical',
        2 => 'Digital',
    ];

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

   public static function getRewardTypeList(): array {
    return self::$rewardTypes;
   }

   public static function getRewardTypeById($id): ?string {
    return self::$rewardTypes[$id] ?? null;
   }
}