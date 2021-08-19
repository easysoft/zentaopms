$(function()
{
    $('#gitlabID').change(function()
    {
        host  = $('#gitlabID').val();
        if(host == '') return false;
        url   = createLink('repo', 'ajaxgetgitlabprojects', "host=" + host);

        $.get(url, function(response)
        {
            $('#sourceProject').html('').append(response);
            $('#sourceProject').chosen().trigger("chosen:updated");;
        });
    });

    $('#sourceProject').change(function()
    {
        $option = $(this).find('option:selected');
        $('#name').val($option.data('name'));
        project = $('#sourceProject').val();
        url   = createLink('gitlab', 'ajaxgetprojectbranches', "gitlabID=" + host + "&projectID=" + project);
        $.get(url, function(response)
        {
            $('#sourceBranch').html('').append(response);
            $('#sourceBranch').chosen().trigger("chosen:updated");;
        });

        $('#targetProject').chosen().trigger("chosen:updated");;
    });
});
