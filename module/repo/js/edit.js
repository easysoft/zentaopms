$(function()
{
    scmChanged(scm);
    $('#submit').mousedown(function()
    {
        $form = $(this).closest('form');
        $form.css('min-height', $form.height());
    })

    $('#gitlabHost').change(function()
    {
        host = $('#gitlabHost').val();
        if(host == '') return false;
        url  = createLink('repo', 'ajaxGetGitlabProjects', "host=" + host);

        $.get(url, function(response)
        {
            $('#gitlabProject').html('').append(response);
            $('#gitlabProject').chosen().trigger("chosen:updated");;
        });
    });

    $('#gitlabProject').change(function()
    {
        $option = $(this).find('option:selected');
        if(!$option.data('name')) return false;
        $('#name').val($option.data('name'));
        $(this).chosen().trigger("chosen:updated");
    });
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
