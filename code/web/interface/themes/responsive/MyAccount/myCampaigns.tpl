{strip}
    <h1>{translate text="Campaigns" isPublicFacing=true}</h1>

    {if empty($campaignList)}
        <div class="alert alert-info">
            {translate text="There are no available campaigns at the moment" isPublicFacing=true}
        </div>
    {else}
        <h2>Your Campaigns</h2>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <h2>All Campaigns</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Campaign Name:</th>
                    <th>Enrollment</th>
                    <th>Milestones Completed</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$campaignList item="campaign" key="resultIndex"}
                    <tr>
                        <td>{$campaign->name}</td>
                        <td>
                            {if $campaign->enrolled}
                                {translate text="Enrolled" isPublicFacing=true}
                            {else}
                                {translate text="Unenrolled" isPublicFacing=true}
                            {/if}
                        </td>
                        <td>
                        {if $campaign->enrolled}
                            {* <div class="progess" style="width:100%; border:1px solid black; border-radius:4px;height:20px;">
                                <div class="progress-bar" role="progressbar" aria-valuenow="{$campaing->progress}" aria-valuemin="0"
                                    aria-valuemax="100" style="width: {$campaign->progress}%;">
                                    {$campaign->progress}%
                                </div>
                            </div> *}
                            <div>
                                {$campaign->numCompletedMilestones} / {$campaign->numCampaignMilestones}
                            </div>
                        {/if}
                        </td>
                        <td>
                        {if $campaign->enrolled}
                            <button onclick="AspenDiscovery.Account.unenroll({$campaign->id}, {$userId});">{translate text="Unenroll" isPublicFacing=true}</button>
                        {else}
                            <button onclick="AspenDiscovery.Account.enroll({$campaign->id}, {$userId});">{translate text="Enroll" isPublicFacing=true}</button>
                        {/if}
                        </td>
                        <td>
                            <button onclick="toggleCampaignInfo({$resultIndex});">{translate text="Campaign Information" isPublicFacing=true}</button>
                        </td>
                    </tr>
                    <tr id="campaignInfo_{$resultIndex}" style="display:none;">
                            <td colspan="4">
                                {* <h4>{translate text="Milestones"}</h4> *}
                                <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{translate text="Start Date" isPublicFacing=true}</th>
                                        <th>{translate text="End Date" isPublicFacing=true}</th>
                                        <th>{translate text="Milestone" isPublicFacing=true}</th>
                                        <th>{translate text="Progress Towards Milestone" isPublicFacing=true}</th>
                                        <th>{translate text="Progess Percentage" isPublicFacing=true}</th>
                                    </tr>
                                </thead>
                                    <tbody>
                                    {foreach from=$campaign->milestones item="milestone"}
                                        <tr>
                                            <td>{$campaign->startDate}</td>
                                            <td>{$campaign->endDate}</td>
                                            <td>{$milestone->name}</td>
                                            <td>
                                                {$campaign->milestoneCompletedGoals[$milestone->id]} / {$campaign->milestoneGoalCount[$milestone->id]}
                                                <div>
                                                    <button class="btn btn-primary" onclick="seeMilestoneProgress()">
                                                        {translate text="More Information"}
                                                    </button>
                                                </div>
                                                <div id="milestoneProgress" style="display:none;">
                                                    <div>
                                                        {$campaign->milestoneCompletedGoals[$milestone->id]} 
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="progress" style="width:100%; border:1px solid black; border-radius:4px;height:20px;">
                                                    <div class="progress-bar" role="progressbar" aria-valuenow="{$campaign->milestoneProgress[$milestone->id]}" aria-valuemin="0"
                                                     aria-valuemax="100" style="width: {$campaign->milestoneProgress[$milestone->id]}%; line-height: 20px; text-align: center; color: #fff;">
                                                        {$campaign->milestoneProgress[$milestone->id]}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>                                    
                                    {/foreach}
                                    </tbody>
                                </table>
                            </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {/if}
    {if !empty($activeCampaigns)}
        <h2>Active Campaigns</h2>
        {foreach from=$activeCampaigns item="activeCampaign" key="resultIndex"}
            <div>
                <p>{$activeCampaign}</p>
            </div>
        {/foreach}
    {/if}
    {if !empty($upcomingCampaigns)}
        <h2>Upcoming Campaigns</h2>
          <table>
            <thead>
                <tr>
                    <th>{translate text="Campaign" isPublicFacing=true}</th>
                    <th>{translate text="Start Date" isPublicFacing=true}</th>
                </tr>
            </thead>
            <tbody>
            {foreach from=$upcomingCampaigns item="upcomingCampaign" key="resultIndex"}
                <tr>
                    <td>{$campaign->name}</td>
                    <td>{$campaign->startDate}</td>
                </tr>
            {/foreach}
            </tbody>
          </table>
    {/if}
{/strip}
{literal}
    <script tupe="text/javascript">
        function toggleCampaignInfo(index) {
            var campaignInfoDiv = document.getElementById('campaignInfo_' + index);
            if (campaignInfoDiv.style.display === 'none') {
                campaignInfoDiv.style.display = 'block';
            } else {
                campaignInfoDiv.style.display = 'none';
            }
        }
        function seeMilestoneProgress() {
            var seeMilestoneProgressDiv = document.getElementById('milestoneProgress');
            if (seeMilestoneProgressDiv.style.display === 'none') {
                seeMilestoneProgressDiv.style.display = 'block';
            } else {
                seeMilestoneProgressDiv.style.display = 'none';
            }
        }
    </script>
{/literal}