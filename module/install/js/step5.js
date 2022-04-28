$(function()
{
    $('#submit').click(function()
    {
        if($('#company').val().length == 0)
        {
            alert(errorEmpty.company);
            return false;
        }
        else if($('#account').val().length == 0)
        {
            alert(errorEmpty.account);
            return false;
        }
        else if($('#password').val().length == 0)
        {
            alert(errorEmpty.password);
            return false;
        }
    })
})
