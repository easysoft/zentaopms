/**
 * Get story demo kanban data
 * 获取“软件需求”看板演示数据
 * @returns {Object} Kanban data
 * @todo 该方法作为演示，应该在最终版本中移除
 */
function getStoryKanbanDemoData()
{
    /* Define kanban columns  定义看板列 */
    var columns =
    [
        /* 看板列定义数据结构：
           id:         列 ID（确保在页面上唯一）,
           type:       类型,
           name:       显示的名称,
           color:      列名称颜色,
           parentType: 所属的父级列类型
           asParent:   是否作为父级列
           cardType:   看板卡片类型，例如：'story'（需求）
           limit:      最大数目，该列上允许展示的卡片最大数目，如果为 0 表示无限制 */
        {id: 'story-blacklog',   type: 'blacklog',   name: 'Blacklog', color: '', limit: 0},
        {id: 'story-ready',      type: 'ready',      name: '准备好',    color: '', limit: 4},
        {id: 'story-dev',        type: 'dev',        name: '开发',      color: '', limit: 4, asParent: true},
        {id: 'story-dev-doing',  type: 'dev-doing',  name: '进行中',    color: '#126eed', limit: 2, parentType: 'dev'},
        {id: 'story-dev-done',   type: 'dev-done',   name: '完成',      color: '#2fab8e', limit: 2, parentType: 'dev'},
        {id: 'story-test',        type: 'test',       name: '测试',     color: '', limit: 4, asParent: true},
        {id: 'story-test-doing', type: 'test-doing', name: '进行中',    color: '#126eed', limit: 2, parentType: 'test'},
        {id: 'story-test-done',  type: 'test-done',  name: '完成',      color: '#2fab8e', limit: 2, parentType: 'test'},
        {id: 'story-accepted',   type: 'accepted',   name: '已验收',    color: '', limit: 0},
        {id: 'story-published',  type: 'published',  name: '已发布',    color: '', limit: 0},
    ];

    /* Define cards in blacklog column  定义 Blacklog 列上的卡片 */
    var blacklogColCards =
    [
        /* 需求数据结构：
           id:          ID，通常为需求 ID,
           title:        名称,
           order:       显示顺序，如果指定为 0，则按 ID 倒序排序,
           pri:         优先级,
           estimate:    预估时间,
           assignedTo:  指派给,
           deadline:    截止日期 */
        {id: 10010, title: '第一条 Blacklog',     order: 1, pri: 1, estimate: 2,  assignedTo: 'admin', deadline: '2021-10-30'},
        {id: 10011, title: '文档模块的实现',        order: 2, pri: 2,  estimate: 0,  assignedTo: 'sunhao'},
        {id: 10012, title: '实现软件需求看板视图中不同分组条件的看板展示方式', order: 3, pri: 3, estimate: 10, assignedTo: 'admin'},
        {id: 10013, title: 'Blacklog 3',         order: 4, pri: 0, estimate: 0,  assignedTo: 'sunhao'},
        {id: 10014, title: '实现泳道的更多操作菜单', order: 5, pri: 0, estimate: 0,  assignedTo: 'sunhao'},
    ];
    /* Define cards in ready column  定义 准备好 列上的卡片 */
    var readyColCards =
    [
        /* 需求数据结构参见 blacklogColCards 定义 */
        {id: 10020, title: '在看板列中实现创建任务功能', order: 10, pri: 1, estimate: 2,  assignedTo: 'admin', deadline: '2021-10-30'},
        {id: 10021, title: '实现泳道的上下移动',       order: 11, pri: 2,  estimate: 0,  assignedTo: 'sunhao'},
        {id: 10024, title: '实现泳道的更多操作菜单',    order: 15, pri: 0, estimate: 0,  assignedTo: 'sunhao'},
    ];
    var devDoingColCards = /* Define cards in dev/doing column  定义 开发/进行中 列上的卡片 */
    [
        /* 需求数据结构参见 blacklogColCards 定义 */
        {id: 10030, title: '实现看板的更多操作菜单', order: 10, pri: 3, estimate: 0,  assignedTo: 'admin', deadline: '2021-10-30'},
        {id: 10031, title: '实现卡片拖动效果功能',  order: 11, pri: 2,  estimate: 2,  assignedTo: 'sunhao'},
    ];
    var devDoneColCards = /* Define cards in dev/done column  定义 开发/完成 列上的卡片 */
    [
        /* 需求数据结构参见 blacklogColCards 定义 */
        {id: 10040, title: '实现看板列卡片排序功能',  order: 10, pri: 1, estimate: 4,  assignedTo: 'admin', deadline: '2021-10-30'},
    ];
    var testDoingColCards = /* Define cards in test/doing column  定义 测试/进行中 列上的卡片 */
    [
        /* 需求数据结构参见 blacklogColCards 定义 */
        {id: 10050, title: '实现看板列的更多操作菜单',  order: 10, pri: 3, estimate: 0,  assignedTo: 'admin', deadline: '2021-10-30'},
        {id: 10051, title: '实现Bug卡片的更多操作功能', order: 11, pri: 2,  estimate: 2,  assignedTo: 'sunhao'},
    ];
    var testDoneColCards = /* Define cards in test/done column  定义 测试/完成 列上的卡片 */
    [
        /* 需求数据结构参见 blacklogColCards 定义 */
        {id: 10060, title: '实现任务卡片的更多操作功能', order: 10, pri: 1, estimate: 4,  assignedTo: 'admin', deadline: '2021-10-30'},
    ];
    var acceptedColCards =
    [
        /* 需求数据结构参见 blacklogColCards 定义 */
        {id: 10070, title: '实现执行看板的新建按钮组功能',  order: 10, pri: 3, estimate: 12,  assignedTo: 'admin', deadline: '2021-10-30'},
        {id: 10071, title: '在看板列中实现提交Bug功能', order: 11, pri: 2,  estimate: 2,  assignedTo: 'sunhao'},
        {id: 10072, title: '在看板列中实现需求的添加和关联功能',  order: 10, pri: 3, estimate: 10,  assignedTo: 'admin', deadline: '2021-10-30'},
        {id: 10073, title: '实现任务看板视图中不同分组条件的看板展示方式', order: 11, pri: 2,  estimate: 2,  assignedTo: 'sunhao'},
    ];
    var publishedColCards =
    [
        /* 需求数据结构参见 blacklogColCards 定义 */
        {id: 10080, title: '实现任务看板视图中泳道分组下拉菜单功能',  order: 10, pri: 3, estimate: 2,  assignedTo: 'admin'},
    ];

    /* Define kanban cards in lane  定义看板泳道内每一列的卡片，属性名为看板列类型，属性值为卡片列表 */
    var cards =
    {
        /* 看板列类型:  卡片列表 */
        blacklog:     blacklogColCards,
        ready:        readyColCards,
        'dev-doing':  devDoingColCards,
        'dev-done':   devDoneColCards,
        'test-doing': testDoingColCards,
        'test-done':  testDoneColCards,
        accepted:     acceptedColCards,
        published:    publishedColCards,
    };

    /* Define kanban lanes  定义看板泳道 */
    var lanes =
    [
        /* 泳道数据结构：
           id:              泳道 ID，确保页面上唯一，可以为数字或字符串,
           name:            名称,
           cards:           定义每列上的卡片
           color:           泳道名称背景色
           defaultCardType: 看板泳道上的卡片默认类型，例如：'story'（需求） */
        {id: 'story', name: '软件需求', cards: cards, color: '#3dc6fc', defaultCardType: 'story'},
    ]

    /* Return kanban data  返回看板数据 */
    /* 看板数据结构：
       id:       ID，确保页面上唯一，可以为数字或字符串,
       columns:  定义看板上的所有列,
       lanes:    定义看板上的所有泳道
       defaultCardType: 看板上的卡片默认类型，例如：'story'（需求） */
    return kanbanGroup.story;
    return {id: 'story', columns: columns, lanes: lanes, defaultCardType: 'story'};
}

/**
 * Get bug demo kanban data
 * 获取“Bug”看板演示数据
 * @returns {Object} Kanban data
 * @todo 该方法作为演示，应该在最终版本中移除
 */
function getBugKanbanDemoData()
{
    return kanbanGroup.bug;
    /* Define kanban columns  定义看板列 */
    var columns =
    [
        /* 看板列定义数据结构：
        id:         列 ID（确保在页面上唯一）,
        type:       类型,
        name:       显示的名称,
        color:      列名称颜色,
        parentType: 所属的父级列类型
        asParent:   是否作为父级列
        cardType:   看板卡片类型，例如：'story'（需求）
        limit:   最大数目，该列上允许展示的卡片最大数目，如果为 0 表示无限制 */
        {id: 'bug-wait',            type: 'wait',            name: '待确认',  color: '', limit: 0},
        {id: 'bug-confirmed',       type: 'confirmed',      name: '已确认',  color: '', limit: 4},
        {id: 'bug-resolving',       type: 'resolving',      name: '解决中',  color: '', limit: 4, asParent: true},
        {id: 'bug-resolving-doing', type: 'resolving-doing',name: '进行中',  color: '#126eed', limit: 2, parentType: 'resolving'},
        {id: 'bug-resolving-done',  type: 'resolving-done', name: '完成',    color: '#2fab8e', limit: 2, parentType: 'resolving'},
        {id: 'bug-test',            type: 'test',           name: '测试',    color: '', limit: 4, asParent: true},
        {id: 'bug-test-doing',      type: 'test-doing',     name: '测试中',  color: '#126eed', limit: 2, parentType: 'test'},
        {id: 'bug-test-done',       type: 'test-done',      name: '测试完毕', color: '#2fab8e', limit: 2, parentType: 'test'},
        {id: 'bug-closed',          type: 'closed',         name: '已关闭',   color: '', limit: 0},
    ];

    /* Define cards in wait column  定义 待确认 列上的卡片 */
    var waitColCards =
    [
        /* bug 数据结构：
        id:          ID，通常为 Bug ID,
        title:       名称,
        order:       显示顺序，如果指定为 0，则按 ID 倒序排序,
        pri:         优先级,
        severity:    紧急程度,
        assignedTo:  指派给 */
        {id: 10010, title: '在看板列中实现创建任务功能', order: 10, pri: 1, severity: 2,  assignedTo: 'admin'},
        {id: 10011, title: '实现泳道的上下移动',       order: 11, pri: 2,  severity: 1,  assignedTo: 'sunhao'},
        {id: 10014, title: '实现泳道的更多操作菜单',    order: 15, pri: 0, severity: 1,  assignedTo: 'sunhao'},
    ];
    /* Define cards in confirmed column  定义 已确认 列上的卡片 */
    var confirmedColCards =
    [
        /* Bug 数据结构参见 waitColCards 定义 */
        {id: 10020, title: '在看板列中实现创建任务功能', order: 10, pri: 1, severity: 2,  assignedTo: 'admin'},
        {id: 10021, title: '实现泳道的上下移动',       order: 11, pri: 2,  severity: 1,  assignedTo: 'sunhao'},
        {id: 10024, title: '实现泳道的更多操作菜单',    order: 15, pri: 0, severity: 1,  assignedTo: 'sunhao'},
    ];
    var resolvingDoingColCards = /* Define cards in resolving/doing column  定义 开发/进行中 列上的卡片 */
    [
        /* Bug 数据结构参见 waitColCards 定义 */
        {id: 10030, title: '实现看板的更多操作菜单', order: 10, pri: 3, severity: 1,  assignedTo: 'admin'},
        {id: 10031, title: '实现卡片拖动效果功能',  order: 11, pri: 2,  severity: 2,  assignedTo: 'sunhao'},
    ];
    var resolvingDoneColCards = /* Define cards in resolving/done column  定义 开发/完成 列上的卡片 */
    [
        /* Bug 数据结构参见 waitColCards 定义 */
        {id: 10040, title: '实现看板列卡片排序功能',  order: 10, pri: 1, severity: 4,  assignedTo: 'admin'},
    ];
    var testDoingColCards = /* Define cards in test/doing column  定义 测试/进行中 列上的卡片 */
    [
        /* Bug 数据结构参见 waitColCards 定义 */
        {id: 10050, title: '实现看板列的更多操作菜单',  order: 10, pri: 3, severity: 3,  assignedTo: 'admin'},
        {id: 10051, title: '实现Bug卡片的更多操作功能', order: 11, pri: 2,  severity: 2,  assignedTo: 'sunhao'},
    ];
    var testDoneColCards = /* Define cards in test/done column  定义 测试/完成 列上的卡片 */
    [
        /* Bug 数据结构参见 waitColCards 定义 */
        {id: 10060, title: '实现任务卡片的更多操作功能', order: 10, pri: 1, severity: 4,  assignedTo: 'admin'},
    ];
    var closedColCards =
    [
        /* Bug 数据结构参见 waitColCards 定义 */
        {id: 10070, title: '实现执行看板的新建按钮组功能',  order: 10, pri: 3, severity: 3,  assignedTo: 'admin'},
        {id: 10071, title: '在看板列中实现提交Bug功能', order: 11, pri: 2,  severity: 2,  assignedTo: 'sunhao'},
        {id: 10072, title: '在看板列中实现需求的添加和关联功能',  order: 10, pri: 3, severity: 3,  assignedTo: 'admin'},
        {id: 10073, title: '实现任务看板视图中不同分组条件的看板展示方式', order: 11, pri: 2,  severity: 2,  assignedTo: 'sunhao'},
    ];

    /* Define kanban cards in lane  定义看板泳道内每一列的卡片，属性名为看板列类型，属性值为卡片列表 */
    var cards =
    {
        /* 看板列类型:  卡片列表 */
        wait:              waitColCards,
        confirmed:         confirmedColCards,
        'resolving-doing': resolvingDoingColCards,
        'resolving-done':  resolvingDoneColCards,
        'test-doing':      testDoingColCards,
        'test-done':       testDoneColCards,
        closed:            closedColCards,
    };

    /* Define kanban lanes  定义看板泳道 */
    var lanes =
    [
        /* 泳道数据结构：
        id:              泳道 ID，确保页面上唯一，可以为数字或字符串,
        name:            名称,
        cards:           定义每列上的卡片
        color:           泳道名称背景色
        defaultCardType: 看板泳道上的卡片默认类型，例如：'bug'（Bug） */
        {id: 'bug', name: 'Bug', cards: cards, color: '#9c30b0', defaultCardType: 'bug'},
    ]

    /* Return kanban data  返回看板数据 */
    /* 看板数据结构：
    id:       ID，确保页面上唯一，可以为数字或字符串,
    columns:  定义看板上的所有列,
    lanes:    定义看板上的所有泳道
    defaultCardType: 看板上的卡片默认类型，例如：'bug'（Bug） */
    return {id: 'bug', columns: columns, lanes: lanes, defaultCardType: 'bug'};
}

/**
 * Get task demo kanban data
 * 获取“任务”看板演示数据
 * @returns {Object} Kanban data
 * @todo 该方法作为演示，应该在最终版本中移除
 */
function getTaskKanbanDemoData()
{
    return kanbanGroup.task;
    /* Define kanban columns  定义看板列 */
    var columns =
    [
        /* 看板列定义数据结构：
        id:         列 ID（确保在页面上唯一）,
        type:       类型,
        name:       显示的名称,
        color:      列名称颜色,
        parentType: 所属的父级列类型
        asParent:   是否作为父级列
        cardType:   看板卡片类型，例如：'task'（任务）
        limit:   最大数目，该列上允许展示的卡片最大数目，如果为 0 表示无限制 */
        {id: 'task-wait',      type: 'wait',      name: '未开始',   color: '', limit: 0},
        {id: 'task-dev',       type: 'dev',       name: '开发',     color: '', limit: 4, asParent: true},
        {id: 'task-dev-doing', type: 'dev-doing', name: '研发中',   color: '#126eed', limit: 2, parentType: 'dev'},
        {id: 'task-dev-done',  type: 'dev-done',  name: '研发完成', color: '#2fab8e', limit: 2, parentType: 'dev'},
        {id: 'task-pause',     type: 'pause',     name: '已暂停',   color: '', limit: 0},
        {id: 'task-cancel',    type: 'cancel',    name: '已取消',   color: '', limit: 0},
        {id: 'task-closed',    type: 'closed',    name: '已关闭',   color: '', limit: 0},
    ];

    /* Define cards in wait column  定义 未开始 列上的卡片 */
    var waitColCards =
    [
        /* 任务数据结构：
        id:          ID，通常为任务 ID,
        name:       名称,
        order:       显示顺序，如果指定为 0，则按 ID 倒序排序,
        pri:         优先级,
        estimate:    预估时间,
        assignedTo:  指派给,
        deadline:    截止日期 */
        {id: 10020, name: '在看板列中实现创建任务功能', order: 10, pri: 1, estimate: 2,  assignedTo: 'admin', deadline: '2021-10-30'},
        {id: 10021, name: '实现泳道的上下移动',       order: 11, pri: 2,  estimate: 0,  assignedTo: 'sunhao', deadline: '2021-10-22'},
        {id: 10024, name: '实现泳道的更多操作菜单',    order: 15, pri: 0, estimate: 0,  assignedTo: 'sunhao'},
    ];
    var devDoingColCards = /* Define cards in dev/doing column  定义 开发/进行中 列上的卡片 */
    [
        /* 任务数据结构参见 waitColCards 定义 */
        {id: 10030, name: '实现看板的更多操作菜单', order: 10, pri: 3, estimate: 0,  assignedTo: 'admin', deadline: '2021-10-30'},
        {id: 10031, name: '实现卡片拖动效果功能',  order: 11, pri: 2,  estimate: 2,  assignedTo: 'sunhao'},
    ];
    var devDoneColCards = /* Define cards in dev/done column  定义 开发/完成 列上的卡片 */
    [
        /* 任务数据结构参见 waitColCards 定义 */
        {id: 10040, name: '实现看板列卡片排序功能',  order: 10, pri: 1, estimate: 4,  assignedTo: 'admin', deadline: '2021-10-30'},
    ];
    var pauseColCards = /* Define cards in pause column  定义 已暂停 列上的卡片 */
    [
        /* 任务数据结构参见 waitColCards 定义 */
        {id: 10050, name: '实现看板列的更多操作菜单',  order: 10, pri: 3, estimate: 0,  assignedTo: 'admin', deadline: '2021-10-30'},
        {id: 10051, name: '实现Bug卡片的更多操作功能', order: 11, pri: 2,  estimate: 2,  assignedTo: 'sunhao'},
    ];
    var cancelColCards = /* Define cards in cancel column  定义 已取消 列上的卡片 */
    [
        /* 任务数据结构参见 waitColCards 定义 */
        {id: 10060, name: '实现任务卡片的更多操作功能', order: 10, pri: 1, estimate: 4,  assignedTo: 'admin', deadline: '2021-10-30'},
    ];
    var closedColCards =
    [
        /* 任务数据结构参见 waitColCards 定义 */
        {id: 10070, name: '实现执行看板的新建按钮组功能',  order: 10, pri: 3, estimate: 12,  assignedTo: 'admin', deadline: '2021-10-30'},
        {id: 10071, name: '在看板列中实现提交Bug功能', order: 11, pri: 2,  estimate: 2,  assignedTo: 'sunhao'},
        {id: 10072, name: '在看板列中实现任务的添加和关联功能',  order: 10, pri: 3, estimate: 10,  assignedTo: 'admin', deadline: '2021-10-30'},
        {id: 10073, name: '实现任务看板视图中不同分组条件的看板展示方式', order: 11, pri: 2,  estimate: 2,  assignedTo: 'sunhao'},
    ];

    /* Define kanban cards in lane  定义看板泳道内每一列的卡片，属性名为看板列类型，属性值为卡片列表 */
    var cards =
    {
        /* 看板列类型:  卡片列表 */
        wait:        waitColCards,
        'dev-doing': devDoingColCards,
        'dev-done':  devDoneColCards,
        pause:       pauseColCards,
        cancel:      cancelColCards,
        closed:      closedColCards,
    };

    /* Define kanban lanes  定义看板泳道 */
    var lanes =
    [
        /* 泳道数据结构：
        id:              泳道 ID，确保页面上唯一，可以为数字或字符串,
        name:            名称,
        cards:           定义每列上的卡片
        color:           泳道名称背景色
        defaultCardType: 看板泳道上的卡片默认类型，例如：'task'（任务） */
        {id: 'task', name: '任务', cards: cards, color: '#126eed', defaultCardType: 'task'},
    ]

    /* Return kanban data  返回看板数据 */
    /* 看板数据结构：
    id:       ID，确保页面上唯一，可以为数字或字符串,
    columns:  定义看板上的所有列,
    lanes:    定义看板上的所有泳道
    defaultCardType: 看板上的卡片默认类型，例如：'task'（任务） */
    return {id: 'task', columns: columns, lanes: lanes, defaultCardType: 'task'};
}


/**
 * Render user avatar
 * @param {String|{account: string, avatar: string}} user User account or user object
 * @returns {string}
 */
function renderUserAvatar(user)
{
    if(typeof user === 'string') user = {account: user};
    if(!user.avatar && window.userList && window.userList[user.account]) user = window.userList[user.account];
    return $('<div class="avatar has-text avatar-sm avatar-circle" />').avatar({user: user});
}

/**
 * Render story item  提供方法渲染看板中的需求卡片
 * @param {Object} item  Story item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderStoryItem(item, $item, col)
{
    var $title = $item.find('.title');
    if(!$title.length)
    {
        $title = $('<a class="title iframe"><i class="icon icon-lightbulb text-muted"></i> <span class="text"></span></a>')
                .attr('href', $.createLink('story', 'view', 'storyID=' + item.id));
        $title.appendTo($item);
    }
    $title.attr('title', item.title).find('.text').text(item.title);

    var $infos = $item.find('.infos');
    if(!$infos.length)
    {
        $infos = $('<div class="infos"></div>').appendTo($item);
    }
    $infos.html(
    [
        '<span class="info info-id text-muted">#' + item.id + '</span>',
        '<span class="info info-pri label-pri label-pri-' + item.pri + '" title="' + item.pri + '">' + item.pri + '</span>',
        item.estimate ? '<span class="info info-estimate text-muted">' + item.estimate + 'h</span>' : '',
    ].join(''));
    if(item.assignedTo) $infos.append(renderUserAvatar(item.assignedTo));

    $item.attr('data-type', 'story').addClass('kanban-item-story');

    return $item;
}


/**
 * Render bug item  提供方法渲染看板中的 Bug 卡片
 * @param {Object} item  Bug item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderBugItem(item, $item, col)
{
    var $title = $item.find('.title');
    if(!$title.length)
    {
        $title = $('<a class="title iframe"><i class="icon icon-bug text-muted"></i> <span class="text"></span></a>')
                .attr('href', $.createLink('bug', 'view', 'bugID=' + item.id));
        $title.appendTo($item);
    }
    $title.attr('title', item.title).find('.text').text(item.title);

    var $infos = $item.find('.infos');
    if(!$infos.length)
    {
        $infos = $('<div class="infos"></div>').appendTo($item);
    }
    $infos.html(
    [
        '<span class="info info-id text-muted">#' + item.id + '</span>',
        '<span class="info info-severity label-severity" data-severity="' + item.severity + '" title="' + item.severity + '"></span>',
        '<span class="info info-pri label-pri label-pri-' + item.pri + '" title="' + item.pri + '">' + item.pri + '</span>',
    ].join(''));
    if(item.assignedTo) $infos.append(renderUserAvatar(item.assignedTo));

    $item.attr('data-type', 'bug').addClass('kanban-item-bug');

    return $item;
}

/**
 * Render task item  提供方法渲染看板中的任务卡片
 * @param {Object} item  Task item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderTaskItem(item, $item, col)
{
    var $title = $item.find('.title');
    if(!$title.length)
    {
        $title = $('<a class="title iframe"><i class="icon icon-checked text-muted"></i> <span class="text"></span></a>')
                .attr('href', $.createLink('task', 'view', 'taskID=' + item.id));
        $title.appendTo($item);
    }
    $title.attr('title', item.name).find('.text').text(item.name);

    var $infos = $item.find('.infos');
    if(!$infos.length)
    {
        $infos = $('<div class="infos"></div>').appendTo($item);
    }
    $infos.html(
    [
        '<span class="info info-id text-muted">#' + item.id + '</span>',
        '<span class="info i nfo-pri label-pri label-pri-' + item.pri + '" title="' + item.pri + '">' + item.pri + '</span>',
        item.estimate ? '<span class="info info-estimate text-muted">' + item.estimate + 'h</span>' : '',
    ].join(''));
    if(item.assignedTo) $infos.append(renderUserAvatar(item.assignedTo));

    $item.attr('data-type', 'task').addClass('kanban-item-task');

    return $item;
}

/* Add column renderer/  添加特定列类型或列条目类型渲染方法 */
/* Add column renderer/  添加特定列类型或列卡片类型渲染方法 */
addColumnRenderer('story', renderStoryItem);
addColumnRenderer('bug',   renderBugItem);
addColumnRenderer('task',  renderTaskItem);

/**
 * Render column count 渲染看板列头上的卡片数目
 * @param {JQuery} $count Kanban count element
 * @param {number} count  Column cards count
 * @param {number} col    Column object
 * @param {Object} kanban Kanban intance
 */
function renderColumnCount($count, count, col)
{
    var text = count + '/' + (!col.limit ? '<i class="icon icon-infinite"></i>' : '');
    $count.html(text + '<i class="icon icon-arrow-up"></i>');
}

/**
 * Updata kanban data
 * 更新看板上的数据
 * @param {string} kanbanID Kanban id   看板 ID
 * @param {Object} data     Kanban data 看板数据
 */
function updateKanban(kanbanID, data)
{
    var $kanban = $('#kanban-' + kanbanID);
    if(!$kanban.length) return;

    $kanban.data('zui.kanban').render(data);
}

/**
 * Create kanban in page
 * 在界面上创建一个看板界面
 * @param {string} kanbanID Kanban id      看板 ID
 * @param {Object} data     Kanban data    看板数据
 * @param {Object} options  Kanban options 组件初始化数据 看板名称
 */
function createKanban(kanbanID, data, options)
{
    var $kanban = $('#kanban-' + kanbanID);
    if($kanban.length) return updateKanban(kanbanID, data);

    $kanban = $('<div id="kanban-' + kanbanID + '" data-id="' + kanbanID + '"></div>').appendTo('#kanbans');
    $kanban.kanban($.extend({data: data}, options));
}

function fullScreen()
{
    var element = document.getElementById('kanbanContainer');
    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullScreen;
    if(requestMethod)
    {
        var afterEnterFullscreen = function()
        {
            $('#kanbanContainer').addClass('scrollbar-hover');
            $.cookie('isFullScreen', 1);
        }

        var whenFailEnterFullscreen = function()
        {
            $.cookie('isFullScreen', 0);
        }

        try
        {
            var result = requestMethod.call(element);
            if(result && (typeof result.then === 'function' || result instanceof window.Promise))
            {
                result.then(afterEnterFullscreen).catch(whenFailEnterFullscreen);
            }
            else
            {
                afterEnterFullscreen();
            }
        }
        catch (error)
        {
            whenFailEnterFullscreen(error);
        }
    }
}

/**
 * Exit full screen.
 *
 * @access public
 * @return void
 */
function exitFullScreen()
{
    $('#kanbanContainer').removeClass('scrollbar-hover');
    $('#content .actions').removeClass('hidden');
    $.cookie('isFullScreen', 0);
}

/* Define drag and drop rules */
if(!window.kanbanDropRules)
{
    window.kanbanDropRules =
    {
        story:
        {
            blacklog: true,
            ready: ['blacklog', 'dev-doing'],
            'dev-doing': ['dev-done'],
            'dev-done': ['test-doing'],
            'test-doing': ['test-done'],
            'test-done': ['accepted'],
            'accepted': ['published'],
            'published': false,
        }
    }
}

/*
 * Find drop columns
 * @param {JQuery} $element Drag element
 * @param {JQuery} $root Dnd root element
 */
function findDropColumns($element, $root)
{
    var $col        = $element.closest('.kanban-col');
    var col         = $col.data();
    var kanbanID    = $root.data('id');
    var kanbanRules = window.kanbanDropRules ? window.kanbanDropRules[kanbanID] : null;

    if(!kanbanRules) return $root.find('.kanban-lane-col:not([data-type="' + col.type + '"])');

    var colRules = kanbanRules[col.type];
    var lane     = $col.closest('.kanban-lane').data('lane');
    return $root.find('.kanban-lane-col').filter(function()
    {
        if(!colRules) return false;
        if(colRules === true) return true;

        var $newCol = $(this);
        var newCol = $newCol.data();
        if(newCol.id === col.id) return false;

        var $newLane = $newCol.closest('.kanban-lane');
        var newLane = $newLane.data('lane');
        var canDropHere = colRules.indexOf(newCol.type) > -1 && newLane.id === lane.id;
        if(canDropHere) $newCol.addClass('can-drop-here');
        return canDropHere;
    });
}

/**
 * Handle finish drop task
 * @param {Object} event Event object
 * @returns {void}
 */
function handleFinishDrop(event)
{
    var $item = $(event.element); // The drag item
    var $dragCol = $item.closest('.kanban-lane-col');
    var $dropCol = $(event.target);

    /* Get d-n-d(drag and drop) infos  获取拖放操作相关信息 */
    var item = $item.data('item');
    var fromColType = $dragCol.data('type');
    var toColType = $dropCol.data('type');
    var kanbanID = $item.closest('.kanban').data('id');

    /* TODO: Save d-n-d infos to server 将拖放操作信息提交到服务器  */
    console.log('TODO: Save d-n-d infos to server 将拖放操作信息提交到服务器', {item, fromColType, toColType, kanbanID});

    /*
        // TODO: The server must return a updated kanban data  服务器返回更新后的看板数据

        // 调用 updateKanban 更新看板数据
        updateKanban(kanbanID, newKanbanData);
     */

    $('#kanbans').find('.can-drop-here').removeClass('can-drop-here');
}

/* Overload kanban default options */
$.extend($.fn.kanban.Constructor.DEFAULTS,
{
    onRender: function()
    {
        var maxWidth = 0;
        $('#kanbans .kanban-board').each(function()
        {
            maxWidth = Math.max(maxWidth, $(this).outerWidth());
        });
        $('#kanbanContainer').css('min-width', maxWidth + 40);
    }
});

/* Example code: */
$(function()
{
    /* Common options 用于初始化看板的通用选项 */　
    var commonOptions =
    {
        maxColHeight:   'auto',
        minColWidth:     240,
        maxColWidth:     240,
        showCount:       true,
        showZeroCount:   true,
        fluidBoardWidth: true,
        countRender:     renderColumnCount,
        droppable:
        {
            target:       findDropColumns,
            finish:       handleFinishDrop,
            mouseButton: 'left'
        }
    };

    /* Restore select menu */
    restoreSelect();

    /* Create story kanban 创建需求看板 */
    createKanban('story', getStoryKanbanDemoData(), commonOptions);

    /* Create bug kanban 创建 Bug 看板 */
    createKanban('bug', getBugKanbanDemoData(), commonOptions);

    /* Create task kanban 创建 任务 看板 */
    createKanban('task', getTaskKanbanDemoData(), commonOptions);
});

/**
 * Restore select menu.
 */
function restoreSelect()
{
    var type = $('#type').val();
    if(type == 'all') $('.c-group').css("display", "none");
}

/**
 * Choose type.
 */
$('#type').change(function()
{
    var type = $('#type').val();
    $('.c-group').css("display", "");
    if(type == 'all')
    {
        $('.c-group').css("display", "none");
    }
    else
    {
        $.get(createLink('execution', 'ajaxGetGroup', 'type=' + type), function(data)
        {
            $('#group_chosen').remove();
            $('#group').replaceWith(data);
            $('#group').chosen();
        })
    }
});

/**
 * Choose group of lane.
 */
$('.c-group').change(function()
{
    $('.c-group').css("display","");

    var type  = $('#type').val();
    var group = $('#group').val();
    link = createLink('execution', 'kanban', 'executionID=' + executionID + '&type=' + type + '&orderBy=order_asc' + '&group=' + group);
    location.href = link;
});
