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
        info.item.suffix      = delayed;
        info.item.suffixClass = 'label danger rounded-xl' + (info.item.status == 'doing' ? ' mr-8' : '');
    }
    if(info.item.status == 'doing') info.item.prefix = {component: 'ProgressCircle', props: {percent: info.item.progress, size: 24}};
    if(info.item.type == 'doingExecution' && privs.canViewExecution)
    {
        info.item.titleUrl = $.createLink('execution', 'task', `id=${info.item.id}`);
    }
    else if(info.item.type == 'doingProject' && privs.canViewProject)
    {
        info.item.titleUrl = $.createLink('project', 'index', `id=${info.item.id}`);
    }
    else if(info.item.type == 'unexpiredPlan' && privs.canViewPlan)
    {
        info.item.titleUrl = $.createLink('productplan', 'view', `id=${info.item.id}`);
    }
    else if(info.item.type == 'normalRelease' && privs.canViewRelease)
    {
        info.item.titleUrl = $.createLink('release', 'view', `id=${info.item.id}`);
        if(info.item.marker == '1') info.item.suffix = {html: '<i class="icon icon-flag" style="color: var(--color-danger-500)"></i>'};
    }
    info.item.titleAttrs = {'class': 'text-black clip', 'title' : info.item.title};
}
