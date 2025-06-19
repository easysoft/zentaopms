window.renderCell = function(result, {col, row})
{
    if(col.name === 'lastStatus')
    {
        let className = '';
        if(row.data.lastStatus == 'failure' || row.data.lastStatus == 'create_fail') className = 'status-doing';
        if(row.data.lastStatus == 'success') className = 'status-done';
        result[0] = {html:'<span class="' + className + '">' + result[0] + '</span>'};
    }

    if(col.name === 'name')
    {
        if(typeof(row.data.branch) == 'undefined') return result;
        result[1] = {html: '<span class="label success-pale mr-1">' + row.data.branch + '</span>'};
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
