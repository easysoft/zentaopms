$(function()
{
    scmChanged('Gitlab');
    $('#submit').mousedown(function()
    {
        $form = $(this).closest('form');
        $form.css('min-height', $form.height());
    });

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

    $('#product').change(function()
    {
        var projects = $('#projects').val();
        var products = $('#product').val();
        $.post(createLink('repo', 'ajaxProjectsOfProducts'), {products, projects}, function(response)
        {
            $('#projectContainer').html('').append(response);
            $('#projects').chosen().trigger("chosen:updated");
        });
    });

    $('#serviceHost').change(function()
    {
        var host = $('#serviceHost').val();
        var url  = createLink('repo', 'ajaxGetProjects', "host=" + host);
        if(host == '') return false;

        $.get(url, function(response)
        {
            $('#serviceProject').html('').append(response);
            $('#serviceProject').chosen().trigger("chosen:updated");;
        });
    });

    $('#serviceProject').change(function()
    {
        $option = $(this).find('option:selected');
        $('#name').val($option.data('name'));
    });

    $('#serviceHost').change();
});

/**
 * Changed SCM.
 *
 * @param  string $scm
 * @access public
 * @return void
 */
function scmChanged(scm)
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

        var url = createLink('repo', 'ajaxGetHosts', "scm=" + scm);
        $.get(url, function(response)
        {
            $('#serviceHost').html(response);
            $('#serviceHost').chosen().trigger("chosen:updated");;
            $('#serviceHost').change();
        });
    }
}
