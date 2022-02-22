/**
 * When type change.
 *
 * @param  string type
 * @access public
 * @return void
 */
function changeType(spaceID, type)
{
    location.href = createLink('kanban', 'editSpace', 'spaceID=' + spaceID + '&type=' + type);
}
