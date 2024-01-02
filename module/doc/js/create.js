$(function()
{
    /* Set editor content height. */
    var contentHeight = $(document).height() - 92;
    setTimeout(function(){$('.ke-edit-iframe, .ke-edit, .ke-edit-textarea').height(contentHeight);}, 100);
    setTimeout(function(){$('.CodeMirror').height($(document).height() - 112);}, 100);
    $('iframe.ke-edit-iframe').contents().find('.article-content').css('padding', '20px 20px 0 20px');

    if(objectType == 'project') loadExecutions($('#project').val());

    /* Change for show create error. */
    $('#contentBox #content').attr('id', 'contentHTML');
    /* Copy doc title to modal title. */
    $('#modalBasicInfo').on('show.zui.modal', function()
    {
        $('#modalBasicInfo #copyTitle').html($('.doc-title #editorTitle').val().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;"));
    });

    $('#saveDraft').click(function(event)
    {
        if($.trim($('#editorTitle').val()) == '')
        {
            bootbox.alert(titleNotEmpty);
            return false;
        }
        $('#status').val('draft');
        event.preventDefault();
        submit(this);
    });

    $('#releaseBtn').click(function(event)
    {
        event.preventDefault();
        submit(this);
    });

    $('#basicInfoLink').click(function()
    {
        if($('#editorTitle').val() == '')
        {
            bootbox.alert(titleNotEmpty);
            return false;
        }
        if(requiredFields.indexOf('content') >= 0)
        {
            var contentType = $('#contentType').val();
            var content     = '';
            if(contentType == 'html')    content = $('#contentHTML').val();
            if(contentType == 'markdown')content = $('#contentMarkdown').val();
            if(content == '')
            {
                bootbox.alert(contentNotEmpty);
                return false;
            }
        }
        $('#status').val('normal');
    });

    if(docType == 'html' || docType == 'template') docType = docContentType;
    setTimeout(function(){initPage(docType)}, 50);
    if(typeof(window.editor) != 'undefined')
    {
        $('.ke-toolbar .ke-outline:last').after("<span data-name='unlink' class='ke-outline' title='Markdown' onclick='toggleEditor(\"markdown\")' style='font-size: unset; line-height: unset;'>Markdown</span>");
    }

    $(document).on("mouseup", 'span[data-name="fullscreen"]', function()
    {
        if($(this).hasClass('ke-selected'))
        {
            $('#submit').removeClass('fullscreen-save')
            $('.form-actions #submit').addClass('btn-wide')
        }
        else
        {
            $('#submit').addClass('fullscreen-save')
            $('.form-actions #submit').removeClass('btn-wide')
        }
    });

    $(document).on("mouseup", 'a[title="Fullscreen"],.icon-columns', function()
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
            }
            else
            {
                $('#submit').removeClass('markdown-fullscreen-save')
                $('#submit').addClass('btn-wide')
                $('.editor-toolbar').css('height', '30px');
                $('.editor-toolbar').css('padding-top', '1px');
                $('.CodeMirror').css('top', '0');
                $('.editor-preview-side').css('top', '30px');
            }
        }, 200);
    });

    if($(".createCustomLib").length == 1) $(".createCustomLib").click(); // Fix bug #15139.
})

function toggleEditor(type)
{
    toggleEditorMode(type);
    $('#contentType').val(type);

    var link = createLink('custom', 'ajaxSaveCustomFields', 'module=doc&section=common&key=docContentType');
    $.post(link, {fields: type});
}

function toggleEditorMode(type)
{
    if(type == 'html')
    {
        $('.contenthtml').removeClass('hidden');
        $('.contentmarkdown').addClass('hidden');
    }
    else if(type == 'markdown')
    {
        $('.contenthtml').addClass('hidden');
        $('.contentmarkdown').removeClass('hidden');
    }
}

function initPage(type)
{
    if(type == 'html' || type == 'markdown')
    {
        if(type == 'markdown')
        {
            $('#contentBox .contentmarkdown').removeClass('hidden');
            $('#contentBox .contenthtml').addClass('hidden');
            $('#contentType').val(type);
        }
    }
    else if(type == 'url')
    {
        $('#contentBox').addClass('hidden');
        $('#urlBox').removeClass('hidden');
    }
    if(type == 'word' || type == 'ppt' || type == 'excel')
    {
        $('#contentBox').hide();
        $('#urlBox').hide();
    }
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
    var groupLink = createLink('doc', 'ajaxGetWhitelist', 'libID=' + libID + '&acl=&control=group');
    var userLink  = createLink('doc', 'ajaxGetWhitelist', 'libID=' + libID + '&acl=&control=user');
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
        if(users != 'private')
        {
            $('#users').replaceWith(users);
            $('#users').next('.picker').remove();
            $('#users').picker();
        }
    });
}

/**
 * Redirect to edit page when create the doc of text type.
 *
 * @param  int     docID
 * @param  string  objectType
 * @param  int     objectID
 * @param  int     libID
 * @return void
 */
function redirect2Edit(docID, objectType, objectID, libID)
{
    parent.location.href = createLink('doc', 'edit', 'docID=' + docID + '&comment=false&objectType=' + objectType + '&objectID=' + objectID + '&libID=' + libID + '&from=create');
}
