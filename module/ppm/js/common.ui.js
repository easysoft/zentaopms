window.renderObjectCell = function(result, {col, row})
{
    if(col.name == 'id')
    {
        const type        = row.data.type;
        const hasViewPriv = row.data.hasViewPriv;
        if(!hasViewPriv) result[0] = row.data.id;
    }
    return result;
}
