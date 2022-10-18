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
            $content.append("<a href='###' class='more'><i class='icon icon-chevron-double-down'></i></a>");
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
        $.cookie('viewType', viewType, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });

    $('#branch').change(function()
    {
        var branchID = $(this).val();
        var link = createLink('productplan', 'browse', "productID=" + productID + '&branch=' + branchID);
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
        var executionLink = systemMode == 'new' ? '#projects' : createLink('execution', 'create', "projectID=0&executionID=0&copyExecutionID=0&plan=" + card.id + "&confirm=no&productID=" + productID);
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

        if(systemMode == 'new')
        {
            items.push({label: productplanLang.createExecution, icon: 'plus', url: executionLink, className: className, attrs: {'data-toggle': 'modal', 'onclick': 'getPlanID(this,' + card.branch + ')', 'data-id': card.id}});
        }
        else
        {
            items.push({label: productplanLang.createExecution, icon: 'plus', url: executionLink, className: className});
        }
    }

    if(privs.includes('linkStory')) items.push({label: productplanLang.linkStory, icon: 'link', url: createLink('productplan', 'view', "planID=" + card.id + "&type=story&orderBy=id_desc&link=true")});
    if(privs.includes('linkBug')) items.push({label: productplanLang.linkBug, icon: 'bug', url: createLink('productplan', 'view', "planID=" + card.id + "&type=bug&orderBy=id_desc&link=true")});
    if(privs.includes('edit')) items.push({label: productplanLang.edit, icon: 'edit', url: createLink('productplan', 'edit', "planID=" + card.id)});
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
        $toggle.find('i').removeClass('icon-chevron-double-up').addClass('icon-chevron-double-down');
    }
    else
    {
        $toggle.addClass('open');
        $toggle.closest('.content').find('div').css('height', 'auto');
        $toggle.css('padding-top', ($toggle.closest('.content').find('div').height() - $toggle.height()) / 2);
        $toggle.find('i').removeClass('icon-chevron-double-down').addClass('icon-chevron-double-up');
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
        if(privs.includes('view')) $title = $('<a class="title"></a>').appendTo($titleBox).attr('href', createLink('productplan', 'view', 'cardID=' + item.id));
        if(!privs.includes('view')) $title = $('<a class="title"></a>').appendTo($titleBox);
    }
    $title.text(item.title).attr('title', item.title);

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

    /* Output plan date information. */
    var $dateBox = $item.children('.dateBox');
    if(!$dateBox.length) $dateBox = $(
    [
        '<div class="dateBox">',
          '<span class="time label label-outline"></span>',
        '</div>'
    ].join('')).appendTo($item);

    var $time            = $dateBox.children('.time');
    var beginTimeShort   = $.zui.formatDate(begin, 'MM-dd');
    var beginTimeLong    = $.zui.formatDate(begin, 'yyyy-MM-dd');
    var endTimeShort     = $.zui.formatDate(end, 'MM-dd');
    var endTimeLong      = $.zui.formatDate(end, 'yyyy-MM-dd')
    var to               = productplanLang.to;
    var undetermined     = productplanLang.future;
    var undetermindeDate = '2030-01-01';
    if(item.begin != undetermindeDate && item.end != undetermindeDate)
    {
        $time.text(beginTimeShort + ' ' + to + ' ' + endTimeShort).attr('title', beginTimeLong + to + endTimeLong).show();
    }
    else if(item.begin != undetermindeDate && item.end == undetermindeDate)
    {
        $time.text(beginTimeShort +  ' ' + to  + ' ' + undetermined).attr('title', beginTimeLong + to).show();
    }
    else if(item.begin == undetermindeDate && item.end != undetermindeDate)
    {
        $time.text(undetermined +  ' ' + to  + ' ' + endTimeShort).attr('title', to + endTimeLong).show();
    }
    else if(item.begin != '2030-01-01' && item.end == '2030-01-01')
    {
        $time.text($.zui.formatDate(begin, 'MM-dd') +  ' ' + productplanLang.to  + ' ' + productplanLang.future).attr('title', $.zui.formatDate(begin, 'yyyy-MM-dd') + productplanLang.to).show();
    }
    else if(item.begin == '2030-01-01' && item.end != '2030-01-01')
    {
        $time.text(productplanLang.future +  ' ' + productplanLang.to  + ' ' + $.zui.formatDate(end, 'MM-dd')).attr('title', $.zui.formatDate(end, 'yyyy-MM-dd') + productplanLang.to).show();
    }
    else
    {
        $time.text(undetermined).attr('title', undetermined).show();
    }

    /* Output plan desc information. */
    var $desc = $item.children('.desc');
    if(!$desc.length)
    {
        $("<div class='desc c-name'"+ " title='" + item.desc + "'>" + item.desc + '</div>').appendTo($item);
    }
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
