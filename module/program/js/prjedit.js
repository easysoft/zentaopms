$(function()
{
    $('#parent').click(function()
    {
        if(!confirm(PGMChangeTips)) return false;
    })

    adjustProductBoxMargin();
    adjustPlanBoxMargin();

    /* If the story of the product which linked the execution under the project, you don't allow to remove the product. */
    $("#productsBox select").each(function()
    {
        var isExisted = $.inArray($(this).attr('data-last'), notRemoveProducts);
        if(isExisted != -1)
        {
            $(this).prop('disabled', true).trigger("chosen:updated");
            $(this).siblings('div').find('span').attr('title', tip);
        }
    });

});

/**
 * Set aclList.
 *
 * @param  int   $programID
 * @access public
 * @return void
 */
function setAclList(programID)
{
    if(programID != 0)
    {
        $('.aclBox').html($('#PGMAcl').html());
    }
    else
    {
        $('.aclBox').html($('#PRJAcl').html());
    }
}

/**
 * Set parent program.
 *
 * @access public
 * @return void
 */
function setParentProgram()
{
    var parentProgram = $("#parent").val();

    if(confirm(PGMChangeTips))
    {
        location.href = createLink('program', 'PRJEdit', 'projectID=' + projectID + '&programID=' + parentProgram + '&from=' + from);
    }
    else
    {
        $('#parent').val(oldParent);
        $("#parent").trigger("chosen:updated");
    }
}
