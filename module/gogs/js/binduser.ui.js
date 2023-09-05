window.setUserEmail = function()
{
    let   email   = '';
    const account = $(this).val();
    if(account && zentaoUsers[account]) email = zentaoUsers[account].email;

    $(this).closest('.dtable-cell').prev().find('.dtable-cell-content').text(email);
}

window.renderGitlabUser = function(result, {row})
{
    const gogsID = row.data.gogsID;
    result.push({html: `<input type="hidden" name='gogsUserNames[]' value='${gogsID}'>`});

    return result;
}
