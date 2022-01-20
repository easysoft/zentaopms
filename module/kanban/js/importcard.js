/**
 * Reload card list.
 *
 * @param  int $kanbanID
 * @access public
 * @return void
 */
function reloadCardList(targetID)
{
    location.href = createLink('kanban','importcard','kanbanID=' + kanbanID + '&regionID=' + regionID + '&groupID=' + groupID + '&columnID=' + columnID + '&targetKanbanID=' + targetID);
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
