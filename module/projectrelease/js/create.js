$('#submit').click(function()
{
    var dateFormat = new RegExp(/^\d{4}\-\d{2}\-\d{2}$/);
    var name       = $('#name').val();
    var date       = $('#date').val();
    if(name && dateFormat.test(date))
    {
        if(confirm(confirmLink))
        {   
             $('#sync').val('true');
        }
        else
        {   
             $('#sync').val('false');
        }
    }
});
