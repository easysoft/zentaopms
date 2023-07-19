$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const $this       = $(this);
    const dtable      = zui.DTable.query($('#userList'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = new FormData();
    checkedList.forEach((id) => postData.append('users[]', id));

    postAndLoadPage($this.data('url'), postData);
});
