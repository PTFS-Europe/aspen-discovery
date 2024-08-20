<?php
require_once __DIR__ . '/../bootstrap.php';

global $enabledModules;
if (!array_key_exists('Community', $enabledModules))
    die();

require_once ROOT_DIR . '/sys/Community/MilestoneProgressEntry.php';
require_once ROOT_DIR . '/sys/Community/MilestoneUsersProgress.php';

$milestoneProgressEntry = new MilestoneProgressEntry();
$milestoneProgressEntry->processed = 0;

if (!$milestoneProgressEntry->find())
    die();

while ($milestoneProgressEntry->fetch()) {
    $milestoneProgressEntry->processed = 1;
    $milestoneProgressEntry->update();

    $milestoneUsersProgress = new MilestoneUsersProgress();
    $milestoneUsersProgress->ce_milestone_id = $milestoneProgressEntry->ce_milestone_id;
    $milestoneUsersProgress->find();
    while ($milestoneUsersProgress->fetch()) {
        $milestoneUsersProgress->progress = $milestoneUsersProgress->progress + 1;
        $milestoneUsersProgress->update();
    }

}

die();