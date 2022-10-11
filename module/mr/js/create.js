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
    var branchUrl = createLink('mr', 'ajaxGetBranchPivs', "hostID=" + hostID + "&project=" + project);
    $.get(branchUrl, function(response)
    {
        branchPrivs = eval('(' + response + ')');
    });
}

$(function()
{
    $('#sourceProject,#targetProject').change(function()
    {
        var hostID        = $('#hostID').val();
        var sourceProject = urlencode($(this).val());
        if(!sourceProject) return false;

        var branchSelect  = $(this).parents('td').find('select[name*=Branch]');
        var branchUrl     = createLink(hosts[hostID].type, 'ajaxGetProjectBranches', hosts[hostID].type + "ID=" + hostID + "&projectID=" + sourceProject);
        $.get(branchUrl, function(response)
        {
            branchSelect.html('').append(response);
            branchSelect.chosen().trigger("chosen:updated");;
        });

    });

    $('#sourceProject').change(function()
    {
        $('#sourceBranch,#targetProject,#targetBranch').empty();
        $('#sourceBranch,#targetProject,#targetBranch').chosen().trigger("chosen:updated");;

        var hostID        = $('#hostID').val();
        var sourceProject = urlencode($(this).val());
        if(!sourceProject) return false;

        var projectUrl    = createLink('mr', 'ajaxGetMRTargetProjects', "hostID=" + hostID + "&projectID=" + sourceProject + "&scm=" + hosts[hostID].type);
        $.get(projectUrl, function(response)
        {
            $('#targetProject').html('').append(response);
            $('#targetProject').chosen().trigger("chosen:updated");;
        });

        var repoUrl = createLink('mr', 'ajaxGetRepoList', "hostID=" + hostID + "&projectID=" + sourceProject);
        $.get(repoUrl, function(response)
        {
            $('#repoID').html('').append(response);
            $('#repoID').val(repo.id);
            $('#repoID').chosen().trigger("chosen:updated");
            $('#repoID').change();
        });

        if(sourceProject) getBranchPriv(sourceProject);
    });

    $('#sourceBranch,#targetBranch').change(function()
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
        var repoUrl  = createLink('mr', 'ajaxCheckSameOpened', "hostID=" + hostID);
        $.post(repoUrl, {"sourceProject": sourceProject, "sourceBranch": sourceBranch, "targetProject": targetProject, "targetBranch": targetBranch}, function(response)
        {
            response = $.parseJSON(response);
            if(response.result == 'fail')
            {
                alert(response.message);
                $this.val('').trigger('chosen:updated');
                if($this.attr('id') == 'sourceBranch') $('#removeSourceBranch').removeAttr('disabled');
                return false;
            }
        });
    });

    $('#repoID').change(function()
    {
        var repoID = $(this).val();
        var jobUrl = createLink('mr', 'ajaxGetJobList', "repoID=" + repoID);
        $.get(jobUrl, function(response)
        {
            $('#jobID').html('').append(response);
            $('#jobID').chosen().trigger("chosen:updated");;
        });
    });

    $('#jobID').change(function()
    {
        var jobID      = $(this).val();
        var compileUrl = createLink('mr', 'ajaxGetCompileList', "job=" + jobID);
        $.get(compileUrl, function(response)
        {
            $('#compile').html('').append(response);
            $('#compile').chosen().trigger("chosen:updated");;
        });
    });

    $("#needCI").change(function()
    {
        if(this.checked == false)
        {
            $("#jobID").prop("disabled", true);
            $('#jobID').chosen().trigger("chosen:updated");;
            $("#jobID").parent().parent().addClass('hidden');
        }
        if(this.checked == true)
        {
            $("#jobID").prop("disabled", false);
            $('#jobID').chosen().trigger("chosen:updated");;
            $("#jobID").parent().parent().removeClass('hidden');
        }
    });

    $('#hostID').change(function()
    {
        $('#sourceProject,#sourceBranch,#targetProject,#targetBranch').empty();
        $('#sourceProject,#sourceBranch,#targetProject,#targetBranch').chosen().trigger("chosen:updated");;

        var hostID = $('#hostID').val();
        if(hostID == '') return false;

        if(hosts[hostID].type == 'gitlab')
        {
            var url = createLink('repo', 'ajaxGetGitlabProjects', "gitlabID=" + hostID + "&projectIdList=&filter=IS_DEVELOPER");
        }
        else if(hosts[hostID].type == 'gitea')
        {
            var url = createLink('repo', 'ajaxGetGiteaProjects', "giteaID=" + hostID);
        }
        else if(hosts[hostID].type == 'gogs')
        {
            var url = createLink('repo', 'ajaxGetGogsProjects', "gogsID=" + hostID);
        }
        $.get(url, function(response)
        {
            if(response == "<option value=''></option>" && confirm(mrLang.addForApp) == true) window.open(hosts[hostID].url);

            $('#sourceProject').html('').append(response);
            if(repo.project)
            {
                $('#sourceProject').val(repo.project);
                $('#sourceProject').change();
            }
            $('#sourceProject').chosen().trigger("chosen:updated");;
        });
   });

    if(repo.gitService) $('#hostID').change();
});
