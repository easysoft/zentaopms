$(function()
{
    scmChanged(scm, true);
    $('#submit').mousedown(function()
    {
        $form = $(this).closest('form');
        $form.css('min-height', $form.height());
    })

    $('#serviceHost').change(function()
    {
        host = $('#serviceHost').val();
        if(host == '') return false;
        url  = createLink('repo', 'ajaxGetProjects', "host=" + host);

        $.get(url, function(response)
        {
            $('#serviceProject').html('').append(response);
            $('#serviceProject').chosen().trigger("chosen:updated");;
        });
    });

    $('#serviceProject').change(function()
    {
        $option = $(this).find('option:selected');
        if(!$option.data('name')) return false;
        $('#name').val($option.data('name'));
        $(this).chosen().trigger("chosen:updated");
    });
});

/**
 * Changed SCM.
 *
 * @param  string $scm
 * @access public
 * @return void
 */
function scmChanged(scm, isFirstRequest = false)
{
    if(scm == 'Git' || scm == 'Gitea' || scm == 'Gogs')
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

    if(scm == 'Git' || scm == 'Subversion')
    {
        $('tr.service').toggle(false);
        $('tr.hide-service').toggle(true);
    }
    else
    {
        $('.tips').addClass('hidden');
        $('tr.service').toggle(true);
        if(scm == 'Gitea' || scm == 'Gogs')
        {
            $('tr.hide-service:not(".hide-git")').toggle(true);
            $('tr.hide-git').toggle(false);
        }
        else
        {
            $('tr.hide-service').toggle(false);
        }

        if(!isFirstRequest)
        {
            var url = createLink('repo', 'ajaxGetHosts', "scm=" + scm);
            $.get(url, function(response)
            {
                $('#serviceHost').html(response);
                $('#serviceHost').chosen().trigger("chosen:updated");;
                $('#serviceHost').change();
            });
        }
    }
}
