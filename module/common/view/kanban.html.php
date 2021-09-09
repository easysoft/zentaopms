<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php css::import($jsRoot . 'zui/kanban/min.css'); ?>
<?php js::import($jsRoot . 'zui/kanban/min.js'); ?>
<style>
#kanbanList .panel-heading {padding: 10px;}
#kanbanList .panel-body {padding: 0 10px 10px;}
#kanbanList .kanban {min-height: 120px;}
#kanbanList .kanban-item {margin-top: 0; border: 1px solid #ebebeb; border-radius: 2px;}
#kanbanList .kanban-item:hover {border: 1px solid #ccc;}
#kanbanList .kanban-item + .kanban-item {margin-top: 10px;}
#kanbanList .kanban-lane-items {padding: 10px; min-height: 38px;}
#kanbanList .kanban-header,
#kanbanList .kanban-lane {border-bottom: none; margin-bottom: 0; min-height: 60px;}
#kanbanList .kanban-sub-lane {border-bottom: 0;}
#kanbanList .kanban-lane {border-top: 2px solid #fff;}
#kanbanList .kanban-lane + .kanban-lane {border-top: 10px solid #fff;}
#kanbanList .kanban-sub-lane + .kanban-sub-lane {border-top: 2px solid #fff;}
#kanbanList .kanban-col + .kanban-col {border-left: 2px solid #fff;}
#kanbanList .kanban-header-col {height: 72px; padding: 20px 5px;}
#kanbanList .kanban-header-col > .title {margin: 0; line-height: 32px; height: 32px}
#kanbanList .kanban-header-col > .title > .text {font-weight: bold; max-width: 200px; max-width: calc(100% - 50px);}
#kanbanList .kanban-header-col > .title > .icon, #kanbanList .kanban-header-col > .title > .count {top: -11px}
#kanbanList .kanban-header + .kanban-lane > .kanban-lane-name {margin-top: 0;}
#kanbanList .kanban-header {position: relative;}
#kanbanList .kanban-item.link-block {padding: 0;}
#kanbanList .kanban-item.link-block > a {padding: 10px; display: block;}
#kanbanList .kanban-item > .title {white-space: nowrap; overflow: hidden; text-overflow: clip;}
#kanbanList .kanban-item.link-block > a {padding: 10px; display: block;}
#kanbanList .kanban-item.has-progress {padding-right: 40px; position: relative;}
#kanbanList .kanban-item.has-progress > .progress-pie {position: absolute; right: 7px; top: 7px}
#kanbanList .kanban-item.has-left-border {border-left: 2px solid #838a9d;}
#kanbanList .kanban-item.has-left-border.border-left-green {border-left-color: #0bd986;}
#kanbanList .kanban-item.has-left-border.border-left-red {border-left-color: #ff5d5d;}
#kanbanList .kanban-item.has-left-border.border-left-blue {border-left-color: #0991ff;}

.kanban-affixed {padding-top: 72px;}
.kanban-affixed > .kanban-header {position: fixed!important; top: 0; background: rgba(80,80,80,.9); color: #fff; z-index: 100;}

#kanbanList .kanban-header-col[data-type="doingProject"],
#kanbanList .kanban-header-col[data-type="doingProject"] + .kanban-header-col[data-type="doingExecution"] {padding: 38px 10px 0;}
#kanbanList .kanban-header-col[data-type="doingProject"]:after {content: attr(data-span-text); display: block; position: absolute; z-index: 10; left: 0; right:  -100%; right: calc(-100% - 2px); top: 0; line-height: 36px; text-align: center; font-weight: bold; border-bottom: 2px solid #fff; background-color: #ededed;}
#kanbanList .kanban-col[data-type="unclosedProduct"] .kanban-lane-items {height: 100%; display: flex; flex-direction: column; justify-content: center;}
#kanbanList .kanban-col[data-type="unclosedProduct"] .kanban-item {background-color: transparent; border: none; padding: 0; text-align: center;}
#kanbanList .kanban-col[data-type="unclosedProduct"] .kanban-item:hover {box-shadow: none;}
#kanbanList .kanban-col[data-type="unclosedProduct"] .kanban-item > .title {white-space: normal;}
#kanbanList .kanban-col[data-type="normalRelease"] .kanban-item > .title {display: flex; flex-direction: row; flex-wrap: nowrap; align-items: center; height: 38px;}
#kanbanList .kanban-col[data-type="normalRelease"] .kanban-item > .title > .text {display: block; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;}
#kanbanList .kanban-col[data-type="normalRelease"] .kanban-item > .title.has-icon > .text {margin-right: 5px; max-width: calc(100% - 20px);}
#kanbanList .no-flex .kanban-col[data-type="normalRelease"] .kanban-item > .title {display: block; height: 38px;}
#kanbanList .no-flex .kanban-col[data-type="normalRelease"] .kanban-item > .title > .text {display: inline-block;}
#kanbanList .no-flex .kanban-col[data-type="normalRelease"] .kanban-item > .title > .icon {position: relative; top: -5px}
#kanbanList .kanban-affixed .kanban-header-col[data-type="doingProject"]:after {background-color: #606060;}

/* Show project and execution in one row */
#kanbanList .kanban-lane-col[data-type="doingProject"] {box-shadow: 2px 0 0 #fff;}
#kanbanList .kanban-lane-col[data-type="doingProject"] + .kanban-lane-col {border-left: none;}
#kanbanList .kanban-lane-col[data-type="doingProject"] > .kanban-lane-items {padding: 0; overflow: visible; max-height: none!important;}
#kanbanList .project-row {position: relative; width: 200%; width: calc(200% + 2px);}
#kanbanList .project-row + .project-row {border-top: 2px solid #fff;}
#kanbanList .project-row > .project-col {float: left; width: 50%; padding: 10px;}
#kanbanList .project-row > .project-col + .project-col {padding: 10px 9px 10px 11px;}
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
        var progress = item.hours && !Array.isArray(item.hours) ? Math.round(item.hours.progress || 0) : 0;
        var $progress = $item.find('.progress-pie');
        if(!$progress.length)
        {
            $progress = $('<div class="progress-pie" data-doughnut-size="90" data-color="#3CB371" data-width="24" data-height="24" data-back-color="#e8edf3"><div class="progress-info"></div></div>').appendTo($item);
        }
        $progress.find('.progress-info').text(progress);
        $progress.attr('data-value', progress).progressPie();
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

    var progress = item.progress || (item.hours && !Array.isArray(item.hours) ? Math.round(item.hours.progress) : undefined);
    if(progress === undefined && window.hourList)
    {
        var hoursInfo = window.hourList[item._id];
        progress = hoursInfo ? Math.round(hoursInfo.progress) : undefined;
    }
    if(progress !== undefined)
    {
        var $progress   = $item.find('.progress-pie');
        if(!$progress.length)
        {
            $progress = $('<div class="progress-pie" data-doughnut-size="90" data-color="#3CB371" data-width="24" data-height="24" data-back-color="#e8edf3"><div class="progress-info"></div></div>').appendTo($item);
        }
        $progress.find('.progress-info').text(progress);
        $progress.attr('data-value', progress).progressPie();
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
    $item.removeClass('kanban-item').addClass('project-row clearfix').empty();

    var $projectCol = $('<div class="project-col"></div>').appendTo($item);
    var $projectItem = $('<div class="kanban-item project-item"></div>').appendTo($projectCol);
    renderProjectItem(item, $projectItem);

    var $executionCol = $('<div class="project-col"></div>').appendTo($item);
    if(item.execution)
    {
        var $executionItem = $('<div class="kanban-item execution-item"></div>').appendTo($executionCol);
        renderExecutionItem(item.execution, $executionItem);
    }

    return $item;
}

/** All build-in columns renderers */
if(!window.columnRenderers) window.columnRenderers =
{
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
function renderKanbanItem(item, $item, col)
{
    var renderer = window.columnRenderers[col.type];
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
    $header.css('width', affixed ? $kanbanBoard.width() : '');
    $kanbanBoard.toggleClass('kanban-affixed', !!affixed);
    $kanbanBoard.css('padding-top', affixed ? $header.outerHeight() : '');
}

/**
 * Update kanban affix state for all boards in page
 */
function updateKanbanAffixState()
{
    var $boards = $('.kanban-board');
    var $lastAffixedBoard = $boards.filter('.kanban-affixed');
    var $currentAffixedBoard;
    var currentOffsetTop = 0;
    var scrollTop = $(window).scrollTop();
    $('.kanban-board').each(function()
    {
        var $board = $(this);
        var offsetTop = $board.offset().top;
        if(scrollTop >= offsetTop && offsetTop > currentOffsetTop && scrollTop < (offsetTop + $board.outerHeight() - 72))
        {
            currentOffsetTop = offsetTop;
            $currentAffixedBoard = $board;
        }
    });

    if($lastAffixedBoard.length && (!$currentAffixedBoard || $lastAffixedBoard[0] !== $currentAffixedBoard[0]))
    {
        affixKanbanHeader($lastAffixedBoard, false);
    }

    if($currentAffixedBoard) affixKanbanHeader($currentAffixedBoard, true);
}

/* Kanban color list for lane name */
if(!window.kanbanColorList) window.kanbanColorList = ['#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#7FBB00', '#424BAC', '#66c5f8', '#EC2761'];

/* Set default options to kanban component */
$.extend($.fn.kanban.Constructor.DEFAULTS,
{
    readonly: true,
    maxColHeight: 260,
    /* laneItemsClass: 'scrollbar-hover', */ // only show scrollbar on mouse hover
    itemRender: renderKanbanItem,
    useFlex: false,
    showZeroCount: true,
    onRenderHeaderCol: function($col, col)
    {
        if(col.type === 'doingProject') $col.attr('data-span-text', doingText);
    },
    onRenderKanban: function($kanban, kanbanData)
    {
        $kanban.find('.kanban-lane-name').each(function(index)
        {
            var color = kanbanColorList[index % kanbanColorList.length];
            $(this).css('background-color', color);
        });

        /* Update project count and execution count */
        var doingProjectCount   = 0;
        var doingExecutionCount = 0;
        var $doingProjectItems = $kanban.find('.kanban-lane-col[data-type="doingProject"] > .kanban-lane-items');
        if($doingProjectItems.length)
        {
            doingProjectCount = $doingProjectItems.find('.project-item').length;
            doingExecutionCount = $doingProjectItems.find('.execution-item').length;
        }
        $kanban.find('.kanban-header-col[data-type="doingProject"] > .title > .count').text(doingProjectCount || 0);
        $kanban.find('.kanban-header-col[data-type="doingExecution"] > .title > .count').text(doingExecutionCount || 0);

        updateKanbanAffixState();
    }
});

$(function()
{
    /* Update kanban affix state on window resize or scroll */
    var updateTimer;
    var updateCallback = function()
    {
        updateKanbanAffixState();
        updateTimer = null;
    };
    $(window).on('scroll resize', function(e)
    {
        if(updateTimer)
        {
            if(window.requestAnimationFrame) cancelAnimationFrame(updateTimer);
            else clearTimeout(updateTimer);
        }
        if(window.requestAnimationFrame) updateTimer = requestAnimationFrame(updateCallback);
        else updateTimer = setTimeout(updateCallback, 50);
    });
});
</script>
