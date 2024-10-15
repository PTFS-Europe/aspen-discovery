<?php
require_once ROOT_DIR . '/sys/Community/Milestone.php';
require_once ROOT_DIR . '/sys/Community/CampaignMilestone.php';
require_once ROOT_DIR . '/sys/Community/UserCampaign.php';
require_once ROOT_DIR . '/sys/Community/Reward.php';


class Campaign extends DataObject {
    public $__table = 'ce_campaign';
    public $id;
    public $name;
    public $description;
    public $milestones;
    public $startDate;
    public $endDate;
    public $enrollmentCounter;
    public $unenrollmentCounter;
    public $currentEnrollments;
    public $campaignReward;

    /** @var AvailableMilestones[] */
    private $_availableMilestones;

    public static function getObjectStructure($context = ''): array {
        $milestoneList = Milestone::getMilestoneList();
        $milestoneStructure = CampaignMilestone::getObjectStructure($context);
        unset($milestoneStructure['campaignId']);

        $rewardList = Reward::getRewardList();
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
				'maxLength' => 255,
				'description' => 'A description of the campaign',
			],
            'availableMilestones' => [
                'property' => 'availableMilestones',
                'type' => 'oneToMany',
                'label' => 'Milestones',
                'renderAsHeading' => true,
                'description' => 'The Milestones to be linked to this campaign',
                'keyThis' => 'campaignId',
                'keyOther' => 'campaignId',
                'subObjectType' => 'CampaignMilestone',
                'structure' => $milestoneStructure,
                'sortable' => true,
                'storeDb' => true,
                'allowEdit' => false,
                'canEdit' => false,
                'canAddNew' => true,
                'canDelete' => true,
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
            'campaignReward' => [
                'property' => 'campaignReward',
                'type' => 'enum',
                'label' => 'Reward for Completing Campaign',
                'values' => $rewardList, 
                'description' => 'The reward given for completing the campaign.'
            ]
        ];
    }

    public function getUsers() {
        if (is_null($this->_users)) {
            $this->_users = [];

            require_once ROOT_DIR . '/sys/Account/User.php';

            if ($this->id) {
                $escapedId = $this->escape($this->id);
                error_log("Fetching users for campaign Id: " . $escapedId);
                $user = new User();
                $user->query("SELECT user.* FROM user INNER JOIN ce_user_campaign ON  user.id = ce_user_campaign.userId WHERE ce_user_campaign.campaignId = $escapedId ORDER BY user.username");

                while($user->fetch()) {
                    $this->_users[$user->id] = clone $user;
                }
            }
        }
        return $this->_users;
    }

    public function getUsersForCampaign() {
        require_once ROOT_DIR . '/sys/Community/UserCampaign.php';
        $users = [];

        if ($this->id) {
            $userCampaign = new UserCampaign();

            $userCampaign->campaignId = $this->id;

            if ($userCampaign->find()) {
                while ($userCampaign->fetch()) {
                    $user = new User();
                    $user->id = $userCampaign->userId;
                    if ($user->find(true)) {
                        $users[] = clone $user;
                    }
                }
            }
        }
        error_log(print_r($users, true));
        return $users;
    }

    public function __get($name) {
        if ($name == 'availableMilestones') {
            return $this->getMilestones();
        } else {
            return parent::__get($name);
        }
    }

    public function __set($name, $value) {
        if ($name == 'availableMilestones') {
            $this->_availableMilestones = $value;
        } else {
            parent::__set($name, $value);
        }
    }

 
    public function getMilestones(){
        if (!isset($this->_availableMilestones)) {
            $this->_availableMilestones = [];
            if (!empty($this->id)) {
                $campaignMilestone = new CampaignMilestone();
                $campaignMilestone->campaignId = $this->id;
               if ($campaignMilestone->find()) {
                    while ($campaignMilestone->fetch()) {
                        $this->_availableMilestones[$campaignMilestone->id] = clone($campaignMilestone);
                    }
               }
            }
        }
        return $this->_availableMilestones;
    }

    /**
	 * Override the update functionality to save related objects
	 *
	 * @see DB/DB_DataObject::update()
	 */
	public function update($context = '') {
        $ret = parent::update();
        if ($ret !== FALSE) {
            $this->saveMilestones();
        }
        return $ret;
    }

    /**
     * Override the insert functionality to save related objects
     * 
     * @see DB/DB_Data_Object::insert()
     */
    public function insert($context = '') {
        $ret = parent::insert();
        if ($ret !== FALSE) {
            $this->saveMilestones();
        }
        return $ret;
    }

    public static function getAllCampaigns() : array {
        $campaign = new Campaign();
        $campaignList = [];

        if ($campaign->find()) {
            while ($campaign->fetch()) {
                $campaignList[$campaign->id] = clone $campaign;
            }
        }
        return $campaignList;
    }

    /**
     * Retrieves a list of active campaigns.
     *
     * An active campaign is one that has started and not yet ended.
     *
     * @return array An associative array of active campaigns, where the keys
     *               are the campaign IDs and the values are the campaign names.
     */
    public static function getActiveCampaignsList(): array
    {
        $campaign = new Campaign();
        $campaign->whereAdd("startDate <= '" . date("Y-m-d") . "'");
        $campaign->whereAdd("endDate >='" . date("Y-m-d") . "'");
        $campaignList = [];
        if ($campaign->find()) {
            while ($campaign->fetch()) {
                $campaignList[$campaign->id] = clone $campaign;
            }
        }
        return $campaignList;
    }

    public static function getUpcomingCampaigns():array {
        $campaign = new Campaign();

        //Work out the date one month from today
        $today = date("Y-m-d");
        $nextMonth = date("Y-m-d", strtotime("+1 month"));

        $campaign->whereAdd("startDate > '" . $today . "'");
        $campaign->whereAdd("startDate <= '" . $nextMonth . "'");

        $campaignList = [];
        if ($campaign->find()) {
            while ($campaign->fetch()) {
                $campaignList[$campaign->id] = $campaign;
            }
        }
        return $campaignList;
    }

    public static function getCampaignsEndingThisMonth(): array {
        $campaign = new Campaign();

        $startOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t'); //Last day of current month

        $campaign->whereAdd("endDate >= '$startOfMonth'");
        $campaign->whereAdd("endDate <= '$endOfMonth'");

        $campaignList = [];
        if ($campaign->find()) {
            while ($campaign->fetch()) {
                $campaignList[$campaign->id] = clone $campaign;
            }
        }
        return $campaignList;
    }

    public static function getUserEnrolledCampaigns($userId): array {
        $campaign = new Campaign();

        $campaign->joinAdd(new UserCampaign(), 'INNER', 'ce_user_campaign', 'id', 'campaignId');

        //Filter by the userId
        $campaign->whereAdd("ce_user_campaign.userId = " . $userId);

        $campaignList = [];
        if ($campaign->find()) {
            while ($campaign->fetch()) {
                $campaignList[] = clone $campaign;
            }
        }
        return $campaignList;
    }

    public function isUserEnrolled($userId) {
        $userCampaign = new UserCampaign();
        $userCampaign->userId = $userId;
        $userCampaign->campaignId = $this->id;

        return $userCampaign->find(true);
    }

    public function saveMilestones() {
        if (isset($this->_availableMilestones) && is_array($this->_availableMilestones)) {
            $this->saveOneToManyOptions($this->_availableMilestones, 'campaignId');
            unset($this->_availableMilestones);
        }
    }
}