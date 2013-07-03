$(function(){
    startDate = new Date(1970, 1, 1);
    $(".date").datePicker({
          createButton: true,
          startDate: startDate,
          endDate: new Date()
    }).dpSetPosition($.dpConst.POS_TOP, $.dpConst.POS_LEFT);
})

$("#recordForm").submit(function()
{
    $('#recordForm .left').each(function()
    {
        if($(this).val() !== '') left = $(this).val();
    });
    if(left === '0')
    {
        return confirm(confirmRecord);
    }
});
