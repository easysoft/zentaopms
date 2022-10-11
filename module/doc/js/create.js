$(function()
{
    toggleAcl($('input[name="acl"]:checked').val(), 'doc');
    setTimeout(function(){initPage(docType)}, 50);
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

    if(!fromGlobal && textType.indexOf(docType) != -1 && from == 'doc')
    {
        var basicInfo = JSON.parse(sessionStorage.getItem('docBasicInfo'));

        var libID       = 0;
        var moduleID    = 0;
        var title       = '';
        var keywords    = '';
        var type        = '';
        var acl         = '';
        var contentType = '';
        var fileNames   = [];
        var mailto      = [];
        var groups      = [];
        var users       = [];

        $.each(basicInfo, function(index, value)
        {
            switch(value.name)
            {
                case 'lib':
                    libID = value.value;
                    break;
                case 'module':
                    moduleID = value.value;
                    break;
                case 'title':
                    title = value.value;
                    break;
                case 'keywords':
                    keywords = value.value;
                    break;
                case 'type':
                    type = value.value;
                    break;
                case 'acl':
                    acl = value.value;
                    break;
                case 'contentType':
                    contentType = value.value;
                    break;
                case 'mailto[]':
                    mailto.push(value.value);
                    break;
                case 'groups[]':
                    groups.push(value.value);
                    break;
                case 'users[]':
                    users.push(value.value);
                    break;
            }
        })

        $('#title').val(title);
        $('#modalBasicInfo #keywords').val(keywords);
        $('#modalBasicInfo #mailto').data('zui.picker').setValue(mailto);
        $('#modalBasicInfo input:radio[value='+ acl +']').attr('checked', 'checked');
        toggleAcl($('input[name="acl"]:checked').val(), 'doc');
        setTimeout(function(){$('#modalBasicInfo #groups').data('zui.picker').setValue(groups)}, 1000);
        setTimeout(function(){$('#modalBasicInfo #users').data('zui.picker').setValue(users)}, 1000);
    }
})

function toggleEditor(type)
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
    $('#contentType').val(type);
    return false;
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
