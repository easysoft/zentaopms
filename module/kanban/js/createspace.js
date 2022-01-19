/**
 * When type change.
 *
 * @param  string type
 * @access public
 * @return void
 */
function changeType(type)
{
    location.href = createLink('kanban', 'createSpace', 'type=' + type);
}

