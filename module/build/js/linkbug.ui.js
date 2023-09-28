window.renderBugCell = function(result, info)
{
    if(info.col.name == 'resolvedByControl' &&(info.row.data.status == 'resolved' || info.row.data.status == 'closed'))
    {
        const resolvedBy     = info.row.data.resolvedBy;
        const resolvedByName = users[resolvedBy] ? users[resolvedBy] : '';
        result[0] = {html: '<span class="resolvedBy">' + resolvedByName + '</span>'};
    }
    return result;
}
