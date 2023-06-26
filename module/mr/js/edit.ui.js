function onRepoChange()
{
    var repoID = $(this).val();
    var jobUrl = $.createLink('mr', 'ajaxGetJobList', "repoID=" + repoID);
    $.get(jobUrl, function(response)
    {
        $('#jobID').html('').append(response);
    });
}

function onNeedCiChange()
{
    if(this.checked == false)
    {
        $("#jobID").prop("disabled", true);
        $("#jobID").parent().parent().addClass('hidden');
    }
    if(this.checked == true)
    {
        $("#jobID").prop("disabled", false);
        $("#jobID").parent().parent().removeClass('hidden');
    }
}

$(function()
{
    $("#needCI").trigger('change');
});
