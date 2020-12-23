$(function()
{
    $("input[type='radio'][value='open']").parent().each(function()
    {
        this.title = openTip;
    });

    $("input[type='radio'][value='private']").parent().each(function()
    {
        this.title = privateTip;
    });
})
