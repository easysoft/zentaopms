<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php css::import($jsRoot . 'zui/kanban/min.css'); ?>
<?php js::import($jsRoot . 'zui/kanban/min.js'); ?>
<style>
#kanbanList .panel-heading {padding: 10px;}
#kanbanList .panel-body {padding: 0 10px 10px;}
#kanbanList .kanban {min-height: 120px; overflow: visible;}
#kanbanList .kanban-card {border-radius: 2px; padding: 10px;}
#kanbanList .kanban-card:hover {border: 1px solid #ccc;}
#kanbanList .kanban-header,
#kanbanList .kanban-lane {border-bottom: none; margin-bottom: 0;}
#kanbanList .kanban-sub-lane {border-bottom: 0;}
#kanbanList .kanban-lane {margin-top: 2px; min-height: 100px;}
#kanbanList .kanban-lane.has-sub-lane {background-color: transparent;}
#kanbanList .kanban-lane + .kanban-lane {margin-top: 10px;}
#kanbanList .kanban-sub-lane + .kanban-sub-lane {margin-top: 2px;}
#kanbanList .kanban-header-col > .title > .text {max-width: 200px; max-width: calc(100% - 50px);}
#kanbanList .kanban-header + .kanban-lane > .kanban-lane-name {margin-top: 0;}
#kanbanList .kanban-header {position: relative;}
#kanbanList .kanban-item.link-block {padding: 0;}
#kanbanList .kanban-item.link-block a {padding: 10px; display: block;}
#kanbanList .kanban-card {display: grid;}
#kanbanList .kanban-card > .title {white-space: nowrap; overflow: hidden; text-overflow: ellipsis;}
#kanbanList .kanban-card.has-progress {padding-right: 40px; position: relative;}
#kanbanList .kanban-card.has-progress > .progress-pie,
#kanbanList .kanban-card.has-progress > .ring {position: absolute; right: 7px; top: 7px; width: 24px; height: 24px;}
#kanbanList .kanban-card.has-left-border {border-left: 2px solid #838a9d;}
#kanbanList .kanban-card.has-left-border.border-left-green {border-left-color: #0bd986;}
#kanbanList .kanban-card.has-left-border.border-left-red {border-left-color: #ff5d5d;}
#kanbanList .kanban-card.has-left-border.border-left-blue {border-left-color: #0991ff;}
#kanbanList .no-flex .kanban-lane > .kanban-sub-lanes[data-sub-lanes-count="1"] > .kanban-sub-lane {min-height: 90px;}
#kanbanList .no-flex .kanban-lane > .kanban-sub-lanes[data-sub-lanes-count="2"] > .kanban-sub-lane {min-height: 45px;}

.kanban-affixed {padding-top: 72px;}
.kanban-affixed > .kanban-header {position: fixed!important; top: 0; background: rgba(80,80,80,.9); color: #fff; z-index: 100;}

#kanbanList .kanban-col[data-type="unclosedProduct"] .kanban-item {padding: 0;}
#kanbanList .kanban-col[data-type="unclosedProduct"] .kanban-lane-items {height: 100%; display: flex; flex-direction: column; justify-content: center; padding: 0; overflow: hidden;}
#kanbanList .kanban-card.kanban-card-span,
#kanbanList .kanban-col[data-type="unclosedProduct"] .kanban-card {background-color: transparent; border: none; padding: 0; text-align: center; box-shadow: none!important; margin: 0; height: auto!important;}
#kanbanList .kanban-card.kanban-card-span:hover,
#kanbanList .kanban-col[data-type="unclosedProduct"] .kanban-card:hover {box-shadow: none;}
#kanbanList .kanban-card.kanban-card-span > .title,
#kanbanList .kanban-col[data-type="unclosedProduct"] .kanban-card > .title {white-space: nowrap; line-height: 1;}

#kanbanList .kanban-col[data-type="normalRelease"] .kanban-card > .title {display: flex; flex-direction: row; flex-wrap: nowrap; align-items: center;}
#kanbanList .kanban-col[data-type="normalRelease"] .kanban-card > .title > .text {display: block; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;}
#kanbanList .kanban-col[data-type="normalRelease"] .kanban-card > .title.has-icon > .text {margin-right: 5px; max-width: calc(100% - 20px);}
#kanbanList .no-flex .kanban-col[data-type="normalRelease"] .kanban-card > .title {display: block; height: 38px;}
#kanbanList .no-flex .kanban-col[data-type="normalRelease"] .kanban-card > .title > .text {display: inline-block;}
#kanbanList .no-flex .kanban-col[data-type="normalRelease"] .kanban-card > .title > .icon {position: relative; top: -5px}
#kanbanList .kanban-affixed .kanban-header-col[data-type="doingProject"]:after {background-color: #606060;}

/* Show project and execution in one row */
#kanbanList .kanban-lane-col[data-type="doingProject"] + .kanban-lane-col {border-left: none; box-shadow: inset 2px 0 0 #fff;}
#kanbanList .kanban-lane-col[data-type="doingProject"] > .kanban-lane-items {padding: 0!important; overflow: visible; max-height: none!important;}
#kanbanList .kanban-item-span {padding: 0!important;}
#kanbanList .project-row {position: relative; width: 200%; width: calc(200% + 2px); height: 62px!important;}
#kanbanList .kanban-item-span + .kanban-item-span > .project-row {border-top: 2px solid #fff;}
#kanbanList .project-row > .project-col {float: left; width: 50%; padding: 10px;}
#kanbanList .project-row > .execution-item {position: absolute!important; left: 100%; top: 0}
</style>
<script>
/**
 * Check the given date whether it is earlier than today
 * @param {Date|string} date Date or date string
 * @returns {boolean}
 */
function isEarlierThanToday(date)
{
    if(!window.todayBegin)
    {
        var now = new Date();
        now.setHours(0);
        now.setMinutes(0);
        now.setSeconds(0);
        now.setMilliseconds(0);
        window.todayBegin = now.getTime();
    }
    return $.zui.createDate(date).getTime() < window.todayBegin;
}

/**
 * Render normal text span item
 * @param {Object} item  Product item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderSpanItem(item, $item)
{
    var $title = $item.find('.title');
    if(!$title.length)
    {
        $title = $('<div class="title" />').appendTo($item);
    }
    $title.text(item.name).attr('title', item.name);
    return $item.addClass('kanban-card-span');
}

/**
 * Render product item
 * @param {Object} item  Product item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderProductItem(item, $item)
{
    var $title = $item.find('.title');
    if(!$title.length)
    {
        if(window.userPrivs.product)
        {
            $title = $('<a class="title" />')
                .attr('href', $.createLink('product', 'browse', 'productID=' + item._id));
        }
        else
        {
            $title = $('<div class="title" />');
        }
        $title.appendTo($item);
    }
    $title.text(item.name).attr('title', item.name);
    return $item;
}

/**
 * Render plan item
 * @param {Object} item  Plan item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderPlanItem(item, $item)
{
    var $title = $item.find('.title');
    if(!$title.length)
    {
        if(window.userPrivs.productplan)
        {
            $item.addClass('link-block');
            $title = $('<a class="title" />')
                .attr('href', $.createLink('productplan', 'view', 'planID=' + item._id));
        }
        else
        {
            $title = $('<div class="title" />');
        }
        $title.appendTo($item);
    }
    $title.text(item.title).attr('title', item.title);
    return $item;
}

/**
 * Render project item
 * @param {Object} item  Project item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderProjectItem(item, $item)
{
    var $title = $item.find('.title');
    if(!$title.length)
    {
        if(window.userPrivs.project)
        {
            $item.addClass('link-block');
            $title = $('<a class="title" />')
                .attr('href', $.createLink('project', 'index', 'projectID=' + item._id));
        }
        else
        {
            $title = $('<div class="title" />');
        }
        $title.appendTo($item);
    }
    $title.text(item.name).attr('title', item.name);

    if(item.status === 'doing')
    {
        var $progress = $item.find('.ring');
        if(!$progress.length)
        {
            $progress = $('<div class="ring"><span></span></div>').appendTo($item);
        }
        var progress = Math.max(0, Math.min(100, Math.round(item.hours && !Array.isArray(item.hours) ? Math.round(item.hours.progress || 0) : 0)));
        $progress.find('span').text(progress);
        $progress.css('background-position-x', -Math.ceil(progress / 2) * 24);
        $item.addClass('has-progress');
    }
    return $item.addClass('has-left-border')
        .toggleClass('border-left-green', item.status === 'doing' && !item.delay)
        .toggleClass('border-left-red', item.status === 'doing' && !!item.delay)
        .toggleClass('border-left-gray', item.status === 'closed')
        .toggleClass('border-left-blue', item.status === 'wait');
}

/**
 * Render execution item
 * @param {Object} item  Execution item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderExecutionItem(item, $item)
{
    var $title = $item.find('.title');
    if(!$title.length)
    {
        if(window.userPrivs.project)
        {
            $item.addClass('link-block');
            $title = $('<a class="title" />')
                .attr('href', $.createLink('execution', 'task', 'executionID=' + item._id));
        }
        else
        {
            $title = $('<div class="title" />');
        }
        $title.appendTo($item);
    }
    $title.text(item.name).attr('title', item.name);

    if(window.statusColorList && window.statusColorList[item.status])
    {
        $item.css('borderLeftColor', window.statusColorList[item.status]);
    }

    var progress = item.progress || (item.hours && !Array.isArray(item.hours) ? Math.round(item.hours.progress) : undefined);
    if(progress === undefined && window.hourList)
    {
        var hoursInfo = window.hourList[item._id];
        progress = hoursInfo ? Math.round(hoursInfo.progress) : undefined;
    }
    if(progress !== undefined)
    {
        var $progress = $item.find('.ring');
        if(!$progress.length)
        {
            $progress = $('<div class="ring"><span></span></div>').appendTo($item);
        }
        progress = Math.max(0, Math.min(100, Math.round(progress)));
        $progress.find('span').text(progress);
        $progress.css('background-position-x', -Math.ceil(progress / 2) * 24);
        $item.addClass('has-progress');
    }
    var isDelay = item.end && isEarlierThanToday(item.end);
    return $item.addClass('has-progress has-left-border')
        .toggleClass('border-left-green', !isDelay)
        .toggleClass('border-left-red', !!isDelay);
}

/**
 * Render release item
 * @param {Object} item  Release item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderReleaseItem(item, $item)
{
    var $title = $item.find('.title');
    if(!$title.length)
    {
        if(window.userPrivs.release)
        {
            $item.addClass('link-block');
            $title = $('<a class="title" />')
                .attr('href', $.createLink('release', 'view', 'releaseID=' + item._id));
        }
        else
        {
            $title = $('<div class="title" />');
        }
        $title.appendTo($item);
    }
    $title.html('<span class="text">' + item.name + '</span>')
        .attr('title', item.name);
    if(item.marker === '1')
    {
        if(!$title.find('.icon').length)
        {
            $title.addClass('has-icon').append('<i class="icon icon-flag text-red"></i>');
        }
    }
    else
    {
        $title.find('.icon').remove();
    }

    return $item;
}

/**
 * Render project item
 * @param {Object} item  Project item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderDoingProjectItem(item, $item)
{
    $item.closest('.kanban-item').addClass('kanban-item-span');
    $item.removeClass('kanban-card').addClass('project-row clearfix').empty();

    var $projectCol = $('<div class="project-col"></div>').appendTo($item);
    var $projectItem = $('<div class="kanban-card project-item"></div>').appendTo($projectCol);
    renderProjectItem(item, $projectItem);

    var $executionCol = $('<div class="project-col"></div>').appendTo($item);
    if(item.execution)
    {
        var $executionItem = $('<div class="kanban-card execution-item"></div>').appendTo($executionCol);
        renderExecutionItem(item.execution, $executionItem);
    }

    return $item;
}

/** All build-in columns renderers */
if(!window.columnRenderers) window.columnRenderers =
{
    span: renderSpanItem,
    execution: renderExecutionItem,
    unclosedProduct: renderProductItem,
    unexpiredPlan: renderPlanItem,
    waitProject: renderProjectItem,
    closedProject: renderProjectItem,
    doingProject: renderDoingProjectItem,
    doingExecution: renderExecutionItem,
    normalRelease: renderReleaseItem,
};

/** User privs map */
if(!window.userPrivs) window.userPrivs = {};

/**
 * Add column renderer
 * @params {string}   columnType Column type
 * @params {function} renderer   Renderer function
 */
function addColumnRenderer(columnType, renderer)
{
    if(typeof columnType === 'object') $.extend(window.columnRenderers[columnType], columnType);
    else window.columnRenderers[columnType] = renderer;
}

/**
 * Render kanban item
 * @param {Object} item  Kanban item object
 * @param {JQuery} $item Kanban item element
 * @param {Object} col   Column object
 * @returns {JQuery} $item Kanban item element
 */
function renderKanbanItem(item, $item, col, lane, kanban)
{
    var columnRenderers = window.columnRenderers;
    var renderer        = columnRenderers[col.cardType] || columnRenderers[col.type] || columnRenderers[lane.defaultCardType || kanban.defaultCardType];
    if(renderer) return renderer(item, $item, col);
    return $item;
}

/**
 * Affix kanban board header
 * @param {JQuery}  $kanbanBoard Kanban board element
 * @param {boolean} affixed      Whether to affix the given board
 */
function affixKanbanHeader($kanbanBoard, affixed)
{
    var $header = $kanbanBoard.children('.kanban-header');
    var headerStyle = {width: '', left: ''};
    if(affixed)
    {
        headerStyle.width = $kanbanBoard.width();
        if($kanbanBoard[0].getBoundingClientRect)
        {
            headerStyle.left = $kanbanBoard[0].getBoundingClientRect().left;
        }
    }
    $header.css(headerStyle);
    $kanbanBoard.toggleClass('kanban-affixed', !!affixed);
    $kanbanBoard.css('padding-top', affixed ? $header.outerHeight() : '');
}

/** Update kanban affix state for all boards in page */
function updateKanbanAffixState()
{
    var $boards           = $('.kanban-board');
    var $lastAffixedBoard = $boards.filter('.kanban-affixed');
    var containerTop      = window.kanbanAffixContainer ? $(window.kanbanAffixContainer)[0].getBoundingClientRect().top : 0;
    var $currentAffixedBoard;

    $('.kanban-board').each(function()
    {
        var $board = $(this);
        var bounds = $board[0].getBoundingClientRect();
        if(bounds.top < containerTop && bounds.bottom > (containerTop))
        {
            $currentAffixedBoard = $board;
        }
    });

    if($lastAffixedBoard.length && (!$currentAffixedBoard || $lastAffixedBoard[0] !== $currentAffixedBoard[0]))
    {
        affixKanbanHeader($lastAffixedBoard, false);
    }

    if($currentAffixedBoard) affixKanbanHeader($currentAffixedBoard, true);
}

/** Try to update kanban affix state */
function tryUpdateKanbanAffix()
{
    if(window.updateKanbanAffixTimer) $.zui.clearAsap(window.updateKanbanAffixTimer);
    window.updateKanbanAffixTimer = $.zui.asap(function()
    {
        updateKanbanAffixState();
        window.updateKanbanAffixTimer = null;
    });
}

/* Kanban color list for lane name */
if(!window.kanbanColorList) window.kanbanColorList = ['#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#7FBB00', '#424BAC', '#66c5f8', '#EC2761'];

/* Set default options to kanban component */
$.extend($.fn.kanban.Constructor.DEFAULTS,
{
    readonly:        true,
    maxColHeight:    260,
    itemRender:      renderKanbanItem,
    showCount:       true,
    showZeroCount:   true,
    fluidBoardWidth: true,
    onRenderLaneName: function($name, lane, $kanban, columns, kanban)
    {
        var color = kanbanColorList[lane.$index % kanbanColorList.length];
        $name.css('background-color', color);
    },
    onCreate: function(kanban)
    {
        kanban.$.on('scroll', tryUpdateKanbanAffix);
        tryUpdateKanbanAffix();
    }
});

$(function()
{
    $(window.kanbanAffixContainer || window).on('scroll resize', tryUpdateKanbanAffix);
});
</script>
