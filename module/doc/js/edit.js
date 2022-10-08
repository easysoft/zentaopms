$(function()
{
    setTimeout(function()
    {
        if(needUpdateContent && confirm(confirmUpdateContent))
        {
            $('#content').html(draft);
            editor = KindEditor.instances[0];
            editor.html('' + draft);
            $('.kindeditor-ph').remove();
        }
    }, 100)

    $('#top-submit').click(function()
    {
        $(this).addClass('disabled');
        $('form').submit();
    })
    toggleAcl($('input[name="acl"]:checked').val(), 'doc');
    $('input[name="type"]').change(function()
    {
        var type = $(this).val();
        if(type == 'text')
        {
            $('#contentBox').removeClass('hidden');
            $('#urlBox').addClass('hidden');
        }
        else if(type == 'url')
        {
            $('#contentBox').addClass('hidden');
            $('#urlBox').removeClass('hidden');
        }
    });

    $('#subNavbar li[data-id="doc"]').addClass('active');

    /* Automatically save document contents. */
    setInterval("saveDraft()", 60 * 1000);

    $(document).on("mouseup", 'span[data-name="fullscreen"]', function()
    {
        if(config.onlybody == 'no')
        {
            if($(this).hasClass('ke-selected'))
            {
                $('#submit').removeClass('fullscreen-save')
                $('#submit').addClass('btn-wide')
            }
            else
            {
                $('#submit').addClass('fullscreen-save')
                $('#submit').removeClass('btn-wide')
            }
        }
    });

    $(document).on("mouseup", 'a[title="Fullscreen"],.icon-columns', function()
    {
        if(config.onlybody == 'no')
        {
            setTimeout(function()
            {
                if($('a[title="Fullscreen"]').hasClass('active'))
                {
                    $('#submit').addClass('markdown-fullscreen-save')
                    $('#submit').removeClass('btn-wide')
                    $('.fullscreen').css('height', '50px');
                    $('.fullscreen').css('padding-top', '15px');
                    $('.CodeMirror-fullscreen').css('top', '50px');
                    $('.editor-preview-side').css('top', '50px');
                    $('#submit').data('placement', 'bottom');
                }
                else
                {
                    $('#submit').removeClass('markdown-fullscreen-save')
                    $('#submit').addClass('btn-wide')
                    $('.editor-toolbar').css('height', '30px');
                    $('.editor-toolbar').css('padding-top', '1px');
                    $('.CodeMirror').css('top', '0');
                    $('.editor-preview-side').css('top', '30px');
                    $('#submit').data('placement', 'right');
                }
            }, 200);
        }
        else
        {
            setTimeout(function()
            {
                if($('a[title="Fullscreen"]').hasClass('active'))
                {
                    $('.main-header').hide();
                    $('#submit').addClass('markdown-fullscreen-save');
                    $('#submit').removeClass('btn-wide');
                    $('#mainContent .fullscreen').css('padding-top', '8px');
                    $('#mainContent .fullscreen').css('height', '40px');
                    $('.CodeMirror-fullscreen').css('top', '40px');
                    $('.editor-preview-side').css('top', '40px');
                    $('#submit').data('placement', 'left');
                    parent.$('.modal-header > .close').addClass('fullscreen-close');
                }
                else
                {
                    $('.main-header').show();
                    $('#submit').removeClass('markdown-fullscreen-save');
                    $('#mainContent .editor-toolbar').css('padding', '1px');
                    $('#mainContent .editor-toolbar').css('height', '30px');
                    $('.CodeMirror').css('top', '0px');
                    $('.editor-preview-side').css('top', '0px');
                    parent.$('.modal-header > .close').removeClass('fullscreen-close');
                }
            }, 200);
        }
    });
})

/**
 * Save draft doc.
 *
 * @access public
 * @return void
 */
function saveDraft()
{
    var content = $('#content').val();
    var link    = createLink('doc', 'ajaxSaveDraft', 'docID=' + docID);
    $.post(link, {content: content});
}

/**
 * Load whitelist by libID.
 *
 * @param  int    $libID
 * @access public
 * @return void
 */
function loadWhitelist(libID)
{
    var groupLink = createLink('doc', 'ajaxGetWhitelist', 'libID=' + libID + '&acl=&control=group' + '&docID=' + docID);
    var userLink  = createLink('doc', 'ajaxGetWhitelist', 'libID=' + libID + '&acl=&control=user' + '&docID=' + docID);
    $.post(groupLink, function(groups)
    {
        if(groups != 'private')
        {
            $('#groups').replaceWith(groups);
            $('#groups').next('.picker').remove();
            $('#groups').picker();
        }
    });

    $.post(userLink, function(users)
    {
        if(users == 'private')
        {
            $('#aclopen').parent('.radio-inline').addClass('hidden');
            $('#aclcustom').parent('.radio-inline').addClass('hidden');
            $('#whiteListBox').addClass('hidden');
            $('#aclprivate').prop('checked', true);
        }
        else if(users == 'project')
        {
            $('#aclprivate').parent('.radio-inline').addClass('hidden');
            $('#aclcustom').parent('.radio-inline').addClass('hidden');
            $('#whiteListBox').addClass('hidden');
            $('#aclopen').prop('checked', true);
        }
        else
        {
            $('#aclopen').parent('.radio-inline').removeClass('hidden');
            $('#aclcustom').parent('.radio-inline').removeClass('hidden');

            $('#users').replaceWith(users);
            $('#users').next('.picker').remove();
            $('#users').picker();
        }
    });
}
