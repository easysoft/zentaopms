window.getItem = function(info)
{
    if(priv.canViewStory)
    {
        info.item.title      = `#${info.item.id} ${info.item.title}`;
        info.item.titleUrl   = $.createLink('execution', 'storyView', `id=${info.item.id}&execution=${executionID}`);
        info.item.titleAttrs = {'data-toggle': 'modal', 'data-size' : 'lg', 'title' : info.item.title};
    }

    const unlinkUrl = $.createLink('execution', 'unlinkStory', `executionID=${executionID}&story=${info.item.id}`);
    const unlinkBtn = priv.canUnlinkStory ? `<a href="${unlinkUrl}" title=${unlinkLang} class="btn item text-primary toolbar-item ajax-submit square size-sm ghost"><i class='icon icon-unlink'></i></a>` : '';
    const content = `
        <div class='flex items-center'>
          <span class='pri-${info.item.pri}'>${info.item.pri}</span>
          <span class='status-${info.item.status} ml-1'>${info.item.statusLabel}</span>
          <div class='flex-1 flex justify-end items-center'>
            <span class='text-sm ml-2 mr-1'>${info.item.estimate + hourUnit}</span>
            <span>${unlinkBtn}</span>
          </div>
        </div>
        `;
    info.item.content = {html: content};
};

window.canDrop = function(dragInfo, dropInfo){
    if(!dragInfo || !priv.canBatchChangeStage) return false;

    const toCol = this.getCol(dropInfo.col);
    if(toCol.key != 'verified') return false;
};

window.onDrop = function(changes){
    const url  = $.createLink('story', 'batchChangeStage', `stage=verified&story=${changes.items[0].id}`);
    const form = new FormData();
    const id   = changes?.items[0]?.id;
    form.append('storyIdList[]', id);
    $.ajaxSubmit({
        url,
        data: form
    });
};
