window.renderRowData = function($row, index, row)
{
    if(row.type == 'stage')
    {
        /* If is stage, modify lifetime to attribute. */
        let $attribute = $row.find('.form-batch-input[data-name="lifetime"]').empty();
        let name       = $attribute.attr('name');
        $attribute.attr('name', name.replace('lifetime', 'attribute'));

        for(let key in stageList)
        {
            $attribute.append('<option value="' + key +'"' + (row.attribute == key ? 'selected' : '') + '>' + stageList[key] + '</option>');
        }
    }
}
