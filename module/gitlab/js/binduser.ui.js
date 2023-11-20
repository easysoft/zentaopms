window.setUserEmail = function()
{
    let   email   = '';
    const account = $(this).val();
    if(account && zentaoUsers[account]) email = zentaoUsers[account].email;

    $(this).closest('.dtable-cell').prev().find('.dtable-cell-content').text(email);
}

window.renderGitlabUser = function(result, {row})
{
    const gitlabID = row.data.gitlabID;
    result.push({html: `<input type="hidden" name='gitlabUserNames[]' value='${gitlabID}'>`});

    return result;
}

window.bindUser = function(e)
{
    const myDTable = $('#table-gitlab-binduser').zui('dtable');
    const formData = myDTable.$.getFormData();

    var bindData = $('#table-gitlab-binduser').zui('dtable').$.props.data;
    var postData = {};
    postData['gitlabUserNames[]'] = [];
    for(i in bindData)
    {
        postData['gitlabUserNames[' + bindData[i].gitlabID + ']'] = bindData[i].gitlabUser;
        postData['zentaoUsers[' + bindData[i].gitlabID + ']'] = formData['zentaoUsers[' + bindData[i].gitlabID + ']'];
    }

    e.preventDefault();
    e.stopPropagation();
    $.ajaxSubmit({
        url: $.createLink('gitlab', 'bindUser', 'gitlabID=' + gitlabID + '&type=' + type),
        data: postData
    });
}

window.afterPageUpdate = function($target, info)
{
    if(info.name === 'dtable')
    {
        const dtable = zui.DTable.query('#table-gitlab-binduser');

        /* Clear saved formData. */
        if(dtable) dtable.$.resetFormData();
    }
};
