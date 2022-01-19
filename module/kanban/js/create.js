/**
 * Refresh page.
 *
 * @param  string $type
 * @access public
 * @return void
 */
function refreshPage(spaceID, type)
{
    location.href = createLink('kanban', 'create', 'spaceID=' + spaceID + '&type=' + type);
}

