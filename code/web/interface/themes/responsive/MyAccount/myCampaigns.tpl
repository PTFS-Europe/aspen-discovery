{strip}
    <h1>{translate text="Your Campaigns" isPublicFacing=true}</h1>

    {if empty($campaignList)}
        <div class="alert alert-info">
            {translate text="There are no available campaign at the moment" isPublicFacing=true}
        </div>
    {else}
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Campaign Name:</th>
                    <th>Enrollment</th>
                    <th>Progress</th>
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
                            <div class="progess" style="width:100%; border:1px solid black; border-radius:4px;height:20px;">
                                <div class="progress-bar" role="progressbar" aria-valuenow="{$campaing->progress}" aria-valuemin="0"
                                    aria-valuemax="100" style="width: {$campaign->progress}%;">
                                    {$campaign->progress}%
                                </div>
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
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {/if}
{/strip}