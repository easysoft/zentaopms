$(function()
{
    $('#parent').click(function()
    {
        if(!confirm(PGMChangeTips)) return false;
    })

    adjustProductBoxMargin();
    adjustPlanBoxMargin();
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
