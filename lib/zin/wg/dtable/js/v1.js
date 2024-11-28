window.setMultipleCell = function(value, info)
{
    value = value.filter((data) => data);
    if(!value.length) return value;

    pairs = info.col.setting.dataPairs;
    const result = [];
    const data   = $.isArray(value) ? value : value.split(',');
    $.each(data, function(_, value)
    {
        if(value && pairs[value]) result.push(pairs[value]);
    });
    return result.join(info.col.setting.delimiter);
};
