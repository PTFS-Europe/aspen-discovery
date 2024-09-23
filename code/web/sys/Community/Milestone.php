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
                'onchange' => 'updateConditionalField(this.value)',
            ],
            'conditionalField' => [
                'property' => 'conditionalField',
                'type' => 'enum',
                'label' => 'Conditional Field: ',
                'values' => [
                    'title' => 'Title',
                    'author' => 'Author',
                    'hold_title' => 'Title',
                    'hold_author' => 'Author',
                    'list_id' => 'List Name',
                    'list_name' => 'List Length',
                    'work_id' => 'Reviewed Title',
                    'work_author' => 'Reviewed Author',
                ],
                'required' => false,
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

    public static function getConditionalFields() {
        $conditionalFields = [
            'user_checkout' => [
                ['value' => 'title', 'label' => 'Title'],
                ['value' => 'author', 'label' => 'Author'],
            ],
            'user_hold' => [
                ['value' => 'hold_title', 'label' => 'Title'],
                ['value' => 'hold_author', 'label' => 'Author'],
            ],
            'user_list' => [
                ['value' => 'list_id', 'label' => 'List Name'],
                ['value' => 'list_name', 'label' => 'List Length'],
            ],
            'user_work_review' => [
                ['value' => 'work_id', 'label' => 'Reviewed Title'],
                ['value' => 'work_author', 'label' => 'Reviewed Author'],
            ],
        ];
        return $conditionalFields;
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

        # First, check if this milestone already has progress for this user
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

        $milestoneUsersProgress->progress++;
        $milestoneUsersProgress->update();
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

$conditionalFields = Milestone::getConditionalFields();
?>
<script>
    var conditionalFields = <?php echo json_encode($conditionalFields); ?>

    function updateConditionalField(milestoneType) {
        // Get the dropdown element for conditional fields
        var conditionalFieldDropdown = document.querySelector('[name="conditionalField"]');

        // Clear existing options in the dropdown
        conditionalFieldDropdown.innerHTML = '';

        // Check if milestoneType has conditional fields
        var options = conditionalFields[milestoneType] || [];

        // If no options are available
        if (options.length === 0) {
            var noOption = document.createElement('option');
            noOption.value = '';
            noOption.text = 'No conditional fields available';
            conditionalFieldDropdown.appendChild(noOption);
            return;
        }

        // Populate new options
        options.forEach(function(option) {
            var newOption = document.createElement('option');
            newOption.value = option.value;
            newOption.text = option.label;
            conditionalFieldDropdown.appendChild(newOption);
        });
    }

    // Trigger dropdown update when the page loads or the milestoneType is changed
    document.addEventListener('DOMContentLoaded', function() {
        var milestoneTypeDropdown = document.querySelector('[name="milestoneType"]');

        if(milestoneTypeDropdown) {
            milestoneTypeDropdown.addEventListener('change', function() {
                updateConditionalField(this.value);
            });
            updateConditionalField(milestoneTypeDropdown.value);

        } else {
            console.error("Milestone type dropdown not found.");
        }
    });
 
</script>