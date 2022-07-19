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

$(function()
{
    $('#hostID').change(function()
    {
        var hostID = $('#hostID').val();
        if(hostID == '') return false;

        if(hosts[hostID].type == 'gitlab')
        {
            var url = createLink('repo', 'ajaxGetGitlabProjects', "gitlabID=" + hostID + "&projectIdList=&filter=IS_DEVELOPER");
        }
        else
        {
            var url = createLink('repo', 'ajaxGetGiteaProjects', "giteaID=" + hostID);
        }
        $.get(url, function(response)
        {
            $('#sourceProject').html('').append(response);
            $('#sourceProject').chosen().trigger("chosen:updated");;
        });
   });

    $('#sourceProject,#targetProject').change(function()
    {
        var hostID        = $('#hostID').val();
        var sourceProject = urlencode($(this).val());
        var branchSelect  = $(this).parents('td').find('select[name*=Branch]');
        var branchUrl     = createLink(hosts[hostID].type, 'ajaxGetProjectBranches', "hostID=" + hostID + "&projectID=" + sourceProject);
        $.get(branchUrl, function(response)
        {
            branchSelect.html('').append(response);
            branchSelect.chosen().trigger("chosen:updated");;
        });

    });

    $('#sourceProject').change(function()
    {
        var hostID        = $('#hostID').val();
        var sourceProject = urlencode($(this).val());
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
            $('#repoID').chosen().trigger("chosen:updated");;
        });
    });

    $('#sourceBranch,#targetBranch').change(function()
    {
        var sourceProject = urlencode($('#sourceProject').val());
        var sourceBranch  = urlencode($('#sourceBranch').val());
        var targetProject = urlencode($('#targetProject').val());
        var targetBranch  = urlencode($('#targetBranch').val());
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
                return false;
            }
        });
    });

    /*
    $('#targetProject').change(function()
    {
        targetProject = $(this).val();
        var hostID = $('#hostID').val();
        var assignee = $("#assignee").parents('td').find('select[name*=assignee]');
        var reviewer = $("#reviewer").parents('td').find('select[name*=reviewer]');
        usersUrl = createLink('gitlab', 'ajaxgetmruserpairs', "hostID=" + hostID + "&projectID=" + targetProject);
        $.get(usersUrl, function(response)
        {
            assignee.html('').append(response);
            assignee.chosen().trigger("chosen:updated");;
            reviewer.html('').append(response);
            reviewer.chosen().trigger("chosen:updated");;
        });
    });
    */

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
});
