function switchDateTodo(switcher)
{
    if(switcher.checked)
    {
        $('#date').attr('disabled','disabled');
    }
    else
    {
        $('#date').removeAttr('disabled');
    }
}

