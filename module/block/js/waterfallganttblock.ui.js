$(function()
{
    if(ganttPlans) initWaterfallGanttBlock();
});

window.initWaterfallGanttBlock = function()
{
    var ganttData = ganttPlans['data'];
    if(!ganttData) return;

    var plans         = [];
    var tasks         = [];
    var plansMap      = {};
    var startDatetime = Number.MAX_SAFE_INTEGER;
    var endDatetime   = 0;
    var minTimeGap    = Number.MAX_SAFE_INTEGER;
    var $gantt        = $('#' + waterfallGanttID);
    var ONE_DAY       = 24 * 3600 * 1000;
    var TIME_GAP_STEP = 7;
    var MIN_COL_WIDTH = 60;

    $.each(ganttData, function(index, item)
    {
        plansMap[item.id] = item;
        if(item.type == 'plan' && item.parent == '0')
        {
            item.startDatetime = createDatetime(item.start_date);
            item.endDatetime   = createDatetime(item.deadline);
            startDatetime      = Math.min(startDatetime, item.startDatetime);
            endDatetime        = Math.max(endDatetime, item.endDatetime);
            minTimeGap         = Math.min(minTimeGap, endDatetime - startDatetime);
            item.tasks         = [];
            item.completeTasks = [];
            item.progress      = Number.parseFloat(item.taskProgress.replace('%', ''), 10);
            plans.push(item);
        }
        else if(item.type === 'task')
        {
            item.progress = Number.parseFloat(item.taskProgress.replace('%', ''), 10);
            tasks.push(item);
        }
    });

    $.each(tasks, function(index, task)
    {
        var plan = plansMap[task.parent];
        if(typeof(plan) == 'undefined') return;
        while(plan.parent > 0)
        {
            plan = plansMap[plan.parent];
            if(typeof(plan) != 'object') return;
        }
        if(task.progress === 100) plan.completeTasks.push(task);
        plan.tasks.push(task);
    });

    var $plans          = $gantt.find('.gantt-plans');
    var $ganttContainer = $gantt.find('.gantt-container');
    var $ganttCanvas    = $gantt.find('.gantt-canvas');
    var themeColor      = 'rgb(46, 127, 255)';
    var canvasHeight    = plans.length * 50 + 10;

    var days   = Math.ceil((endDatetime - startDatetime) / ONE_DAY);
    minTimeGap = Math.max(1, Math.ceil(minTimeGap / ONE_DAY));

    /* Update gantt plans and bars */
    $.each(plans, function(index, plan)
    {
        var $plan = $('<div class="gantt-plan"></div>');
        $plan.append('<div class="strong" title="' + plan.name + '">' + plan.text + '</div>');
        $plans.append($plan);

        var $bar = $('<div class="gantt-bar"></div>');
        $('<div class="gantt-bar-progress bg-primary"></div>').css(
        {
            width: plan.progress + '%',
            background: themeColor,
        }).appendTo($bar);
        $bar.append('<div class="gantt-task-info text-muted small">' + taskLang + ' ' + plan.completeTasks.length + '/' + plan.tasks.length + '</div>').attr('title', zui.formatDate(plan.startDatetime, 'yyyy-MM-dd') + '~' + zui.formatDate(plan.endDatetime, 'yyyy-MM-dd'));
        var $row = $('<div class="gantt-row" data-id="' + plan.id + '"></div>').append($bar);
        $ganttCanvas.append($row);
    });

    /* Layout gantt container */
    $ganttContainer.css('left', $plans.width() + 15);
    $ganttCanvas.css('height', canvasHeight);

    /* Layout gantt */
    var minWidth = $ganttContainer.width();
    var timeGap = minTimeGap < TIME_GAP_STEP ? minTimeGap : Math.floor(minTimeGap / TIME_GAP_STEP) * TIME_GAP_STEP;
    var colsCount = Math.ceil(days / timeGap);
    var canvasWidth = Math.max(minWidth, colsCount * MIN_COL_WIDTH);
    var colWidth = Math.floor(canvasWidth / colsCount);
    var pxPerMs = colWidth / (timeGap * ONE_DAY);
    $ganttCanvas.css('width', canvasWidth).find('.gantt-col').remove();
    for (var i = 0; i < colsCount; ++i)
    {
        var $col = $('<div class="gantt-col"></div>');
        $col.css(
        {
            left:   i * colWidth,
            width:  colWidth,
            height: canvasHeight
        });
        var colTime = startDatetime + i * timeGap * ONE_DAY;
        $col.append('<div class="gantt-col-time text-muted small">' + zui.formatDate(colTime, 'MM/dd') + '</div>');
        $ganttCanvas.append($col);
    }

    $.each(plans, function(index, plan)
    {
        var $planRow = $gantt.find('.gantt-row[data-id="' + plan.id + '"]');
        $planRow.find('.gantt-bar').css(
        {
            left:  Math.floor((plan.startDatetime - startDatetime) * pxPerMs),
            width: Math.floor((plan.endDatetime - plan.startDatetime) * pxPerMs)
        });
    });

    $gantt.find('.gantt-today').css('left', (Date.now() - startDatetime) * pxPerMs);

    /**
     * Create date from string
     *
     * @param {String} dateStr like '2020-08-02'
     * @return {Number} Date timestramp
     */
    function createDatetime(dateStr)
    {
        dateStr   = dateStr.split('-');
        var year  = Number.parseInt(dateStr[0].length > 3 ? dateStr[0] : dateStr[2], 10);
        var month = Number.parseInt(dateStr[1], 10);
        var day   = Number.parseInt(dateStr[2].length > 3 ? dateStr[0] : dateStr[2], 10);
        return new Date(year, month - 1, day).getTime();
    }
}
