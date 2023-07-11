function onRepoChange()
{
    var repoID = $(this).val();
    var jobUrl = $.createLink('mr', 'ajaxGetJobList', "repoID=" + repoID);
    $.get(jobUrl, function(response)
    {
        $('#jobID').html('').append(response);
    });
}

function onNeedCiChange(event)
{
    const $needCi = $(event.target) ;

    if($needCi.prop('checked') == false)
    {
        $("#jobID").prop("disabled", true);
        $("#jobID").parent().parent().addClass('hidden');
    }
    if($needCi.prop('checked') == true)
    {
        $("#jobID").prop("disabled", false);
        $("#jobID").parent().parent().removeClass('hidden');
    }
}

$(function()
{
    $("#needCI").trigger('change');
});
