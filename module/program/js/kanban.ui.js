window.getCol = function(col)
{
    if(col.cards) col.subtitle = {html: "<span class='text-gray ml-1'>" + col.cards + "</span>"};
}

window.itemRender = function(info)
{
    info.item.className.push('card-item-' + (info.item.status == 'doing' && info.item.delay ? 'delay' : info.item.status));
}

window.getItem = function(info)
{
    if(info.item.delay)
    {
        info.item.suffix      = delayInfo.replace('%s', info.item.delay);
        info.item.suffixClass = 'label danger-pale circle size-sm nowrap' + (info.item.status == 'doing' ? ' mr-8' : '');
    }
    if(info.item.status == 'doing') info.item.prefix = {component: 'ProgressCircle', props: {percent: info.item.progress, size: 24}};
    if(info.item.cardType == 'doingExecutions' && privs.canViewExecution)
    {
        info.item.titleUrl = $.createLink('execution', 'task', `id=${info.item.id}`);
    }
    else if(['waitingProjects', 'doingProjects'].includes(info.item.cardType) && privs.canViewProject)
    {
        info.item.titleUrl = $.createLink('project', 'index', `id=${info.item.id}`);
    }
    else if(info.item.cardType == 'unexpiredPlans' && privs.canViewPlan)
    {
        info.item.titleUrl = $.createLink('productplan', 'view', `id=${info.item.id}`);
    }
    else if(info.item.cardType == 'normalReleases' && privs.canViewRelease)
    {
        info.item.titleUrl = $.createLink('release', 'view', `id=${info.item.id}`);
        if(info.item.marker == '1') info.item.suffix = {html: '<i class="icon icon-flag" style="color: var(--color-danger-500)"></i>'};
    }
    info.item.titleAttrs = {'class': 'text-black clip', 'title' : info.item.title};
}
