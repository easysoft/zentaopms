window.setUserEmail = function()
{
    let   email   = '';
    const account = $(this).val();
    if(account && zentaoUsers[account]) email = zentaoUsers[account].email;

    $(this).closest('.dtable-cell').prev().find('.dtable-cell-content').text(email);
}

window.renderGitlabUser = function(result, {row})
{
    const giteaID = row.data.giteaID;
    result.push({html: '<input type="hidden" name="giteaUserNames[' + row.id + ']" value="' + giteaID + '">'});

    return result;
}

window.bindUser = function(e)
{
    const myDTable = $('#table-gitea-binduser').zui('dtable');
    const formData = myDTable.$.getFormData();

    var bindData = $('#table-gitea-binduser').zui('dtable').$.props.data;
    var postData = {};
    postData['giteaUserNames[]'] = [];
    for(i in bindData)
    {
        postData['giteaUserNames[' + bindData[i].giteaID + ']'] = bindData[i].giteaAccount;
        postData['zentaoUsers[' + bindData[i].giteaID + ']'] = formData['zentaoUsers[' + bindData[i].giteaID + ']'];
    }

    e.preventDefault();
    e.stopPropagation();
    $.ajaxSubmit({
        url: $.createLink('gitea', 'bindUser', 'giteaID=' + giteaID + '&type=' + type),
        data: postData
    });
}

window.afterPageUpdate = function($target, info)
{
    if(info.name === 'dtable')
    {
        const dtable = zui.DTable.query('#table-gitea-binduser');

        /* Clear saved formData. */
        if(dtable) dtable.$.resetFormData();
    }
};
