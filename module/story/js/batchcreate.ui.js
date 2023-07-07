window.renderCellData = function($cell)
{
    var $select = $cell.find('select');
    if($select.length > 0)
    {
        if($select.prop('multiple')){$select.attr('name', $select.attr('name') + '[]');}
    }
};
