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

            const picker = zui.Picker.query("[name='targetBranch']");
            picker.render({items: result});
            picker.$.setValue('');
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
    const picker  = zui.Picker.query("[name='jobID']");
    if($needCi.prop('checked') == false)
    {
        picker.render({"disabled": true, "items": []});
        $("#jobID").addClass('hidden');
    }
    else
    {
        const items = [];
        for(const jobID in jobPairs) items.push({"value": jobID, "text": jobPairs[jobID]});
        picker.render({"disabled": false, "items": items});
        $("#jobID").removeClass('hidden');
    }
}

function changeRepo()
{
    const repoID = $('input[name=repoID]').val();
    loadPage($.createLink('mr', 'create', `repoID=${repoID}&objectID=${objectID}`));
}

$(function()
{
    if(repo.gitService)
    {
        onProjectChange();
    }
});
