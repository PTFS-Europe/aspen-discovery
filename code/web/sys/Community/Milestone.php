<?php
require_once ROOT_DIR . '/sys/Community/Campaign.php';
require_once ROOT_DIR . '/sys/Community/CampaignMilestone.php';
require_once ROOT_DIR . '/sys/Community/MilestoneUsersProgress.php';
require_once ROOT_DIR . '/sys/Community/Reward.php';
require_once ROOT_DIR . '/sys/Community/UserCampaign.php';
class Milestone extends DataObject {
    public $__table = 'ce_milestone';
    public $id;
    public $name;
    public $milestoneType;
    public $conditionalField;
    public $conditionalValue;
    public $campaignId;
    public $conditionalOperator;

  

    public static function getObjectStructure($context = ''): array {
     
        $structure = [
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
            'milestoneType' => [
                'property' => 'milestoneType',
                'type' => 'enum',
                'label' => 'When: ',
                'values' => [
                    'user_checkout' => 'Checkout',
                    'user_hold' => 'Hold',
                    'user_list' => 'List',
                    'user_work_review' => 'Rating',
                ],
            ],
            'conditionalField' => [
                'property' => 'conditionalField',
                'type' => 'enum',
                'label' => 'Conditional Field: ',
                'values' => [
                    'title_display' => 'Title',
                    'author_display' => 'Author',
                    'subject_facet' => 'Subject',
                ],
                // var_dump($groupedWorkDriver->getSolrField('format_category_main')); #Books, eBooks, Audiobooks, Music, Video
                // var_dump($groupedWorkDriver->getSolrField('publisherStr'));
                // var_dump($groupedWorkDriver->getSolrField('title_display'));
                // var_dump($groupedWorkDriver->getSolrField('topic_facet'));
                // var_dump($groupedWorkDriver->getSolrField('placeOfPublication'));
                // var_dump($groupedWorkDriver->getSolrField('publishDate'));
                // var_dump($groupedWorkDriver->getSolrField('owning_library_main'));
                // var_dump($groupedWorkDriver->getSolrField('lc_subject'));
                // var_dump($groupedWorkDriver->getSolrField('subject_facet'));
                // var_dump($groupedWorkDriver->getSolrField('itype_main'));
                // var_dump($groupedWorkDriver->getSolrField('format_main'));
                // var_dump($groupedWorkDriver->getSolrField('language'));
                // var_dump($groupedWorkDriver->getSolrField('auth_author2')); #contributors
                // var_dump($groupedWorkDriver->getSolrField('author_display')); #main author
            ],
            'conditionalOperator' => [
                'property' => 'conditionalOperator',
                'type' => 'enum',
                'label' => 'Conditional Operator',
                'values' => [
                    'equals' => 'Is',
                    'is_not' => 'Is Not',
                    'like' => 'Is Like',
                ],
            ],
            'conditionalValue' => [
                'property' => 'conditionalValue',
                'type' => 'text',
                'label' => 'Conditional Value: ',
                'maxLength' => 100,
                'description' => 'Optional value e.g. Fantasy',
                'required' => false,
            ],
        ];
        return $structure;
    } 

    /**
     * Gets a list of milestones for a given object and table name that are related to
     * a patron enrolled in an active campaign and of type $tableName
     *
     * @param object $object The object to check
     * @param string $tableName The table name to check for
     * @param int $userId The user id of the patron
     * @return Milestone|false Returns a Milestone object if one is found, false otherwise
     */
    public static function getMilestonesToUpdate($object, $tableName, $userId)
    {

        # Bail if not the table we want
        if ($object->__table != $tableName)
            return false;

        # Bail if no active campaigns exist
        $activeCampaigns = Campaign::getActiveCampaignsList();
        if (!count($activeCampaigns))
            return false;

        # Bail if this object does not relate to a patron enrolled in an active campaign
        $userCampaigns = new UserCampaign();
        $userCampaigns->whereAdd("campaignId IN (" . implode(",", array_keys($activeCampaigns)) . ")");
        $userCampaigns->userId = $userId;
        if (!$userCampaigns->find())
            return false;

        # Bail if no user active campaigns' milestones are of type $tableName
        $userActiveCampaigns = [];
        while ($userCampaigns->fetch()) {
            array_push($userActiveCampaigns, $userCampaigns->campaignId);
        }
        $milestone = new Milestone();
        $milestone->milestoneType = $tableName;
        $milestone->joinAdd(new CampaignMilestone(), 'LEFT', 'campaignMilestones', 'id', 'milestoneId');
        $milestone->whereAdd('campaignMilestones.campaignId IN (' . implode(',', $userActiveCampaigns) . ')');

        if (!$milestone->find())
            return false;

        return $milestone;
    }

    /**
     * Adds a new MilestoneProgressEntry for a given milestone, object, and user.
     * 
     * @param Milestone $milestone The milestone associated with this progress entry.
     * @param mixed $object The object associated with this progress entry.
     * @param int $userId The user id associated with this progress entry.
     */
    public function addMilestoneProgressEntry( $object, $userId)
    {

        if (!$this->conditionalsCheck($object))
            return;

        # Check if this milestone already has progress for this user
        $milestoneUsersProgress = new MilestoneUsersProgress();
        $milestoneUsersProgress->ce_milestone_id = $this->id;
        $milestoneUsersProgress->userId = $userId;

        # If there isn't one, create it.
        if (!$milestoneUsersProgress->find(true)) {
            $milestoneUsersProgress->progress = 0;
            $milestoneUsersProgress->insert();
        }

        $milestoneProgressEntry = new MilestoneProgressEntry();
        $milestoneProgressEntry->initialize(
            $this,
            [
                "object" => $object,
                "userId" => $userId,
                "milestoneUsersProgress" => $milestoneUsersProgress
            ]
        );
    }

    /**
     * Checks if a given object meets the conditionals of this milestone.
     *
     * If the object does not have a groupedWorkId, it is assumed to meet the conditionals.
     * If the milestone does not have a conditional operator, field, or value, it is assumed
     * to meet the conditionals.
     *
     * Otherwise, this method uses the groupedWorkDriver to get the value of the specified
     * field from the grouped work.  It then checks if the value matches the conditional
     * operator and value.  If it does, it returns true.  If not, it returns false.
     *
     * @param mixed $object The object to check against the conditionals.
     * @return bool True if the object meets the conditionals, false otherwise.
     */
    protected function conditionalsCheck($object)
    {

        if (!$this->conditionalOperator || !$this->conditionalValue || !$this->conditionalField)
            return true;

        if (!$object->groupedWorkId)
            return false;

        require_once ROOT_DIR . '/RecordDrivers/GroupedWorkDriver.php';
        $groupedWorkDriver = new GroupedWorkDriver($object->groupedWorkId);

        if (!$fieldValues = $groupedWorkDriver->getSolrField($this->conditionalField))
            return false;

        if(!is_array($fieldValues)){
            $fieldValues = [$fieldValues];
        }
    
        if ($this->conditionalOperator == 'like') {
            #Convert this foreach to array_map
            foreach ($fieldValues as $fieldValue) {
                if (str_contains(strtolower($fieldValue), strtolower($this->conditionalValue))) {
                    return true;
                }
            }
            return false;
        } elseif ($this->conditionalOperator == 'equals') {
            foreach ($fieldValues as $fieldValue) {
                if (strtolower($fieldValue) == strtolower($this->conditionalValue)) {
                    return true;
                }
            }
            return false;
        } elseif ($this->conditionalOperator == 'is_not') {
            foreach ($fieldValues as $fieldValue) {
                if (strtolower($fieldValue) != strtolower($this->conditionalValue)) {
                    return true;
                }
            }
            return false;
        }

        return false;
    }

    /**
  * @return array
  */
  public static function getMilestoneList(): array {
    $milestone = new Milestone();
    $milestoneList = [];
     
    if ($milestone->find()) {
        while ($milestone->fetch()) {
            $milestoneList[$milestone->id] = $milestone->name;
        }
    }
    return $milestoneList;
  }
}