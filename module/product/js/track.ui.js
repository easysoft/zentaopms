window.getItem = function(info)
{
    const col = info.col;
    if(col.indexOf('epic') != -1 || col.indexOf('requirement') != -1 || col.indexOf('story') != -1)
    {
        info.item.title = {html: "<div class='line-clamp-2'><span class='align-sub pri-" + info.item.pri + "'>" + langStoryPriList[info.item.pri] + "</span> <span class='title' title='" + info.item.title + "'>" + info.item.title + '</span></div>'}
        info.item.content = {html: "<div class='flex justify-between bottom-0'><div class='status-" + info.item.status + "'>" + langStoryStatusList[info.item.status] + "</div><div style='color: var(--color-gray-600);'>" + langStoryStageList[info.item.stage] + '</div></div>'}
    }
}

window.canDrop = function(){ return false;}
