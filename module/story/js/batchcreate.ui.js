window.renderRowData = function($row)
{
    $row.find('select').each(function()
    {
        $select = $(this);
        if($select.prop('multiple')){$select.attr('name', $select.attr('name') + '[]');}
    });
};
