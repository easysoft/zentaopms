$(function()
{
    $('#longTime').change(function()
    {
        if($(this).prop('checked'))
        {
            $('#end').val('').attr('disabled', 'disabled');
            $('#days').val('');
        }
        else
        {
            $('#end').removeAttr('disabled');
        }
    });

    $('#parent').click(function()
    {
        if(!confirm(PGMChangeTips)) return false;
    })

    adjustProductBoxMargin();
    adjustPlanBoxMargin();
});

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

function setParentProgram()
{
    var parentProgram = $("#parent").val();

    if(confirm(PGMChangeTips))
    {
        location.href = createLink('program', 'PRJEdit', 'projectID=' + projectID + '&programID=' + parentProgram);
    }
    else
    {
        $('#parent').val(oldParent);
        $("#parent").trigger("chosen:updated");
    }
}
