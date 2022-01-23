/**
 * Reload card list.
 *
 * @param  int $kanbanID
 * @access public
 * @return void
 */
function reloadPlanList(targetID)
{
    location.href = createLink('kanban','importPlan','kanbanID=' + kanbanID + '&regionID=' + regionID + '&groupID=' + groupID + '&columnID=' + columnID + '&targetProductID=' + targetID);
}

/**
 * Set target lane ID.
 *
 * @param  int $targetLaneID
 * @access public
 * @return void
 */
function setTargetLane(targetLaneID)
{
    $('#targetLane').val(targetLaneID);
}
