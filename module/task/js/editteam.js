$(document).ready(function()
{
    $('#submit').click(function()
    {
        var memberCount = 0;
        $('select[name^=team]').each(function()
        {
            if($(this).find('option:selected').text() == '') return;
            memberCount++;
        })

        if(memberCount < 2)
        {
            alert(teamMemberError);
            return false;
        }
    });
});
