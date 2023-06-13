window.renderCell = function(result, info)
{
    const testcase = info.row.data;
    if(info.col.name == 'lastRunDate' && result)
    {
        if(testcase.lastRunDate == '0000-00-00 00:00:00') return [''];
        return [testcase.lastRunDate.substr(5, 11)];
    }
    return result;
};
