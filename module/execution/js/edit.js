$().ready(function()
{
    $('#submit').click(function()
    {
        $('#products0').removeAttr("disabled");
        $('#branch0').removeAttr("disabled");
    });
});

$(function()
{
    /* If the story of the product which linked the execution under the project, you don't allow to remove the product. */
    $("#productsBox select").each(function()
    {
        var isExisted = $.inArray($(this).attr('data-last'), unmodifiableProducts);
        if(isExisted != -1)
        {
            $(this).prop('disabled', true).trigger("chosen:updated");
            $(this).siblings('div').find('span').attr('title', tip);
        }
    });
})
