window.renderDrillResult = function(result, {col, row})
{
    if(col.name == 'name' && row.data.type == 'program') result[0].props.href = $.createLink('program', 'kanban');
    if((col.name == 'name' || col.name == 'title') && typeof(row.data.isModal) != 'undefined' && row.data.isModal)
    {
        result[0].props['data-toggle'] = 'modal';
        result[0].props['data-size']   = 'lg';

        delete result[0].props['target'];
    }

    return result;
}
