window.renderCell = function(result, {col, row})
{
    if(col.name === 'lastStatus')
    {
        let className = '';
        if(row.data.lastStatus == 'failure' || row.data.lastStatus == 'create_fail') className = 'status-doing';
        if(row.data.lastStatus == 'success') className = 'status-done';
        result[0] = {html:'<span class="' + className + '">' + result[0] + '</span>'};

        return result;
    }

    return result;
};

window.importJob = function()
{
    if(repoID === undefined || repoID === null || typeof repoID != "number") return;

    var url = $.createLink('job', 'ajaxImportJobs', "repoID=" + repoID);
    $.getJSON(url, function(data)
    {
        if(data.result == 'success')
        {
            if(!sessionStorage.getItem('jobImported'))
            {
                sessionStorage.setItem('jobImported', 'true');
                return loadTable();
            }
        }
    });
}

$(function()
{
    importJob();
});
