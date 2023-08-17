window.renderRepobugList = function (result, {col, row, value})
{
    if(col.name == 'entry')
    {
        result[0] = {html: '<span class="label primary mr-1">' + row.data.lines + '</span><a href="' + row.data.link + '" data-toggle="modal">' + row.data.entry + '</a>'};
        return result;
    }

    return result;
}
