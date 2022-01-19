/**
 * Refresh page.
 *
 * @param  string $type
 * @access public
 * @return void
 */
function refreshPage(type)
{
    location.href = createLink('kanban', 'createSpace', 'type=' + type);
}

