window.createSortLink = function(col)
{
    var sort = col.name + '_asc';
    if(sort == orderBy) sort = col.name + '_desc';

    return sortLink.replace('{orderBy}', sort);
}

window.renderCell = function(result, {col, row})
{
    if(col.name === 'osVersion')
    {
        const{osName, osVersion} = row.data;
        if(osName && osVersion != '') result[0] = {html: hostLang[osName + 'List'][osVersion]};

        return result;
    }

    if(col.name === 'status')
    {
        let status = '';
        if(row.data.testType === '')
        {
            status = hostLang.statusList[row.data.status];
            result[0] = {html: status};
        }
        else
        {
            status = zahostLang.statusList[row.data.status];
            let html = "<span class=''>" + status + "</span>";
            if(row.data.status === 'wait') html = "<span class='text-danger'>" + status + "</span>";
            result[0] = {html: html};

        }

        return result;
    }

    return result;
};
