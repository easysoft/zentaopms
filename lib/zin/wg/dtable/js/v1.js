window.setMultipleCell = function(value, pairs, delimiter)
{
    pairs = JSON.parse(pairs);

    const result = [];
    $.each(value.split(','), function(index, value)
    {
        if(value) result.push(pairs[value]);
    });
    return result.join(delimiter);
};
