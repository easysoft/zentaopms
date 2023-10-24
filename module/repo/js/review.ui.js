window.renderRepobugList = function (result, {col, row, value})
{
    if(col.name == 'entry')
    {
        result[0] = {html: '<span class="label primary mr-1">' + row.data.lines + '</span><a href="' + row.data.link + '">' + row.data.entry + '</a>'};
        return result;
    }

    return result;
}

window.createSortLink = function(col)
{
    var sort = col.name + '_asc';
    if(sort == orderBy) sort = col.name + '_desc';

    return sortLink.replace('{orderBy}', sort);
};