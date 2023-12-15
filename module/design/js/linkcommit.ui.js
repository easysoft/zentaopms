function loadCommit()
{
    let begin  = $('.select-date-box input[name=begin]').val();
    let end    = $('.select-date-box input[name=end]').val();
    let repoID = $('.select-repo-box input[name=repo]').val();

    if(begin.indexOf('-') != -1)
    {
        let beginarray = begin.split("-");
        begin = '';
        for(i = 0; i < beginarray.length; i++) begin = begin + beginarray[i];
    }
    if(end.indexOf('-') != -1)
    {
        let endarray = end.split("-");
        end = '';
        for(i = 0 ; i < endarray.length ; i++) end = end + endarray[i];
    }

    if(begin > end)
    {
        zui.Modal.alert(errorDate);
        loadPage($.createLink('design', 'linkCommit', "designID=" + designID + '&repoID=' + repoID));
        return false;
    }

    loadModal($.createLink('design', 'linkCommit', "designID=" + designID + '&repoID=' + repoID + '&begin=' + begin + '&end=' + end), 'viewCommitModal');
}

$(document).off('click','.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('revision[]', id));

    $.ajaxSubmit({url, data: form});
});
