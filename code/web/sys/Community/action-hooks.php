<?php

require_once ROOT_DIR . '/sys/Community/Milestone.php';
require_once ROOT_DIR . '/sys/Community/MilestoneProgressEntry.php';

/**
 * after_checkout_insert
 *
 * React to a new user_checkout being added to the database.
 * Add a new ce_milestone_progress_entry to be processed later if all conditions are met
 *
 * @param $value Checkout() object
 */

add_action('after_object_insert', 'after_checkout_insert', function ($value) {
    $milestone = Milestone::getMilestonesToUpdate($value, 'user_checkout', $value->userId);
    if (!$milestone)
        return;

    while ($milestone->fetch()) {
        if (_checkoutMilestoneProgressEntryExists($value, $milestone))
            return;

        $milestone->addMilestoneProgressEntry($value, $value->userId);
    }
    return;
});

/**
 * after_hold_insert
 *
 * React to a new user_hold being added to the database.
 * Add a new ce_milestone_progress_entry to be processed later
 *
 * @param $value Hold() object
 */

add_action('after_object_insert', 'after_hold_insert', function ($value) {
    $milestone = Milestone::getMilestonesToUpdate($value, 'user_hold', $value->userId);
    if (!$milestone)
        return;

    while ($milestone->fetch()) {
        if (_checkoutMilestoneProgressEntryExists($value, $milestone))
            return;

        $milestone->addMilestoneProgressEntry($value, $value->userId);
    }
    return;
});

/**
 * after_list_insert
 *
 * React to a new user_list being added to the database.
 * Add a new ce_milestone_progress_entry to be processed later
 *
 * @param $value UserList() object
 */

add_action('after_object_insert', 'after_list_insert', function ($value) {
    $milestone = Milestone::getMilestonesToUpdate($value, 'user_list', $value->user_id);
    if (!$milestone)
        return;

    while ($milestone->fetch()) {
        $milestone->addMilestoneProgressEntry($value, $value->user_id);
    }
    return;
});

/**
 * after_work_review_insert
 *
 * React to a new user_work_review being added to the database.
 * Add a new ce_milestone_progress_entry to be processed later
 *
 * @param $value UserWorkReview() object
 */

add_action('after_object_insert', 'after_work_review_insert', function ($value) {
    $milestone = Milestone::getMilestonesToUpdate($value, 'user_work_review', $value->userId);
    if (!$milestone)
        return;

    while ($milestone->fetch()) {
        $milestone->addMilestoneProgressEntry($value, $value->userId);
    }
    return;
});

/**
 * Checks if a checkout entry already exists in the ce_milestone_progress_entries table, for a specific milestone.
 * This check is required because a new checkout being added to the database may not actually be a new checkout.
 *
 * @param object $value The Checkout object containing the sourceId, recordId, and userId.
 * @param Milestone $milestone The milestone object.
 * @return bool Returns true if an entry exists, false otherwise.
 */
function _checkoutMilestoneProgressEntryExists($value, $milestone)
{
    $milestoneProgressEntryCheck = new MilestoneProgressEntry();
    $milestoneProgressEntryCheck->initialize($milestone);
    if ($milestoneProgressEntryCheck->find()) {
        while ($milestoneProgressEntryCheck->fetch()) {
            $decoded_object = json_decode($milestoneProgressEntryCheck->object);
            if (
                $decoded_object->sourceId == $value->sourceId &&
                $decoded_object->recordId == $value->recordId &&
                $decoded_object->userId == $value->userId
            ) {
                return true;
            }
        }
    }
    return false;
}



