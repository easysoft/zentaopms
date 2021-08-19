$(function()
{
    $('#gitlabID').change(function()
    {
        host = $('#gitlabID').val();
        if(host == '') return false;

        url = createLink('repo', 'ajaxgetgitlabprojects', "host=" + host);
        $.get(url, function(response)
        {
            $('#sourceProject').html('').append(response);
            $('#sourceProject').chosen().trigger("chosen:updated");;
        });
    });

    $('#sourceProject,#targetProject').change(function()
    {
        sourceProject = $(this).val();
        var branchSelect = $(this).parents('td').find('select[name*=Branch]');
        branchUrl = createLink('gitlab', 'ajaxgetprojectbranches', "gitlabID=" + host + "&projectID=" + sourceProject);
        $.get(branchUrl, function(response)
        {
            branchSelect.html('').append(response);
            branchSelect.chosen().trigger("chosen:updated");;
        });

    });

    $('#sourceProject').change(function()
    {
        sourceProject = $(this).val();
        projectUrl = createLink('mr', 'ajaxGetMRTragetProjects', "gitlabID=" + host + "&projectID=" + sourceProject);
        $.get(projectUrl, function(response)
        {
            $('#targetProject').html('').append(response);
            $('#targetProject').chosen().trigger("chosen:updated");;
        });
    });
});
