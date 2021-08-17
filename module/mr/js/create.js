$(function()
{
    $('#gitlabID').change(function()
    {
        host  = $('#gitlabID').val();
        url   = createLink('repo', 'ajaxgetgitlabprojects', "host=" + host);
        if(host == '') return false;

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
    });

});

