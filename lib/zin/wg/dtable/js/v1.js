window.setMultipleCell = function(value, pairs, delimiter)
{
    pairs = JSON.parse(pairs);
    return value.split(',').map((data) => pairs[data]).join(delimiter);
}
