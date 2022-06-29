$(function()
{
    scmChanged('Gitlab');
    $('#submit').mousedown(function()
    {
        $form = $(this).closest('form');
        $form.css('min-height', $form.height());
    })

    $('#repoForm').bind('DOMNodeInserted', function(e)
    {
        if($("#clientLabel").length > 0)
        {
            if($("#client").val() !== '' && $("#client").val().indexOf(" ") == -1)
            {
                $("#clientLabel").css('color', '#0c64eb');
                $("#client").attr('style', 'border-color: #0c64eb !important;box-shadow: 0 0 6px #0c64eb !important;');
            }
            else
            {
                $("#client").removeAttr("style");
            }
        }
    });

    $('#gitlabHost').change(function()
    {
        host  = $('#gitlabHost').val();
        url   = createLink('repo', 'ajaxGetGitlabProjects', "host=" + host);
        if(host == '') return false;

        $.get(url, function(response)
        {
            $('#gitlabProject').html('').append(response);
            $('#gitlabProject').chosen().trigger("chosen:updated");;
        });
    });

    $('#gitlabProject').change(function()
    {
        $option = $(this).find('option:selected');
        $('#name').val($option.data('name'));
    });

    $('#gitlabHost').change();
});

function scmChanged(scm)
{
    if(scm == 'Git')
    {
        $('.account-fields').addClass('hidden');

        $('.tips-git').removeClass('hidden');
        $('.tips-svn').addClass('hidden');
    }
    else
    {
        $('.account-fields').removeClass('hidden');

        $('.tips-git').addClass('hidden');
        $('.tips-svn').removeClass('hidden');
    }

    $('tr.gitlab').toggle(scm == 'Gitlab');
    $('tr.hide-gitlab').toggle(scm != 'Gitlab');
}
