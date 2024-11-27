window.renderCell = function(result, {col, row})
{
    if(col.name === 'name' && row.data.integrated == 1)
    {
        result[0] = {className: 'overflow-hidden', html: result[0]};
        result[result.length] = {html:'<span class="label gray-pale rounded-xl clip">' + systemLang.integratedLabel + '</span>', className:'flex items-end', style:{flexDirection:"column"}};
    }

    return result;
};
