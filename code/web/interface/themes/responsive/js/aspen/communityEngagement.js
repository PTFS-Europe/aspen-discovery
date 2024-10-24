AspenDiscovery.CommunityEngagement = function() {
    return {
        campaignRewardGiven: function(userId, campaignId) {
            var url = Globals.path + "/Community/AJAX?method=campaignRewardGivenUpdate";
            var params = {
                userId: userId, 
                campaignId: campaignId,
            };
            $.getJSON(url, params, 
                function(data) {
                    if (data.success) {
                        var button = $('.set-reward-btn[data-user-id="' + userId + '"][data-campaign-id="' + campaignId + '"]');
                        button.replaceWith('<span>Reward Given</span>');
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown){
               
                alert('An error occurred while updating the reward status.' + textStatus + ', ' + errorThrown);
                });
        },
        milestoneRewardGiven: function(userId, campaignId, milestoneId) {
            var url = Globals.path + "/Community/AJAX?method=milestoneRewardGivenUpdate";
            var params = {
                userId: userId,
                campaignId: campaignId,
                milestoneId: milestoneId,
            };
            $.getJSON(url, params,
                function(data) {
                    if (data.success) {
                        var button = $('.set-reward-btn-milestone[data-user-id="' + userId + '"][data-campaign-id="' + campaignId + '"][data-milestone-id="' + milestoneId + '"]');
                        button.replaceWith('<span>Milestone Reward Given</span>');
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error: " + textStatus + ", " + errorThrown);
                    console.error("Response Text: " + jqXHR.responseText);
                    alert('An error occurred while updating the reward status for this milestone.' + textStatus + ', ' + errorThrown);
                });
        },
    }
}(AspenDiscovery.CommunityEngagement || {});