$(document).on('click', '.task-toggle', function(e)
{
    var $toggle = $(this);
    var id = $(this).data('id');
    var isCollapsed = $toggle.toggleClass('collapsed').hasClass('collapsed');
    $toggle.closest('[data-ride="table"]').find('tr.parent-' + id).toggle(!isCollapsed);

    e.stopPropagation();
    e.preventDefault();
});

$(function()
{
    if(viewType != 'kanban') toggleFold('#productplanForm', unfoldPlans, productID, 'productplan');
    $('#productplanList tbody tr').each(function()
    {
        var $content = $(this).find('td.content');
        var content  = $content.find('div').html();
        if(content.indexOf('<br') >= 0 || content.indexOf('<img') >= 0)
        {
            $content.append("<a href='###' class='more'><i class='icon icon-angle-right rotate-down'></i></a>");
        }
    });

    $('#createExecutionButton').on('click', function()
    {
        var projectID = $('#project').val();
        var planID    = $('#planID').val();
        if(!projectID)
        {
            alert(projectNotEmpty);
            return false;
        }
        else
        {
            $.apps.open(createLink('execution', 'create', 'projectID=' + projectID + '&executionID=&copyExecutionID=&planID=' + planID + '&confirm=&productID=' + productID), 'execution')
        }
        $('#projects').modal('hide');
    });

    $('.switchButton').click(function()
    {
        var viewType = $(this).attr('data-type');
        var branchID = $(this).val();
        $.cookie('viewType', viewType, {expires:config.cookieLife, path:config.webRoot});
        var link = createLink('productplan', 'browse', "productID=" + productID + '&branch=' + branchID);
        location.href = link;
    });

    $('#branch').change(function()
    {
        var branchID = $(this).val();
        var link = createLink(rawModule, 'browse', "productID=" + productID + '&branch=' + branchID);
        location.href = link;
    });

    if(viewType == 'kanban')
    {
        $('#branch_chosen .chosen-single span').prepend('<i class="icon-delay"></i>');
        $('#kanban').kanban(
        {
            data:         kanbanData,
            minColWidth:  typeof window.minColWidth === 'number' ? window.minColWidth: defaultMinColWidth,
            maxColWidth:  typeof window.maxColWidth === 'number' ? window.maxColWidth: defaultMaxColWidth,
            maxColHeight: 460,
            minColHeight: 190,
            cardHeight:   80,
            itemRender:   renderKanbanItem,
            virtualize:   true,
            droppable:
            {
                target:      findDropColumns,
                finish:      handleFinishDrop
            }
        });

        resetLaneHeight();

        $('#kanban').on('scroll', function()
        {
            $.zui.ContextMenu.hide();
        });

        /* Init contextmenu. */
        $('#kanban').on('click', '[data-contextmenu]', function(event)
        {
            var $trigger    = $(this);
            var menuType    = $trigger.data('contextmenu');
            var menuCreator = window.menuCreators[menuType];
            if(!menuCreator) return;

            var options = $.extend({event: event, $trigger: $trigger}, $trigger.data());
            var items   = menuCreator(options);
            if(!items || !items.length) return;

            $.zui.ContextMenu.show(items, items.$options || {event: event});
        });
    }

    /* Hide contextmenu when page scroll. */
    $(window).on('scroll', function()
    {
        $.zui.ContextMenu.hide();
    });

    $('.execution-popover').on('click', function(e)
    {
        e.stopPropagation();
        var showPopover = $(this).next().css('display') == 'block';
        $('.popover.right').hide();
        if(!showPopover) $(this).next().show();
    });

    $('.execution-link').on('click', function()
    {
        $('.popover.right').hide();
    });

    /* Hide popover tip. */
    $(document).on('mousedown', function(e)
    {
        var $target = $(e.target);
        var $toggle = $target.closest('.popover, .execution-popover');
        if(!$toggle.length) $('.popover.right').hide();
    });
});

/* Define menu creators. */
window.menuCreators = {card: createCardMenu};

/**
 * Create card menu.
 *
 * @param  object options
 * @access public
 * @return array
 */
function createCardMenu(options)
{
    var card  = options.$trigger.closest('.kanban-item').data('item');
    var privs = card.actions;
    if(!privs.length) return [];

    var items = [];
    if(privs.includes('createExecution'))
    {
        var className     = '';
        var today         = new Date();
        var end           = $.zui.createDate(card.end);
        if(end.getTime() < today.getTime())
        {
            className = 'disabled';
        }
        else if(card.status == 'done' || card.status == 'closed')
        {
            className = 'disabled';
        }
        else if(product.type != 'normal')
        {
            var branchStatus = branchStatusList[card.branch];
            if(branchStatus == 'closed') className = 'disabled';
        }

        items.push({label: productplanLang.createExecution, icon: 'plus', url: '#projects', className: className, attrs: {'data-toggle': 'modal', 'onclick': 'getPlanID(this,' + card.branch + ')', 'data-id': card.id}});
    }

    if(privs.includes('linkStory')) items.push({label: productplanLang.linkStory, icon: 'link', url: createLink(rawModule, 'view', "planID=" + card.id + "&type=story&orderBy=id_desc&link=true")});
    if(privs.includes('linkBug')) items.push({label: productplanLang.linkBug, icon: 'bug', url: createLink(rawModule, 'view', "planID=" + card.id + "&type=bug&orderBy=id_desc&link=true")});
    if(privs.includes('edit')) items.push({label: productplanLang.edit, icon: 'edit', url: createLink(rawModule, 'edit', "planID=" + card.id)});
    if(privs.includes('start')) items.push({label: productplanLang.start, icon: 'start', url: createLink('productplan', 'start', "planID=" + card.id), attrs: {'target': 'hiddenwin'}});
    if(privs.includes('finish')) items.push({label: productplanLang.finish, icon: 'checked', url: createLink('productplan', 'finish', "planID=" + card.id), attrs: {'target': 'hiddenwin'}});
    if(privs.includes('close')) items.push({label: productplanLang.close, icon: 'off', url: createLink('productplan', 'close', "planID=" + card.id, '', true), className: 'iframe', attrs: {'data-toggle': 'modal'}});
    if(privs.includes('activate')) items.push({label: productplanLang.activate, icon: 'magic', url: createLink('productplan', 'activate', "planID=" + card.id), attrs: {'target': 'hiddenwin'}});
    if(privs.includes('delete')) items.push({label: productplanLang.delete, icon: 'trash', url: createLink('productplan', 'delete', "planID=" + card.id), attrs: {'target': 'hiddenwin'}});

    var bounds = options.$trigger[0].getBoundingClientRect();
    items.$options = {x: bounds.right, y: bounds.top};
    return items;
}

$(document).on('click', 'td.content .more', function(e)
{
    var $toggle = $(this);
    if($toggle.hasClass('open'))
    {
        $toggle.removeClass('open');
        $toggle.closest('.content').find('div').css('height', '25px');
        $toggle.css('padding-top', 0);
        $toggle.find('i').addClass('rotate-down');
    }
    else
    {
        $toggle.addClass('open');
        $toggle.closest('.content').find('div').css('height', 'auto');
        $toggle.css('padding-top', ($toggle.closest('.content').find('div').height() - $toggle.height()) / 2);
        $toggle.find('i').removeClass('rotate-down');
    }
});

/**
 * Find drop columns.
 *
 * @param  object $element Drag element
 * @param  object $root Dnd root element
 * @access public
 * @return object|bool
 */
function findDropColumns($element, $root)
{
    var $col        = $element.closest('.kanban-col');
    var col         = $col.data();
    var lane        = $col.closest('.kanban-lane').data();
    var kanbanRules = window.kanbanDropRules ? window.kanbanDropRules : null;

    if(!kanbanRules) return $root.find('.kanban-lane[data-id="' + lane.id + '"] .kanban-lane-col:not([data-type="project"],[data-type="' + col.type + '"])');

    var colRules = kanbanRules[col.type];
    var lane     = $col.closest('.kanban-lane').data('lane');
    return $root.find('.kanban-lane-col').filter(function()
    {
        if(!colRules) return false;
        if(colRules === true) return true;

        var $newCol = $(this);
        var newCol  = $newCol.data();
        if(newCol.id === col.id) return false;

        var $newLane = $newCol.closest('.kanban-lane');
        var newLane  = $newLane.data('lane');
        return colRules.indexOf(newCol.type) > -1 && newLane.id === lane.id;
    });
}

/**
 * Reset lane height according to window height.
 */
function resetLaneHeight()
{
    if(product.type == 'normal')
    {
        var windowHeight = $(window).height();
        var mainHeader   = $('#mainHeader').outerHeight();
        var marginMenu   = $('#mainMenu').outerHeight();
        var headerHeight = $('#kanban > .kanban-board > .kanban-header').outerHeight();

        maxHeight = windowHeight - headerHeight - mainHeader - marginMenu - 80;
        $('.kanban-lane').css('height', maxHeight);
    }
}


/**
 * Handle finish drop card.
 *
 * @param  object event
 * @access public
 * @return void
 */
function handleFinishDrop(event)
{
    var $card    = $(event.element); // The drag card.
    var $dragCol = $card.closest('.kanban-lane-col');
    var $dropCol = $(event.target);

    /* Get d-n-d(drag and drop) infos. */
    var card        = $card.data('item');
    var fromColType = $dragCol.data('type');
    var toColType   = $dropCol.data('type');
    var kanbanID    = $card.closest('.kanban').data('id');

    changeCardColType(card, fromColType, toColType, kanbanID);
}

/**
 * Change column type for a card.
 *
 * @param  object card
 * @param  string fromColType The column type before change
 * @param  string toColType The column type after change
 * @param  int    kanbanID
 * @access public
 * @return void
 */
function changeCardColType(card, fromColType, toColType, kanbanID)
{
    if(typeof card == 'undefined') return false;
    var objectID   = card.id;
    var privs      = card.actions;
    var showIframe = false;
    var link       = '';

    if(toColType == 'doing')
    {
        if(fromColType == 'wait' && privs.includes('start'))
        {
            link = createLink('productplan', 'start', 'planID=' + objectID);
            showIframe = false;
        }
        else if((fromColType == 'done' || fromColType == 'closed') && privs.includes('activate'))
        {
            link       = createLink('productplan', 'activate', 'planID=' + objectID);
            showIframe = false;
        }
    }
    else if(toColType == 'done')
    {
        if(fromColType == 'doing')
        {
            link       = createLink('productplan', 'finish', 'planID=' + objectID);
            showIframe = false;
        }
    }
    else if(toColType == 'closed')
    {
        if(fromColType != 'closed')
        {
            link       = createLink('productplan', 'close', 'planID=' + objectID, '', true);
            showIframe = true;
        }
    }

    if(showIframe)
    {
        var modalTrigger = new $.zui.ModalTrigger({type: 'iframe', width: '70%', url: link});
        modalTrigger.show();
    }
    else if(!showIframe && link)
    {
        hiddenwin.location.href = link;
    }
}

/**
 * The function for rendering kanban item.
 *
 * @param  object item
 * @param  object $item
 * @access public
 * @return void
 */
function renderKanbanItem(item, $item)
{
    var privs        = item.actions;
    var printMoreBtn = (privs.includes('createExecution') || privs.includes('linkStory') || privs.includes('linkBug') || privs.includes('edit') || privs.includes('start') || privs.includes('finish') || privs.includes('close') || privs.includes('activate') || privs.includes('delete'));

    /* Output header information. */
    var $header = $item.children('.header');
    if(!$header.length) $header = $(
    [
        '<div class="header">',
        '</div>'
    ].join('')).appendTo($item);

    var $titleBox = $item.children('.titleBox');
    if(!$titleBox.length) $titleBox = $(
    [
        '<div class="titleBox">',
        '</div>'
    ].join('')).appendTo($header);

    /* Print plan name. */
    var $title = $titleBox.children('.title');
    if(!$title.length)
    {
        if(privs.includes('view')) $title = $('<a class="title"></a>').appendTo($titleBox).attr('href', createLink(rawModule, 'view', 'cardID=' + item.id));
        if(!privs.includes('view')) $title = $('<a class="title"></a>').appendTo($titleBox);
    }
    if(!$title.children('i').length)
    {
      $(function() {$title.prepend('<i class="icon icon-delay"></i>');})
    }
    $title.text(item.title).attr('title', item.title);

    var $info = $item.children('.productplanInfo');
    if(!$info.length) $info = $(
    [
        '<div class="productplanInfo">',
        '</div>'
    ].join('')).appendTo($item);

    /* Output plan desc information. */
    var $descBox = $item.children('.productplanDesc');
    if(!$descBox.length)
    {
        $descBox = $('<div class="productplanDesc c-name cardDesc" title="' + item.desc + '">' + item.desc + '</div>').appendTo($info);
    }

    var $statusBox = $item.children('.productplanStatus');
    if(!$statusBox.length)
    {
        if(item.deleted == '0')
        {
            $statusBox = $('<span class="productplanStatus label label-' + item.status + '">' + productplanLang.statusList[item.status] + '</span>').appendTo($info);
        }
        else
        {
            $statusBox = $('<span class="productplanStatus label label-deleted">' + productplanLang.deleted + '</span>').appendTo($info);
        }
    }

    /* Determine whether to print an expired label. */
    var today = new Date();
    var begin = $.zui.createDate(item.begin);
    var end   = $.zui.createDate(item.end);
    if(item.delay && (item.status == 'wait' || item.status == 'doing'))
    {
        $expired = $titleBox.children('.expired');
        if(!$expired.length)
        {
            $('<span class="expired label label-danger label-badge">' + productplanLang.expired + '</span>').appendTo($titleBox);
        }
    }

    if(printMoreBtn)
    {
        $(
        [
            '<div class="actions" title="' + productplanLang.more + '">',
              '<a data-contextmenu="card" data-id="' + item.id + '">',
                '<i class="icon icon-ellipsis-v"></i>',
              '</a>',
            '</div>'
        ].join('')).appendTo($header);
    }

    /* Display date of product plan. */
    var $date      = $info.children('.date');
    var begin      = $.zui.createDate(item.begin);
    var end        = $.zui.createDate(item.end);
    var today      = new Date();
    var labelType  = (item.begin <= $.zui.formatDate(today, 'yyyy-MM-dd') && item.end >= $.zui.formatDate(today, 'yyyy-MM-dd')) ? 'danger' : 'wait';
    var labelTitle = $.zui.formatDate(begin, 'MM-dd') + ' ' + productplanLang.to + ' ' + $.zui.formatDate(end, 'MM-dd');

    if((item.begin == '2030-01-01' || item.end == '2030-01-01'))
    {
        labelType  = 'future';
        labelTitle = productplanLang.future;
    }
    if(!$date.length) $date = $('<span class="date label label-' + labelType + '"></span>').appendTo($info);
    $date.text(labelTitle).attr('title', labelTitle).show();

    /* Display avatars of creator. */
    var $user = $info.children('.user');
    var user  = [item.createdBy];
    if(users[item.createdBy])
    {
        if(!$user.length) $user = $('<div class="user"></div>').appendTo($info);
        $user.html(renderUsersAvatar(user, item.id)).attr('title', users[item.createdBy]);
    }
}

/**
* Render avatars of user.
* @param {String|{account: string, avatar: string}} user User account or user object
* @returns {string}
*/
function renderUsersAvatar(users, itemID, size)
{
    var avatarSizeClass = 'avatar-' + (size || 'md');

    if(users.length == 0 || (users.length == 1 && users[0] == ''))
    {
        return $('<div class="avatar has-text ' + avatarSizeClass + ' avatar-circle iframe" title="' + noAssigned + '" style="background: #ccc"><i class="icon icon-person"></i></div>');
    }

    var assignees = [];
    for(var user of users)
    {
        var $noPrivAndNoAssigned = $('<div class="avatar has-text ' + avatarSizeClass + ' avatar-circle" title="' + noAssigned + '" style="background: #ccc"><i class="icon icon-person"></i></div>');
        if(!priv.canAssignCard && !user)
        {
            assignees.push($noPrivAndNoAssigned);
            continue;
        }
        if(!user)
        {
            assignees.push($('<div class="avatar has-text ' + avatarSizeClass + ' avatar-circle iframe" title="' + noAssigned + '" style="background: #ccc"><i class="icon icon-person"></i></div>'));
            continue;
        }

        if(typeof user === 'string') user = {account: user};
        if(!user.avatar && window.userList && window.userList[user.account]) user = window.userList[user.account];
        if(!user.name && window.users && window.users[user.account]) user.name = window.users[user.account];

        assignees.push($('<div class="avatar has-text ' + avatarSizeClass + ' avatar-circle iframe"></div>').avatar({user: user}));
    }

    if(assignees.length > 3) assignees.splice(3, assignees.length - 3, '<span>...</span>');

    return assignees;
}

/**
 * Get planID.
 *
 * @param  object obj
 * @param  int    branch
 * @access public
 * @return void
 */
function getPlanID(obj, branch)
{
    var planID = $(obj).attr("data-id");
    $('#planID').val(planID);

    link = createLink('productplan', 'ajaxGetProjects', 'productID=' + productID + '&branch=' + branch);
    $.get(link, function(projects)
    {
        $('#project').replaceWith(projects);
        $("#project_chosen").remove();
        $("#project").chosen();

        var projectList = $("#project").val();
        if(!projectList)
        {
            $("#project").attr('disabled', true);
            $("#project").trigger('chosen:updated');
            $(".tips").removeClass('hidden');

            var locateLink   = createLink('product', 'project', 'status=all&productID=' + productID + '&branch=' + branch);
            var locateButton = "<a href=" + locateLink + " class='btn btn-primary' data-app='product'>" + enterProjectList + "</a>";
            $("#projects .btn-primary").replaceWith(locateButton);
        }
        else
        {
            $(".tips").addClass('hidden');
        }
    });
}

if(!window.kanbanDropRules)
{
    window.kanbanDropRules =
    {
        wait:   ['doing', 'closed'],
        doing:  ['done', 'closed'],
        done:   ['doing', 'closed'],
        closed: ['doing']
    }
}
