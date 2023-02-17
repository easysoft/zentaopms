$(function()
{
    config.onlybody = 'no';

    toggleAcl($('input[name="acl"]:checked').val(), 'doc');

    if($(".createCustomLib").length == 1) $(".createCustomLib").click(); // Fix bug #15139.

    $('#saveBtn').click(function()
    {
        var requiredArr = ['lib', 'title', 'keywords'];
        for(var i = 0; i < 3; i++)
        {
            if(requiredFields.indexOf(',' + requiredArr[i] + ',') != -1 && !$('#' + requiredArr[i]).val().trim()) return alert(eval(requiredArr[i] + 'NotEmpty'));
        }

        var params = $('#dataform').serializeArray();

        sessionStorage.setItem('docBasicInfo', JSON.stringify(params));

        var libID    = $('#lib').val();
        var moduleID = $('#module').val();
        var link     = createLink('doc', 'create', 'objectType=' + objectType + '&objectID=' + objectID + '&libID=' + libID + '&moduleID=' + moduleID + '&docType=' + docType);

        parent.location.href = link;
    })
})

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
