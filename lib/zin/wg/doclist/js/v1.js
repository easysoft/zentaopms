window.removeDocs = function(event)
{
    const docID    = $(event.target).closest(".docItem").data('docID');
    const docTitle = $(event.target).closest(".docItem").find('.docTitle').text();

    /* 删掉的这一行追加到相关文档下拉组件里。 */
    items = $("#docs").zui('picker').$.state.items;
    items.push({text: docTitle, value: docID, key: docID});
    $("#docs").zui('picker').render({items});

    $(event.target).closest(".docItem").empty();
}
