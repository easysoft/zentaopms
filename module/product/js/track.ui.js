window.getItem = function(info)
{
    const col   = info.col;
    const color = info.item.color ? " style='color:" + info.item.color + "'" : '';
    const title = info.item.title;

    let titleHtml = `<span${color}>${title}</span>`;
    info.item.content = [];
    if(col.indexOf('epic') != -1 || col.indexOf('requirement') != -1 || col.indexOf('story') != -1)
    {
        let storyPriList    = langStoryPriList[info.item.storyType];
        let storyStatusList = langStoryStatusList[info.item.storyType];
        let storyStageList  = langStoryStageList[info.item.storyType];

        if(privs[info.item.storyType]) titleHtml = "<a href='" + $.createLink(info.item.storyType, 'view', `storyID=${info.item.id}`) + "' data-toggle='modal' data-size='lg'" + color + ">" + title + "</a>";
        info.item.title      = {html: `<div class="line-clamp-2"><span class="align-sub pri-${info.item.pri}">${storyPriList[info.item.pri]}</span> ${titleHtml}</div>`}
        info.item.titleAttrs = {'title' : title};

        info.item.content.push({html: `<div class="status-${info.item.status}">${storyStatusList[info.item.status]}</div>`});
        info.item.content.push({html: `<div style="color:var(--color-gray-600)">${storyStageList[info.item.stage]}</div>`})
    }
    else if(col == 'project' || col == 'execution')
    {
        let delayHtml = '';
        if(info.item.delay > 0) delayHtml = `<span class='label danger-pale delayed nowrap'>${langProjectStatusList['delay']}</span>`;

        titleHtml = `<span${color} class="title">${title}</span>`;
        if(col == 'project'   && privs['project'])   titleHtml = "<a class='title' href='" + $.createLink('project', 'view', `projectID=${info.item.id}`) + "'>" + title + "</a>";
        if(col == 'execution' && privs['execution']) titleHtml = "<a class='title' href='" + $.createLink('execution', 'task', `executionID=${info.item.id}`) + "'>" + title + "</a>";
        info.item.title      = {html: `<div class="relative"><div class="line-clamp-2">${titleHtml}${delayHtml}</div></div>`}
        info.item.titleAttrs = {'title' : title};

        info.item.content.push({html: `<div class="status-${info.item.status}">${langProjectStatusList[info.item.status]}</div>`});
        info.item.content.push({component: 'ProgressCircle', props: {percent: info.item.progress, size: 24}});
    }
    else if(col == 'task')
    {
        if(privs['task']) titleHtml = "<a href='" + $.createLink('task', 'view', `taskID=${info.item.id}`) + "' data-toggle='modal' data-size='lg'" + color + ">" + title + "</a>";
        info.item.title = {html: `<div class='line-clamp-2'><span class="align-sub pri-${info.item.pri}">${langTaskPriList[info.item.pri]}</span> ${titleHtml}</div>`}
        info.item.titleAttrs = {'title' : title};

        if(info.item.parent == '-1') info.item.content.push({html: `<span class="label cursor-pointer primary rounded-xl is-collapsed" onclick="toggleChildren(this, ${info.item.id})">${langChildren} <span class="toggle-icon ml-1"></span></span>`});
        info.item.content.push({html: `<div class="status-${info.item.status}">${langTaskStatusList[info.item.status]}</div>`});
        if(info.item.assignedTo) info.item.content.push({html: "<i class='icon icon-hand-right'></i> " + (users[info.item.assignedTo] ? users[info.item.assignedTo] : info.item.assignedTo)});
        info.item.content.push({component: 'ProgressCircle', props: {percent: info.item.progress, size: 24}});
    }
    else if(col == 'bug')
    {
        if(privs['bug']) titleHtml = "<a href='" + $.createLink('bug', 'view', `bugID=${info.item.id}`) + "' data-toggle='modal' data-size='lg'" + color + ">" + title + "</a>";
        info.item.title      = {html: `<div class="line-clamp-2"><span class="align-sub pri-${info.item.pri}">${langBugPriList[info.item.pri]}</span> ${titleHtml}</div>`}
        info.item.titleAttrs = {'title' : title};

        severity     = info.item.severity;
        severityHtml = `<div class="severity" data-severity="${severity}"></div>`;
        if(!langBugSeverityList[severity] || langBugSeverityList[severity] != severity) severityHtml = `<div class="severity">${severity}</div>`;

        info.item.content.push({html: severityHtml});
        if(info.item.assignedTo) info.item.content.push({html: "<i class='icon icon-hand-right'></i> " + (users[info.item.assignedTo] ? users[info.item.assignedTo] : info.item.assignedTo)});
    }
    else if(col == 'case')
    {
        if(privs['case']) titleHtml = "<a href='" + $.createLink('testcase', 'view', `caseID=${info.item.id}`) + "' data-toggle='modal' data-size='lg'" + color + ">" + title + "</a>";
        info.item.title      = {html: `<div class="line-clamp-2"><span class="align-sub pri-${info.item.pri}">${langCasePriList[info.item.pri]}</span> ${titleHtml}</div>`}
        info.item.titleAttrs = {'title' : title};

        info.item.content.push({html: "<div class='status-" + info.item.lastRunResult + "'>" + (langCaseResultList[info.item.lastRunResult] ? langCaseResultList[info.item.lastRunResult] : langUnexecuted) + "</div>"});
        info.item.content.push({html: (users[info.item.lastRunner] ? users[info.item.lastRunner] : info.item.lastRunner)});
    }
    else if(col == 'design')
    {
        info.item.titleAttrs = {'class': 'line-clamp-3', 'title' : title};
        if(privs['design'])
        {
            info.item.titleUrl = $.createLink('design', 'view', `id=${info.item.id}`);
            info.item.titleAttrs = {'class': 'line-clamp-3', 'title' : title, 'data-toggle': 'modal', 'data-size': 'lg'};
        }
    }
    else if(col == 'commit')
    {
        if(privs['commit']) info.item.titleUrl = $.createLink('repo', 'revision', `repoID=${info.item.repo}&objectID=0&revision=${info.item.revision}`);
        info.item.titleAttrs = {'class': 'line-clamp-3', 'title' : title};
    }
}

window.getLaneCol = function(lane, col)
{
    if(mergeCells[lane.name])
    {
        if(mergeCells[lane.name][col.name]) return {laneColClass: 'lane-col-join-with-above'};
        return {laneColClass: 'lane-col-shrink-with-above'};
    }
}

window.getCol = function(col)
{
    if(col.name == storyType) col.subtitle = {html: `<span class="icon ml-1 cursor-pointer orderByIcon icon-swap" title="${orderByTitle}"></span>`};
}

window.itemRender = function(info)
{
    const col = info.col;
    if(col == 'task')
    {
        if(info.item.parent > '0') info.item.className.push('hidden childTask parent-' + info.item.parent);
        if(info.item.parent == '-1') info.item.className.push('parentTask');
    }
    if(col == 'project' || col == 'execution')
    {
        $delayed = $('.kanban-lane-col[z-lane="' + info.lane + '"][z-col="' + col + '"] .kanban-item[z-key="' + info.item.id + '"] .delayed');
        if($delayed.length > 0)
        {
            let $relative = $delayed.closest('.relative');
            if($relative.find('.line-clamp-2').height() < $relative.find('.title').height()) $delayed.addClass('absolute bottom-0 right-0');
        }
    }

    if(config.rawModule == 'projectstory' && (col.indexOf('epic') != -1 || col.indexOf('requirement') != -1 || col.indexOf('story') != -1))
    {
        if(!storyIdList.includes(parseInt(info.item.id))) info.item.className.push('hidden');
    }
}

window.afterRender = function()
{
    $orderByIcon = $('.orderByIcon');
    if($orderByIcon.length > 0 && !$orderByIcon.hasClass('dropdownInited'))
    {
        new zui.Dropdown($('.orderByIcon'), {menu: {items: orderByItems}});
        $orderByIcon.addClass('dropdownInited');
    }
}

window.toggleChildren = function(obj, parentID)
{
    if($(obj).hasClass('is-expanded'))
    {
        $(obj).removeClass('is-expanded').addClass('is-collapsed');
        $('.parent-' + parentID).addClass('hidden')
    }
    else
    {
        $(obj).removeClass('is-collapsed').addClass('is-expanded');
        $('.parent-' + parentID).removeClass('hidden')
    }
}

window.changeProduct = function(e)
{
    if(config.rawModule == 'projectstory') loadPage($.createLink(config.rawModule, config.rawMethod, "projectID=" + projectID + "&productID=" + $(e.target).val()));
}
