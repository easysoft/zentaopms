window.setMultipleCell = function(value, pairs, delimiter)
{
    value = value.filter((data)=>data);
    if(!value.length) return value;

    pairs = JSON.parse(pairs);
    const result = [];
    $.each(value.split(','), function(index, value)
    {
        if(value) result.push(pairs[value]);
    });
    return result.join(delimiter);
};
