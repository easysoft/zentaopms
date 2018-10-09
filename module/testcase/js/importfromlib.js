$(function()
{
    $('.querybox-toggle').parent().addClass('active');

    $(document).on('click', '.chosen-with-drop', function()
    {
        var select = $(this).prev('select');
        if($(select).val() == 'ditto')
        {
            var index = $(select).closest('td').index();
            var row   = $(select).closest('tr').index();
            var table = $(select).closest('tr').parent();
            var value = '';
            for(i = row - 1; i >= 0; i--)
            {
                value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
                if(value != 'ditto') break;
            }
            $(select).val(value);
            $(select).trigger("chosen:updated");
        }
    });
})

function reload(libID)
{ 
    link = createLink('testcase','importFromLib','productID='+ productID + '&branch=' + branch + '&libID='+libID);
    location.href = link;
}
