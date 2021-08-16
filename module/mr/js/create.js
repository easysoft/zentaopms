$(function()
{
    $('#gitlabID').change(function()
    {
        host  = $('#gitlabID').val();
        url   = createLink('repo', 'ajaxgetgitlabprojects', "host=" + host);
        if(host == '') return false;

        $.get(url, function(response)
        {
            $('#projectID').html('').append(response);
            $('#projectID').chosen().trigger("chosen:updated");;
        });
    });

    $('#projectID').change(function()
    {
        $option = $(this).find('option:selected');
        $('#name').val($option.data('name'));
    });

});

