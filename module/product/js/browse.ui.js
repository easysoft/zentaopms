$(document).on('click', '.batch-btn', function()
{
    const $this       = $(this);
    const dtable      = zui.DTable.query($('#stories'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = new FormData();
    checkedList.forEach((id) => postData.append('storyIdList[]', id));
    if($this.data('account')) postData.append('assignedTo', $this.data('account'));

    if($this.data('page') == 'batch')
    {
        postAndLoadPage($this.data('formaction'), postData);
    }
    else
    {
        $.ajaxSubmit({"url": $this.data('formaction'), "data": postData});
    }
});

$(document).on('click', '#batchUnlinkStory', function(e)
{
    const $this       = $(this);
    const dtable      = zui.DTable.query($('#stories'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    let batchUnlinkStoryURL = $.createLink('projectstory', 'batchUnlinkStory', 'projectID=' + projectID + '&stories=' + encodeURIComponent(checkedList.join(',')));
    $.get(batchUnlinkStoryURL, function(data)
    {
        var jsonData = {};
        if(typeof data == 'string' && data.indexOf('{') == 0) jsonData = JSON.parse(data);

        setTimeout(function()
        {
            if(typeof jsonData.result != 'undefined')
            {
                zui.Modal.hide('#' + $('.modal.show.in').attr('id'));
                return loadCurrentPage();
            }

            $('.modal.show.in .modal-dialog').html(data);
            $('.modal.show.in .modal-dialog .modal-dialog .modal-header .modal-header').unwrap();
            $('.modal.show.in .modal-dialog .modal-dialog').unwrap();
            $('.modal.show.in .modal-dialog .modal-header').attr('class', '').attr('class', 'modal-header');
        }, 100);
    });
});
