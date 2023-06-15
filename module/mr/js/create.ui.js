/**
 * Urlencode param.
 *
 * @param  param $param
 * @access public
 * @return string
 */
function urlencode(param)
{
    var hostID = $('#hostID').val();
    if(hosts[hostID].type != 'gitlab') return Base64.encode(encodeURIComponent(param));

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
    var hostID    = $('#hostID').val();
    var branchUrl = $.createLink('mr', 'ajaxGetBranchPivs', "hostID=" + hostID + "&project=" + project);
    $.get(branchUrl, function(response)
    {
        branchPrivs = eval('(' + response + ')');
    });
}

function onProjectChange(event)
{
    const $project = $(event.target) ;
    var hostID        = $('#hostID').val();
    var sourceProject = urlencode($project.val());
    if(!sourceProject) return false;

    var branchSelect  = $project.parents('div').find('select[name*=Branch]');
    var branchUrl     = $.createLink(hosts[hostID].type, 'ajaxGetProjectBranches', hosts[hostID].type + "ID=" + hostID + "&projectID=" + sourceProject);
    $.get(branchUrl, function(response)
    {
        branchSelect.html('').append(response);
    });
}

function onSourceProjectChange()
{
    $('#sourceBranch,#targetProject,#targetBranch').empty();

    var hostID        = $('#hostID').val();
    var sourceProject = urlencode($('#sourceProject').val());
    if(!sourceProject) return false;

    var projectUrl    = $.createLink('mr', 'ajaxGetMRTargetProjects', "hostID=" + hostID + "&projectID=" + sourceProject + "&scm=" + hosts[hostID].type);
    $.get(projectUrl, function(response)
    {
        $('#targetProject').html('').append(response);
    });

    var repoUrl = $.createLink('mr', 'ajaxGetRepoList', "hostID=" + hostID + "&projectID=" + sourceProject);
    $.get(repoUrl, function(response)
    {
        $('#repoID').html('').append(response);
        $('#repoID').val(repo.id);
        onReopChange();
    });

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
    var hostID = $('#hostID').val();
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

function onHostChange()
{
    $('#sourceProject,#sourceBranch,#targetProject,#targetBranch').empty();

    var hostID = $('#hostID').val();
    if(hostID == '') return false;

    if(hosts[hostID].type == 'gitlab')
    {
        var url = $.createLink('repo', 'ajaxGetGitlabProjects', "gitlabID=" + hostID + "&projectIdList=&filter=IS_DEVELOPER");
    }
    else if(hosts[hostID].type == 'gitea')
    {
        var url = $.createLink('repo', 'ajaxGetGiteaProjects', "giteaID=" + hostID);
    }
    else if(hosts[hostID].type == 'gogs')
    {
        var url = $.createLink('repo', 'ajaxGetGogsProjects', "gogsID=" + hostID);
    }
    $.get(url, function(response)
    {
        if(response == "<option value=''></option>" && confirm(mrLang.addForApp) == true) window.open(hosts[hostID].url);

        $('#sourceProject').html('').append(response);
        if(repo.project)
        {
            $('#sourceProject').val(repo.project);
            onSourceProjectChange();
        }
    });
}

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
    if(repo.gitService) onHostChange();
});
