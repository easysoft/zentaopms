window.setMultipleCell = function(value, info)
{
    if(!$.isArray(value)) value = value.toString().split(',');

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

window.checkedChange = function(changes)
{
    if(!this._checkedRows) this._checkedRows = {};
    Object.keys(changes).forEach((rowID) =>
    {
        const row = this.getRowInfo(rowID);
        if(row !== undefined) this._checkedRows[rowID] = row.data;
    });
}
