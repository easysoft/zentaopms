/**
 * Refresh page.
 *
 * @param  string $type
 * @access public
 * @return void
 */
function refreshPage(kanbanID, type)
{
    location.href = createLink('kanban', 'edit', 'kanbanID=' + kanbanID + '&type=' + type);
}

