<?php

class UserCampaign extends DataObject {
    public $__table = 'ce_user_campaign';
    public $id;
    public $userId;
    public $campaignId;
    public $enrollmentDate;
    public $unenerollmentDate;
    public $completed;
    public $rewardGiven;

    public static function getObjectStructure($context = ''): array {
        return [
            'id' => [
                'property' => 'id',
                'type' => 'label',
				'label' => 'Id',
				'description' => 'The unique id',
            ],
            'userId' => [
                'property' => 'userId',
                'type' => 'label',
				'label' => 'User Id',
				'description' => 'The unique id of the user',
            ],
            'campaignId' => [
                'property' => 'campaignId',
                'type' => 'label',
				'label' => 'Campaign Id',
				'description' => 'The unique id of the campaign',
            ],
            'enrollmentDate' => [
                'property' => 'enrollmentDate',
                'type' => 'date',
				'label' => 'Enrollment Date',
				'description' => 'The Date of Enrollment',
            ],
            'unenrollmentDate' => [
                'property' => 'unenrollmentDate',
                'type' => 'date',
				'label' => 'Unenrollment Date',
				'description' => 'The Date of Unenrollment',
            ],
            'completed' => [
                'property' => 'completed',
                'type' => 'checkbox',
				'label' => 'Campaign Complete',
				'description' => 'Whether or not the campaign is complete',
                'default' => false,
            ],
            'rewardGiven' => [
                'property' => 'rewardGiven',
                'type' => 'checkbox',
                'label' => 'Reward Given',
                'description' => 'Whether or not the reward for completing the campaign has been given',
                'default' => false,
            ],
        ];
    }

    public function checkCompletionStatus() {
        require_once ROOT_DIR . '/sys/Community/Milestone.php';
        require_once ROOT_DIR . '/sys/Community/CampaignMilestone.php';
        require_once ROOT_DIR . '/sys/Community/UserCompletedMilestone.php';
        
        $campaignMilestone = new CampaignMilestone();
        $campaignMilestone->campaignId = $this->campaignId;

        $totalMilestones = 0;
        $completedMilestonesCount = 0;

        if ($campaignMilestone->find()) {
            while ($campaignMilestone->fetch()) {
                $totalMilestones++;

                $completedMilestone = new UserCompletedMilestone();
                $completedMilestone->userId = $this->userId;
                $completedMilestone->milestoneId = $campaignMilestone->milestoneId;
                $completedMilestone->campaignId = $this->campaignId;

                if ($completedMilestone->find(true)) {
                    $completedMilestonesCount++;
                }
            }
        }
        $this->completed = ($totalMilestones > 0 && $completedMilestonesCount === $totalMilestones) ? 1 : 0;
        $this->update();
    }

}