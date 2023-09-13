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
