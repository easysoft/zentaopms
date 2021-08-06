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
    
    oldProject = $("#project").val();
    $('#project').change(function()
    {
        if(!confirm('修改所属项目后，执行关联的原项目需求如果要更改所属项目，需手动修改到新项目，是否继续修改？')) $("#project").val(oldProject).trigger("chosen:updated");
    });
})
