window.setUserEmail = function()
{
    let   email   = '';
    const account = $(this).val();
    if(account && zentaoUsers[account]) email = zentaoUsers[account].email;

    $(this).closest('.dtable-cell').prev().find('.dtable-cell-content').text(email);
}

window.renderGitfoxUser = function(result, {row})
{
    const gitfoxID = row.data.gitfoxID;
    result.push({html: `<input type="hidden" name='gitfoxUserNames[]' value='${gitfoxID}'>`});

    return result;
}

window.bindUser = function(e)
{
    const myDTable = $('#table-gitfox-binduser').zui('dtable');
    const formData = myDTable.$.getFormData();

    var bindData = $('#table-gitfox-binduser').zui('dtable').$.props.data;
    var postData = {};
    postData['gitfoxUserNames[]'] = [];
    for(i in bindData)
    {
        postData['gitfoxUserNames[' + bindData[i].gitfoxID + ']'] = bindData[i].gitfoxUser;
        postData['zentaoUsers[' + bindData[i].gitfoxID + ']'] = formData['zentaoUsers[' + bindData[i].gitfoxID + ']'];
    }

    e.preventDefault();
    e.stopPropagation();
    $.ajaxSubmit({
        url: $.createLink('gitfox', 'bindUser', 'gitfoxID=' + gitfoxID + '&type=' + type),
        data: postData
    });
}

window.afterPageUpdate = function($target, info)
{
    if(info.name === 'dtable')
    {
        const dtable = zui.DTable.query('#table-gitfox-binduser');

        /* Clear saved formData. */
        if(dtable) dtable.$.resetFormData();
    }
};
