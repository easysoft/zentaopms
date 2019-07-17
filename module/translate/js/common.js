function adjustKeyWidth()
{
    var maxWidth = 30;
    $('.table-data tbody tr').each(function()
    {
        keyWidth = $(this).find('td').eq(1).find('nobr').width();
        if(maxWidth < keyWidth) maxWidth = keyWidth;
    });
    $('.table-data thead tr:first th').eq(1).width(maxWidth);
}
