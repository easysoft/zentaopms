$(function()
{
    $('#gitlabID').change(function()
    {
        gitlabID = $('#gitlabID').val();
        if(gitlabID == '') return false;

        url = createLink('repo', 'ajaxgetgitlabprojects', "gitlabID=" + gitlabID);
        $.get(url, function(response)
        {
            $('#sourceProject').html('').append(response);
            $('#sourceProject').chosen().trigger("chosen:updated");;
        });

        var assignee = $("#assignee").parents('td').find('select[name*=assignee]');
        var reviewer = $("#reviewer").parents('td').find('select[name*=reviewer]');
        usersUrl = createLink('gitlab', 'ajaxgetmruserpairs', "gitlabID=" + gitlabID);
        $.get(usersUrl, function(response)
        {
            assignee.html('').append(response);
            assignee.chosen().trigger("chosen:updated");;
            reviewer.html('').append(response);
            reviewer.chosen().trigger("chosen:updated");;
        });

    });

    $('#sourceProject,#targetProject').change(function()
    {
        sourceProject = $(this).val();
        var branchSelect = $(this).parents('td').find('select[name*=Branch]');
        branchUrl = createLink('gitlab', 'ajaxgetprojectbranches', "gitlabID=" + gitlabID + "&projectID=" + sourceProject);
        $.get(branchUrl, function(response)
        {
            branchSelect.html('').append(response);
            branchSelect.chosen().trigger("chosen:updated");;
        });

    });

    $('#sourceProject').change(function()
    {
        sourceProject = $(this).val();
        projectUrl = createLink('mr', 'ajaxGetMRTargetProjects', "gitlabID=" + gitlabID + "&projectID=" + sourceProject);
        $.get(projectUrl, function(response)
        {
            $('#targetProject').html('').append(response);
            $('#targetProject').chosen().trigger("chosen:updated");;
        });
    });
});
