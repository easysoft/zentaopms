window.getItem = function(info)
{
    const col = info.col;
    if(col.indexOf('epic') != -1 || col.indexOf('requirement') != -1 || col.indexOf('story') != -1)
    {
        info.item.title   = {html: "<div class='line-clamp-2'><span class='align-sub pri-" + info.item.pri + "'>" + langStoryPriList[info.item.pri] + "</span> <span class='title' title='" + info.item.title + "'>" + info.item.title + '</span></div>'}
        info.item.content = [];
        info.item.content.push({html: "<div class='status-" + info.item.status + "'>" + langStoryStatusList[info.item.status] + "</div>"});
        info.item.content.push({html: "<div style='color: var(--color-gray-600);'>" + langStoryStageList[info.item.stage] + '</div>'})
    }
    if(col == 'project' || col == 'execution')
    {
        let delayHtml = '';
        let titleHtml = info.item.title;
        if(info.item.delay > 0) delayHtml = "<span class='label danger-pale nowrap pull-left absolute right-0 bottom-0'>" + langProjectStatusList['delay'] + "</span>";
        if(col == 'execution') titleHtml = "<a href='" + $.createLink('execution', 'task', 'executionID=' + info.item.id) + "'>" + info.item.title + "</a>";
        info.item.title   = {html: "<div class='relative'><span class='title line-clamp-2' title='" + info.item.title + "'>" + titleHtml + '</span>' + delayHtml + '</div>'}

        info.item.content = [];
        info.item.content.push({html: "<div class='status-" + info.item.status + "'>" + langProjectStatusList[info.item.status] + "</div>"});
        info.item.content.push({component: 'ProgressCircle', props: {percent: info.item.progress, size: 24}});
    }
}

window.canDrop = function(){ return false;}
