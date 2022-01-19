/**
 * When type change.
 *
 * @oaram  int    spaceID
 * @param  string type
 * @access public
 * @return void
 */
function changeType(spaceID, type)
{
    location.href = createLink('kanban', 'create', 'spaceID=' + spaceID + '&type=' + type);
}

