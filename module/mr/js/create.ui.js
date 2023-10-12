/**
 * Urlencode param.
 *
 * @param  param $param
 * @access public
 * @return string
 */
function urlencode(param)
{
    if(hostType != 'gitlab') return Base64.encode(encodeURIComponent(param));

    return param;
}

/**
 * Get branch priv.
 *
 * @param  int|string $project
 * @access public
 * @return void
 */
function getBranchPriv(project)
{
    var sourceProject = projectNamespace ? urlencode(projectNamespace) : project;
    var branchUrl     = $.createLink('mr', 'ajaxGetBranchPivs', "hostID=" + hostID + "&project=" + sourceProject);
    $.get(branchUrl, function(response)
    {
        branchPrivs = eval('(' + response + ')');
    });
}

function onProjectChange()
{
    var sourceProject = projectNamespace ? urlencode(projectNamespace) : projectID;
    var branchUrl     = $.createLink(hostType, 'ajaxGetProjectBranches', hostType + "ID=" + hostID + "&projectID=" + sourceProject);
    $.ajaxSubmit(
    {
        url: branchUrl,
        method: 'get',
        onComplete: function(result)
        {
            zui.Picker.query("[name='sourceBranch']").render({items: result});
            zui.Picker.query("[name='targetBranch']").render({items: result});
            $("[name='targetBranch']").picker('setValue', '');
        },
    });

    getBranchPriv(projectID);
}

function onSourceProjectChange()
{
    var sourceProject = urlencode($('#sourceProject').val());
    if(!sourceProject) return false;

    if(sourceProject) getBranchPriv(sourceProject);
}

function onBranchChange()
{
    $('#removeSourceBranch').removeAttr('disabled');

    var sourceProject = $('#sourceProject').val();
    var sourceBranch  = $('#sourceBranch').val();
    var targetProject = $('#targetProject').val();
    var targetBranch  = $('#targetBranch').val();
    if(branchPrivs[sourceBranch])
    {
        $('#removeSourceBranch').attr('disabled', 'true');
        $('#removeSourceBranch').attr("checked",false);
    }
    if(!sourceProject || !sourceBranch || !targetProject || !targetBranch) return false;

    var $this    = $(this);
    var repoUrl  = $.createLink('mr', 'ajaxCheckSameOpened', "hostID=" + hostID);
    $.post(repoUrl, {"sourceProject": sourceProject, "sourceBranch": sourceBranch, "targetProject": targetProject, "targetBranch": targetBranch}, function(response)
    {
        response = $.parseJSON(response);
        if(response.result == 'fail')
        {
            alert(response.message);
            if($this.attr('id') == 'sourceBranch') $('#removeSourceBranch').removeAttr('disabled');
            return false;
        }
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
    if(repo.gitService)
    {
        onProjectChange();
    }
});
