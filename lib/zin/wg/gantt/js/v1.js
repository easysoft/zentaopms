let scriptLoadedMap = {};
let pointAttr       = null;

/**
 * Get remote script for export.
 *
 * @param  string $url
 * @param  function $successCallback
 * @param  function $errorCallback
 * @access public
 * @return void
 */
function getRemoteScript(url, successCallback, errorCallback)
{
    if(scriptLoadedMap[url]) return successCallback && successCallback();

    scriptLoadedMap[url] = true;

    let script = document.createElement('script');
    script.type = 'text/javascript';
    script.onload = function() { successCallback(); };
    script.src = url;
    document.head.appendChild(script);
}

/**
 * Update export progress.
 *
 * @param  int $progress
 * @access public
 * @return void
 */
function updateProgress(progress)
{
    let progressText = window.loadedGanttLang.exporting;
    if(progress < 1) progressText += Math.floor(progress * 100) + '%';
    $('#mainContent').attr('data-loading', progressText);
}

/**
 * Draw gantt to canvas.
 *
 * @param  string   $exportType
 * @param  function $successCallback
 * @param  function $errorCallback
 * @access public
 * @return void
 */
function drawGanttToCanvas(exportType, successCallback, errorCallback)
{
    updateProgress(0);

    let ganttData = options;
    if(typeof(options) == 'string') ganttData = JSON.parse(options);

    exportType = exportType || 'image';
    let $ganttView      = $('#' + ganttID);
    let oldHeight       = $ganttView.css('height');
    let $ganttContainer = $('#ganttContainer');
    let $ganttDataArea  = $ganttView.find('.gantt_data_area');
    let $ganttDridData  = $ganttView.find('.gantt_grid_data');

    let ganttDridWidth = $ganttDridData.width();
    let ganttAreaWidth = $ganttDataArea.length > 0 ? $ganttDataArea.width() : 0;
    let oldDridWidth   = $ganttView.find('.grid_cell').width();

    let ganttHeadHeight = 66;
    let ganttRowHeight  = $ganttView.find('.gantt_row').first().height() || 25;
    let ganttHeight     = ganttHeadHeight + (ganttData.data.length ? ganttData.data.length : 0) * (ganttRowHeight + 3);
    let ganttWidth      = ganttDridWidth + ganttAreaWidth;

    $ganttContainer.css(
    {
        height: ganttHeight + 80,
        width: ganttWidth + 100
    });
    $ganttView.css('height', ganttHeight);

    updateProgress(0.1);
    getRemoteScript(jsRoot + 'js/html2canvas/min.js', function()
    {
        updateProgress(0.2);
        let afterFinish = function(canvas)
        {
            $ganttContainer.css({width: '', height: ''});
            $ganttView.css('height', oldHeight);
            window.setLayout(oldDridWidth, true);
            gantt.resetLayout();
            gantt.showDate(new Date());
            window.setTodayMarker();
            window.addBaselineLayer();
            $('#mainContent #ganttDownload').remove();

            if(!canvas) return;
            try
            {
                canvas.removeNode(true)
            }
            catch(err)
            {
                canvas.remove()
            };
        };
        let delayTime = Math.max(1000, Math.floor(10 * (ganttHeight * ganttWidth) / 100000));
        let progressTimer;
        if(delayTime > 1500)
        {
            let startProgress = 0.2;
            let deltaProgress = 0.5 / Math.floor(delayTime/1000);
            progressTimer = setInterval(function()
            {
                startProgress += deltaProgress;
                updateProgress(Math.min(0.7, startProgress));
            }, 1000);
        }

        window.setLayout(ganttDridWidth, false);
        gantt.resetLayout();
        setTimeout(function()
        {
            window.setTodayMarker();
            window.addBaselineLayer();
            gantt.render();

            if(progressTimer) clearInterval(progressTimer);
            updateProgress(0.7);
            html2canvas($ganttContainer[0], {logging: false, scale: 1}).then(function(canvas)
            {
                let isExportPDF = exportType === 'pdf';
                updateProgress(isExportPDF ? 0.8 : 0.9);
                canvas.onerror = function()
                {
                    afterFinish(canvas);
                    if(errorCallback) errorCallback('Cannot convert image to blob.');
                };
                if(isExportPDF)
                {
                    let width = canvas.width;
                    let height = canvas.height;
                    getRemoteScript(jsRoot + 'js/pdfjs/min.js', function()
                    {
                        updateProgress(0.8);
                        let pdf = new jsPDF(
                        {
                            format: [width, height],
                            unit: 'px',
                            orientation: width > height ? 'l' : 'p'
                        });
                        pdf.addImage(canvas, 0, 0, pdf.internal.pageSize.getWidth(), pdf.internal.pageSize.getHeight());
                        pdf.save(exportFileName + '.pdf');
                        if(successCallback) successCallback(pdf);
                        afterFinish();
                    }, function(error)
                    {
                        afterFinish(canvas);
                        if(errorCallback) errorCallback(error);
                    });
                }
                else
                {
                    canvas.toBlob(function(blob)
                    {
                        updateProgress(0.95);
                        let imageUrl = URL.createObjectURL(blob);
                        if(navigator.msSaveBlob)
                        {
                            navigator.msSaveOrOpenBlob(blob, exportFileName + '.png');
                        }
                        else
                        {
                            $('#mainContent').append('<a id="ganttDownload" style="display:none;" download="' + exportFileName + '.png" target="_blank" href="' + imageUrl + '"></a>');
                            $('#mainContent #ganttDownload')[0].click();
                        }
                        if(successCallback) successCallback(imageUrl);
                        afterFinish(canvas);
                    });
                }
            }).catch(function(error)
            {
                afterFinish();
                if(errorCallback) errorCallback('Cannot draw graphic to canvas.');
            });
        }, delayTime);
    }, errorCallback);
}

/**
 * Export gantt.
 *
 * @param  string $exportType
 * @access public
 * @return void
 */
window.exportGantt = function(exportType)
{
    let $mainContent = $('#mainContent');
    $mainContent.addClass('load-indicator').addClass('loading').css('height', Math.max(200, Math.floor($(window).height() - $('#header').outerHeight() - $('#mainMenu').outerHeight())));
    $('#main').removeClass('load-indicator');
    let afterFinish = function(url)
    {
        setTimeout(function()
        {
            $mainContent.css('height', '').removeClass('loading').removeClass('load-indicator');
            $('#main').addClass('load-indicator');
        }, 300);
        updateProgress(1);
    };
    drawGanttToCanvas(exportType, afterFinish, function(errorText)
    {
        afterFinish();
        zui.Messager.show({content: window.loadedGanttLang.exportFail + (errorText || ''), type: 'danger-outline'});
    });
}

/**
 * Get by id for gantt.
 *
 * @param  array  $list
 * @param  string $id
 * @access public
 * @return string
 */
function getByIdForGantt(list, id)
{
    for(let i = 0; i < list.length; i++)
    {
        if (list[i].key == id) return list[i].label || "";
    }
    return id;
}

/**
 * Update criticalPath
 *
 * @access public
 * @return void
 */
window.updateCriticalPath = function()
{
    let showCriticalPath = !gantt.config.highlight_critical_path;

    $('#criticalPath').toggleClass('bg-gray-300', showCriticalPath).attr('title', showCriticalPath ? window.loadedGanttLang.hideCriticalPath : window.loadedGanttLang.showCriticalPath);
    gantt.config.highlight_critical_path = showCriticalPath;

    gantt.render();
}

window.scrollToDate = function(date)
{
    gantt.showDate(date);
}

window.scrollToToday = function()
{
    if($('.gantt .gantt_marker.today').length == 0) return zui.Modal.alert(window.loadedGanttLang.warningNoToday);
    scrollToDate(new Date());
}

function exitHandler()
{
    if(module == 'review' && method == 'assess' && !document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement)
    {
        loadCurrentPage();
    }
}

/* 父级的开始结束跟着子级变动。 */
function changeParentTask(child, id)
{
    let task = gantt.getTask(id);
    if(task.type != 'task') return false;

    if(child.start_date < task.start_date) task.start_date = child.start_date;
    if(child.end_date   > task.end_date)   task.end_date   = child.end_date;

    let from      = new Date(task.start_date);
    let to        = new Date(task.end_date);
    task.begin    = from;
    task.deadline = (new Date(to.valueOf() - 1));

    gantt.updateTask(task.id, task);
    if(task.parent) changeParentTask(task, task.parent);
}

/* Validate task drag. */
function validateResources(id)
{
    let task   = gantt.getTask(id);
    let from   = new Date(task.start_date);
    let to     = new Date(task.end_date);
    let status = task.status;
    let type   = task.type;
    if(typeof type == 'undefined') return false;

    task.begin    = from;
    task.deadline = (new Date(to.valueOf() - 1));

    let itemID;
    if(type == 'task')
    {
        itemID = task.id;
        if(itemID.toString().indexOf('-') != -1) itemID = task.id.split("-")[1];
        if($('.scheduleBox [name=auto]:checked').length) return taskManualSchedule(task, itemID, from.toLocaleDateString('en-CA'), to.toLocaleDateString('en-CA'));
    }
    if(type == 'plan')  itemID = task.id;
    if(type == 'point') itemID = task.id.split("-")[2];

    /* Check data. */
    const postData = 'id=' + itemID + '&startDate=' + from.toLocaleDateString('en-CA') + '&endDate=' + to.toLocaleDateString('en-CA') + '&type=' +type;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', $.createLink('programplan', 'ajaxResponseGanttDragEvent'), false); // 同步模式
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(postData);

    let   flag     = true;
    const response = JSON.parse(xhr.responseText);
    if(response.result == 'fail' && response.message)
    {
        let message = response.message;
        if(typeof message == 'object') message = Object.values(message)[0];
        zui.Messager.show({content: message, type: 'danger-outline', icon: 'exclamation-sign'});
        flag = false;
    }
    else
    {
        gantt.updateTask(task.id, task);
        if(task.parent) changeParentTask(task, task.parent);
    }

    return flag;
};

/**
 * 任务手动排期。
 *
 * @param  object $task
 * @param  int    $taskID
 * @param  string $estStarted
 * @param  string $deadline
 * @access public
 * @return bool
 */
function taskManualSchedule(task, taskID, estStarted, deadline)
{
    const minBuffering = $('.scheduleBox [name=minBuffering]').val();
    estStarted = Math.round(Date.parse(estStarted) / 1000);
    deadline   = Math.round(Date.parse(deadline) / 1000);

    /* 对任务进行自动排期，并获取排期结果。*/
    const xhr = new XMLHttpRequest();
    xhr.open('GET', $.createLink('task', 'ajaxManualSchedule', `taskID=${taskID}&estStarted=${estStarted}&deadline=${deadline}&minBuffering=${minBuffering}`), false); // 同步模式
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send();

    let   flag     = true;
    const response = JSON.parse(xhr.responseText);
    if(response.result == 'success')
    {
        const from = config.currentModule == 'execution' ? 'execution' : 'project';
        /* 如果由于当前任务数据变动，导致需要修改其他任务/执行的起止日期，则弹窗提示是否要修改数据；若取消修改，则当前任务的起止日期也不进行修改。*/
        if(typeof response.message !== 'undefined')
        {
            flag = false;
            zui.Modal.confirm(response.message).then(result =>
            {
                if(result)
                {
                    const form = new FormData();
                    form.append('data', JSON.stringify(response.data));
                    $.ajaxSubmit({url: $.createLink('project', 'ajaxSaveTaskSchedule', `type=manual&from=${from}`), data: form});
                }
            });
        }
        /* 若修改当前任务的起止日期后，不会对其他任务/执行的起止日期进行修改，则更新当前任务的起止日期。*/
        else
        {
            const form = new FormData();
            form.append('data', JSON.stringify(response.data));
            $.ajaxSubmit({url: $.createLink('project', 'ajaxSaveTaskSchedule', `type=manual&from=${from}`), data: form});
        }
    }
    else
    {
        flag = false;
        zui.Modal.alert(response.message);
    }
    $('.scheduleBox menu').removeClass('show');
    return flag;
}

function isWorkTime(date)
{
    const day        = date.getDay();
    const formatDate = gantt.date.date_to_str("%Y-%m-%d")(date);
    if(weekend.weekend != 0 && (day == 0 || day == 6))
    {
        if(workingDays.includes(formatDate)) return true;
        if(weekend.weekend == 2 && (day == 0 || day == 6)) return false;
        if(weekend.weekend == 1 && weekend.restDay == day) return false;
    }

    if(holidays.includes(formatDate)) return false;
    return true;
};

function setScalesByZoom(zooming)
{
    const isZH        = (config.clientLang == 'zh-cn' || config.clientLang == 'zh-tw');
    const weekLang    = window.loadedGanttLang.zooming['week'];
    const quarterLang = window.loadedGanttLang.zooming['quarter'];

    if(zooming == 'day')     gantt.config.scales = [{unit: "year", step: 1, format: "%Y"}, {unit: 'month',   step: 1, format: '%M'}, {unit: 'day', step: 1, format: '%d', css: function(date) { if(!isWorkTime(date)) return 'weekend'; }}];
    if(zooming == "week")    gantt.config.scales = [{unit: "year", step: 1, format: "%Y"}, {unit: 'month',   step: 1, format: '%M'}, {unit: 'week', step: 1, format: isZH ? ("%W" + weekLang) : weekLang + " %W"}];
    if(zooming == "month")   gantt.config.scales = [{unit: "year", step: 1, format: "%Y"}, {unit: 'quarter', step: 1, format: function(date) { const quarter = (Math.floor(date.getMonth() / 3) + 1); return isZH ? (quarter + quarterLang) : quarterLang + " " + quarter; }}, {unit: 'month', step: 1, format: '%M'}];
    if(zooming == "quarter") gantt.config.scales = [{unit: "year", step: 1, format: "%Y"}, {unit: 'quarter', step: 1, format: function(date) { const quarter = (Math.floor(date.getMonth() / 3) + 1); return isZH ? (quarter + quarterLang) : quarterLang + " " + quarter; }}];
    gantt.config.min_column_width = 70;
    gantt.config.scale_height = 22 * gantt.config.scales.length;
}

window.zoomTasks = function(zooming)
{
    setScaleByZoom(zooming);
    gantt.render();
    $('.gantt_grid_head_cell .sort').addClass(value);
}

function waitGantt(callback)
{
    let timer = setInterval(function()
    {
        if(typeof(gantt) != 'undefined')
        {
            clearInterval(timer);
            callback();
        }
        else
        {
            zui.Gantt.loadModule();
        }
    }, 300);
}

/**
 * Toggle all tasks expand/collapse
 * @access public
 * @return void
 */
window.toggleAllTasks = function()
{
    const isCollapsed = $('.gantt').hasClass('is-collapsed');
    let   parent      = getSplitState(root);
    gantt.eachTask(function(task)
    {
        task.$open = isCollapsed;
        if(gantt.hasChild(task.id)) parent[appTab][root][ganttType][task.id] = isCollapsed;
    });
    gantt.render();
    zui.store.set('ganttSplitState', JSON.stringify(parent));
    $('.gantt').toggleClass('is-collapsed', !isCollapsed);
}

function initGantt()
{
    window.globalProject   = projectID;
    window.globalRoot      = root;
    window.loadedGanttLang = ganttLang;
    gantt.plugins({marker: true, critical_path: true, fullscreen: true, tooltip: true, click_drag: true});

    // Set gantt view height
    let gridDateToStr = gantt.date.date_to_str("%Y-%m-%d");
    let resizeGanttView = function()
    {
        if(gantt.getState().fullscreen) return false;

        let ganttHeight = $(window).height() - $('#header').height();
        if($('#mainNavbar').length)    ganttHeight -= $('#mainNavbar').height();
        if($('#mainMenu').length)      ganttHeight -= $('#mainMenu').height();
        if($('.detail-header').length) ganttHeight -= $('.detail-header').height();
        ganttHeight -= 30;

        if(height) ganttHeight = height;
        $('#' + ganttID).css('height', Math.max(200, ganttHeight));
    };

    ganttData = options;
    if(!ganttData) ganttData = {};
    if(!ganttData.data) ganttData.data = [];

    gantt.serverList("userList", userList);

    gantt.config.readonly            = canGanttEdit ? false : true;
    gantt.config.details_on_dblclick = false;
    gantt.config.order_branch        = ganttType == 'assignedTo' ? false : true;
    gantt.config.drag_progress       = false;
    gantt.config.drag_links          = true;
    gantt.config.drag_move           = true;
    gantt.config.drag_resize         = true;
    gantt.config.smart_rendering     = true;
    gantt.config.smart_scales        = true;
    gantt.config.static_background   = true;
    gantt.config.show_task_cells     = true;
    gantt.config.bar_height          = 24;
    gantt.config.row_height          = 32;
    gantt.config.details_on_create   = false;
    gantt.config.duration_unit       = "day";
    setScalesByZoom(zooming);
    gantt.config.show_chart = showChart;

    let gridWidth = zui.store.get('ganttGridWidth');
    if(gridWidth)
    {
        gridWidth = JSON.parse(gridWidth);
        gridWidth = gridWidth[window.globalRoot] ? gridWidth[window.globalRoot] : 0;
    }
    window.setLayout(gridWidth ? gridWidth : colsWidth, true);
    window.setGanttColumns();

    gantt.locale.labels = {
        ...gantt.locale.labels,
        ...ganttFields
    };

    gantt.templates.grid_folder    = function(item) { return "" };
    gantt.templates.grid_file      = function(item) { return "" };
    gantt.templates.task_class     = function(start, end, task){return 'pri-' + (task.pri || 0);};
    gantt.templates.rightside_text = function(start, end, task)
    {
        let ownerID = task.owner_id || task.ownerID;
        if(ownerID == undefined) return;
        if(task.type == 'point') return "<span class='status-" + task.rawStatus + "'>" + task.status + '</span>';
        return getByIdForGantt(gantt.serverList('userList'), ownerID);
    };
    gantt.templates.timeline_cell_class = function(item, date)
    {
        if(zooming != 'day') return;
        if(!isWorkTime(date)) return 'weekend';
    };

    gantt.templates.link_class = function(link)
    {
        let types = gantt.config.links;
        if(link.type == types.finish_to_start)  return 'finish_to_start';
        if(link.type == types.start_to_start)   return 'start_to_start';
        if(link.type == types.finish_to_finish) return 'finish_to_finish';
        if(link.type == types.start_to_finish)  return 'start_to_finish';
    };

    gantt.templates.tooltip_text = function (start, end, task)
    {
        return task.text;
    };

    gantt.templates.task_end_date = function(date)
    {
        return gantt.templates.task_date(new Date(date.valueOf() - 1));
    }

    gantt.templates.grid_date_format = function(date, column)
    {
        if(column === "end_date") return gridDateToStr(new Date(date.valueOf() - 1));
        return gridDateToStr(date);
    }

    if(!gantt.bindedEvents)
    {
        gantt.bindedEvents = true;
        gantt.attachEvent('onTemplatesReady', function()
        {
            $('#fullScreenBtn').off('click').on('click', function()
            {
                gantt.ext.fullscreen.toggle();
            });
        });

        gantt.attachEvent("onExpand", function()
        {
            $('body').addClass('gantt-fullscreen');
        });
        gantt.attachEvent("onCollapse", function()
        {
            $('body').removeClass('gantt-fullscreen');
            gantt.render();
        });

        gantt.attachEvent("onBeforeTaskMove", function(id, mode, e)
        {
            task = gantt.getTask(id);
            if(task.type == 'point') return false;
        });

        gantt.attachEvent("onGridResizeEnd", function(old_width, new_width)
        {
            let gridWidth = zui.store.get('ganttGridWidth');
            if(gridWidth)  gridWidth = JSON.parse(gridWidth);
            if(!gridWidth) gridWidth = {};
            gridWidth[window.globalRoot] = new_width;
            zui.store.set('ganttGridWidth', JSON.stringify(gridWidth));
        });

        // Show task in modal on click task
        gantt.attachEvent('onTaskClick', function(id, e)
        {
            let editBtn = $(e.srcElement || e.target);
            if(editBtn.hasClass('icon-common-edit')) editBtn = editBtn.parent();
            if(editBtn.hasClass('icon-confirm')) editBtn = editBtn.parent();

            // Check if clicked element is inside submitBtn
            if(!editBtn.hasClass('submitBtn') && editBtn.closest('.submitBtn').length) editBtn = editBtn.closest('.submitBtn');

            if(editBtn.hasClass('editDeadline'))
            {
                let $current     = editBtn.closest('.gantt_row');
                let $prev        = $current.prev();
                let reviewID     = id;
                let stageID      = id.split("-")[0];
                let stage        = gantt.getTask(stageID);
                let thisTask     = gantt.getTask($current.attr('task_id'));
                let prevTask     = gantt.getTask($prev.attr('task_id'));
                let stageEndDate = stage.endDate;
                let deadlineDate = thisTask.deadline;
                let minDate      = prevTask.type == 'point' ? prevTask.deadline : stage.begin;

                const deadlinePicker = $('#changeDeadlineModal [name="deadline"]').zui('datePicker');
                const options        = deadlinePicker.options
                options.weekStart    = 1;
                options.minDate      = new Date(minDate);
                if(stageEndDate) options.maxDate = new Date(stageEndDate);

                deadlinePicker.render(options);
                deadlinePicker.$.setValue(deadlineDate);

                $('#changeDeadlineModal [name="reviewID"]').val(reviewID);
                $('#changeDeadlineModal [name="projectID"]').val(window.globalProject);

                return false;
            }

            if(editBtn.hasClass('gantt_close') || editBtn.hasClass('gantt_open')) return false;

            let task = gantt.getTask(id);

            // Check for submit button first to prevent other actions
            if(editBtn.hasClass('icon-confirm') || editBtn.hasClass('submitBtn') || editBtn.find('.icon-confirm').length || editBtn.closest('.submitBtn').length || $(e.srcElement || e.target).closest('.submitBtn').length)
            {
                let pointAttr = JSON.parse($('#gantt_here').attr('data-reviewpoints'));
                let category  = id.split("-")[1];
                category = category.replace('point', '');

                if(pointAttr[category]['disabled'])
                {
                    zui.Messager.show({content: pointAttr[category]['message'], type: 'danger-outline', icon: 'exclamation-sign'});
                    return false;
                }

                const reviewID  = task.reviewID;
                const reviewURL = reviewID != 0 ? $.createLink('review', 'submit', 'reviewID=' + reviewID) : $.createLink('review', 'create', 'projectID=' + window.globalProject + '&deliverable=0&reviewID=' + reviewID + '&type=decision&objectID=' + category);
                zui.Modal.open({
                    url: reviewURL,
                    modal: true
                });
                if(e && e.preventDefault) e.preventDefault();
                if(e && e.stopPropagation) e.stopPropagation();
                return false;
            }

            if(task.type == 'point' && task.rawStatus)
            {
                if(!canViewReview) return false;
                loadPage($.createLink('review', 'view', 'reviewID=' + task.reviewID));
            }
            if(task.type == 'plan' && !task.isParent && !(task.executionType == 'kanban' && task.isTpl == '1'))
            {
                if(!canViewTaskList) return false;
                $.apps.open($.createLink('execution', 'task', 'id=' + task.id), 'execution');
            }

            /* The id of task item is like executionID-taskID. e.g. 1507-37829, 37829 is task id. */
            let taskID   = id;
            let position = id.indexOf('-');
            if(position > 0) taskID = parseInt(id.substring(position + 1));
            if(typeof task.type != 'undefined' && task.type != 'task') taskID = 0;

            if(!isNaN(taskID) && taskID > 0)
            {
                if(!canViewTask) return false;
                zui.Modal.open({url: $.createLink('task', 'view', 'taskID=' + taskID), 'size': 'lg'});
            }
        });

        gantt.attachEvent("onBeforeRowDragEnd", function(id, parent, tindex)
        {
            let tasks = gantt.getChildren(parent);
            let task  = gantt.getTask(id);
            let link  = $.createLink('programplan', 'ajaxResponseGanttMoveEvent');

            //prevent moving to another position.
            if(task.parent != parent || id.indexOf('-') == -1) return false;

            $.post(link, {id: id, 'tasks[]': tasks});
            return true;
        });

        /* 默认折叠所有任务。*/
        gantt.attachEvent("onTaskLoading", function(task)
        {
            if(typeof task.planned_start == 'string')
            {
                const parsedDate = task.planned_start.split('-');
                task.planned_start = new Date(parsedDate[2], parseInt(parsedDate[1]) - 1, parsedDate[0]);
            }
            if(typeof task.planned_end == 'string')
            {
                const parsedDate = task.planned_end.split('-');
                task.planned_end = new Date(parsedDate[2], parseInt(parsedDate[1]) - 1, parsedDate[0]);
            }

            task.$open = false;
            return true;
        });

        /* 监听任务展开事件。 */
        gantt.attachEvent("onTaskOpened", function(id) {
            $('#ganttView').removeClass('is-collapsed');
            saveSplitState(id, true);
            return true;
        });

        /* 监听任务折叠事件。 */
        gantt.attachEvent("onTaskClosed", function(id) {
            saveSplitState(id, false);

            let allCollapsed = true;
            gantt.eachTask(function(task) {
                if(task.$open && task.parent == 0) { // 只判断顶级是否折叠
                    allCollapsed = false;
                    return false;
                }
            });

            if(allCollapsed) $('#ganttView').addClass('is-collapsed');
            return true;
        });

        /* Link attachEvent onAfterTaskDrag */
        gantt.attachEvent("onBeforeTaskChanged", function(id, mode, task){return validateResources(id);});

        gantt.attachEvent("onRowDragStart", function(id, target, e)
        {
            //any custom logic here
            let task = gantt.getTask(id);
            if(task.type != 'task') return false;
            return true;
        });

        /* 添加关联关系前的校验。 */
        gantt.attachEvent("onBeforeLinkAdd", function(id, link)
        {
            const sourceTask = gantt.getTask(link.source);
            const targetTask = gantt.getTask(link.target);
            if(sourceTask.type != 'task' || targetTask.type != 'task')
            {
                let message = window.loadedGanttLang.wrongRelation;
                if(sourceTask.type != 'task') message += window.loadedGanttLang.wrongRelationSource;
                if(targetTask.type != 'task') message += window.loadedGanttLang.wrongRelationTarget;
                zui.Messager.show({content: message, type: 'danger-outline', icon: 'exclamation-sign'});

                return false;
            }

            if(sourceTask.allowLinks === false || targetTask.allowLinks === false)
            {
                zui.Messager.show({content: window.loadedGanttLang.wrongKanbanTasks, type: 'danger-outline', icon: 'exclamation-sign'});
                return false;
            };

            return true;
        });

        /* 添加关联关系。 */
        gantt.attachEvent("onAfterLinkAdd", function(id, item)
        {
            let sourceData = item.source;
            let target     = item.target;
            if(sourceData.indexOf("-") > 0) sourceData = item.source.split("-")[1];
            if(target.indexOf("-") > 0)     target     = item.target.split("-")[1];

            const type          = +item.type;
            const conditionData = (type == 1 || type == 3) ? 'begin' : 'end';
            const actionData    = (type == 1 || type == 0) ? 'begin' : 'end';
            const pretask       = {1:sourceData}
            const condition     = {1:conditionData}
            const task          = {1:target}
            const action        = {1:actionData}
            const relation      = {pretask, condition, task, action};
            const link          = $.createLink('execution', 'ajaxMaintainRelation', 'projectID=' + window.globalProject + '&executionID=0');
            $.post(link, relation, function(response)
            {
                response = JSON.parse(response);
                if(response.result == 'fail' && response.message)
                {
                    zui.Messager.show({content: response.message, type: 'danger-outline', icon: 'exclamation-sign'});
                    gantt.deleteLink(id);
                }
                else
                {
                    gantt.changeLinkId(id, response.linkID);
                }
            });
        });

        /* 双击删除关联关系。 */
        gantt.attachEvent("onLinkDblClick", function(id, e, b)
        {
            zui.Modal.confirm({message: window.loadedGanttLang.deleteRelation}).then((res) =>
            {
                if(res)
                {
                    $.post($.createLink('programplan', 'ajaxResponseGanttDeleteRelationEvent'), {id: id});
                    gantt.deleteLink(id);
                }
            });
            return false;
        });
    }

    resizeGanttView();
    $(window).off('.gannt').on('resize.gannt', function(){resizeGanttView()});

    /* 渲染甘特图时先清空原有数据。 */
    gantt._clear_data();

    gantt.init(ganttID);
    gantt.parse(ganttData);
    gantt.showDate(new Date());
    window.setTodayMarker();
    window.addBaselineLayer();
    initGanttSplitState();

    // Make folder can open or close by click
    $('#' + ganttID).off('.gannt').on('click.gannt', '.gantt_close,.gantt_open', function()
    {
        let $task = $(this).closest('.gantt_row_task');
        let task  = gantt.getTask($task.attr('task_id'));
        if(task) gantt[task.$open ? 'close' : 'open'](task.id);
    }).on('click.gannt', '.gantt_task_row', function() // 点击任务行空白区域，自动定位到任务开始时间。
    {
        let $task = $(this);
        let task  = gantt.getTask($task.attr('task_id'));
        gantt.showDate(task.start_date);
    });

    $('#ganttContainer').off('.gannt').on('mouseleave.gannt', function()
    {
        setTimeout(function(){$('.gantt_tooltip').remove()}, 100);
    });

    window.onPageUnmount = function()
    {
        $(window).off('.gannt');
        $('.gantt_tooltip').remove();
    };
}

$(function()
{
    waitGantt(function(){initGantt();});
});

window.saveDeadline = function()
{
    const deadline  = $('#changeDeadlineModal [name="deadline"]').val();
    const reviewID  = $('#changeDeadlineModal [name="reviewID"]').val();
    const projectID = $('#changeDeadlineModal [name="projectID"]').val();
    $.post($.createLink('review', 'ajaxChangeTRDeadline'), {'deadline' : deadline, 'id' : reviewID , 'projectID' : projectID}, function()
    {
        loadCurrentPage();
    });
};

window.getSplitState = function(root)
{
    let ganttSplitState = zui.store.get('ganttSplitState');
    if(ganttSplitState)  ganttSplitState = JSON.parse(ganttSplitState);
    if(!ganttSplitState) ganttSplitState = {};
    if(!ganttSplitState[appTab]) ganttSplitState[appTab] = {};
    if(root && !ganttSplitState[appTab][root])
    {
        const objectKeys = Object.keys(ganttSplitState[appTab]);
        if(objectKeys.length > 20) delete ganttSplitState[appTab][objectKeys[0]]; // 保留最后20个。 Keep the last 20.

        ganttSplitState[appTab][root] = {};
    }
    if(!ganttSplitState[appTab][root][ganttType]) ganttSplitState[appTab][root][ganttType] = {};

    return ganttSplitState;
}

window.saveSplitState = function(id, state)
{
    let ganttSplitState = getSplitState(root);

    ganttSplitState[appTab][root][ganttType][id] = state;
    zui.store.set('ganttSplitState', JSON.stringify(ganttSplitState));
};

window.initGanttSplitState = function()
{
    let ganttSplitState = zui.store.get('ganttSplitState');
    if(!ganttSplitState) return;

    ganttSplitState = JSON.parse(ganttSplitState);
    if(!ganttSplitState[appTab] || !ganttSplitState[appTab][root] || !ganttSplitState[appTab][root][ganttType]) return;
    $.each(ganttSplitState[appTab][root][ganttType], function(id, state)
    {
        if(!state) return;
        gantt.open(id);
    });
};

$(document).on('click', '#browseTypeList a.item-content', function()
{
    if(gantt.getState().fullscreen) gantt.ext.fullscreen.toggle();
});

window.setGanttColumns = function()
{
    let showOwnerField = showFields.includes('owner_id') || showFields.includes('ownerID') || showFields.includes('PM') || showFields.includes('assignedTo');
    gantt.config.columns = [];
    gantt.config.columns.push({name: 'text', width: '*', tree: true, resize: true, min_width:120, width:200});
    if(showFields.length != 0) gantt.config.columns.push({name:"custom", label:"", align: "left", width: 40, template: window.getSubmitBtn});
    if(showOwnerField)         gantt.config.columns.push({name: 'ownerID', align: 'left', resize: true, width: 80, template: function(task){return getByIdForGantt(gantt.serverList('userList'), task.owner_id || task.ownerID)}});
    if(showFields.includes('id'))
    {
        gantt.config.columns.push({name: 'id', align: 'center', resize: true, width: 80, template: function(task)
        {
            if(!isNaN(task.id)) return task.id > 0 ? task.id : '';
            if(task.id.indexOf('-') != -1)
            {
                let taskID = task.id.split('-').pop();
                if(!isNaN(taskID) && taskID > 0) return taskID;
            }
            return ''
        }});
    }
    if(showFields.includes('status'))    gantt.config.columns.push({name: 'status', align: 'center', resize: true, width: 80});
    if(showFields.includes('begin'))     gantt.config.columns.push({name: 'begin', align: 'center', resize: true, width: 80});
    if(showFields.includes('deadline'))  gantt.config.columns.push({name: 'deadline', align: 'center', resize: true, width: 80, template: window.getDeadlineBtn});
    if(showFields.includes('duration'))  gantt.config.columns.push({name: 'duration', align: 'center', resize: true, width: 120});
    if(showFields.includes('progress'))
    {
        gantt.config.columns.push({name: 'percent', align: 'center', resize: true, width:70, template: function(plan)
        {
            if(plan.percent)  return Math.round(plan.percent) + '%';
            if(plan.progress) return Math.round(plan.progress * 100) + '%';
        }});
    }
    if(showFields.includes('taskProgress')) gantt.config.columns.push({name: 'taskProgress', align: 'center', resize: true, width: 60});
    const fixedFields = ['text', 'id', 'owner_id', 'ownerID', 'PM', 'assignedTo', 'status', 'begin', 'deadline', 'duration', 'progress', 'delay', 'delayDays', 'taskProgress'];
    showFields.forEach(function(field)
    {
        if(fixedFields.includes(field)) return;
        gantt.config.columns.push({name: field, align: 'center', resize: true, width: 80});
    });
    if(showFields.includes('delay'))
    {
        gantt.config.columns.push({name: 'delay', align: 'center', resize: true, width: 60, template:function(item)
        {
            if(item.delayDays > 0) return "<div class='delayed'>" + item.delay + "</div>";
            return item.delay;
        }});
    }
    if(showFields.includes('delayDays')) gantt.config.columns.push({name: 'delayDays', align: 'center', resize: false, width: 60});

    endField = gantt.config.columns.pop();
    endField.resize = false;
    gantt.config.columns.push(endField);
};

window.getSubmitBtn = function(task)
{
    if(!canGanttEdit) return;
    if(task.type != 'point') return;

    if(!pointAttr) pointAttr = JSON.parse($('#gantt_here').attr('data-reviewpoints'));
    let category  = task.id.split("-")[1].replace('point', '');
    if(pointAttr[category]['disabled'] && !pointAttr[category]['message']) return;

    if(task.type == 'point' && (!task.rawStatus || task.rawStatus == 'draft')) return '<button class="btn btn-link submitBtn" title="' + window.loadedGanttLang.submit + '"><i class="icon-confirm"></i></button>';
};

window.getDeadlineBtn = function(task)
{
    let date = task.deadline;
    let gridDateToStr = gantt.date.date_to_str("%Y-%m-%d");
    if(task.type == 'point' && canEditDeadline && (!task.rawStatus || task.rawStatus == 'fail' || task.rawStatus == 'draft')) return "<table><tr><td class='deadlineBox'><span class='deadline'>" + gridDateToStr(new Date(date.valueOf())) + '</span> <a class="btn primary size-sm editDeadline" data-toggle="modal" data-target="#changeDeadlineModal" title="' + window.loadedGanttLang.edit + '"><i class="icon-common-edit icon-edit"></i></a></td></tr></table>';
    return date;
};

window.setLayout = function(colsWidth, colResize)
{
    if(!colsWidth) colsWidth = 700;
    if(!colResize) colResize = false;
    gantt.config.layout =
    {
        css: "gantt_container",
        cols:[{
                width: colsWidth,
                rows:[
                    {view: "grid", scrollX: "gridScroll", scrollable: true, scrollY: "scrollVer"},
                    {view: "scrollbar", id: "gridScroll", group:"horizontal"}
            ]},
            {resizer: colResize, width: 1},
            {
                rows:[
                    {view: "timeline", scrollX: "scrollHor", scrollY: "scrollVer"},
                    {view: "scrollbar", id: "scrollHor", group:"horizontal"}
            ]},
            {view: "scrollbar", id: "scrollVer"}
    ]};

};

window.setTodayMarker = function()
{
    $('.gantt .gantt_marker.today').each(function()
    {
        const todayMarker = $(this).attr('data-marker-id');
        gantt.deleteMarker(todayMarker);
    });

    let date2Str = gantt.date.date_to_str(gantt.config.task_date);
    let today    = new Date();
    gantt.addMarker({
        start_date: today,
        css: "today",
        text: window.loadedGanttLang.today,
        title: window.loadedGanttLang.today + ": " + date2Str(today)
    });
};

window.addBaselineLayer = function()
{
    gantt.addTaskLayer(
    {
        renderer:
        {
            render: function(task)
            {
                if(!(task.planned_start && task.planned_end)) return false;

                let sizes = gantt.getTaskPosition(task, task.planned_start, task.planned_end);
                let el    = document.createElement('div');
                el.className    = 'baseline bg-gray-300';
                el.style.left   = (sizes.left - 3) + 'px';
                el.style.width  = (sizes.width + 7) + 'px';
                el.style.height = (gantt.config.bar_height + 4) + 'px';
                el.style.top    = (sizes.top + 2) + 'px';
                return el;
            },
            // define getRectangle in order to hook layer with the smart rendering
            getRectangle: function(task, view)
            {
                if(task.planned_start && task.planned_end) return gantt.getTaskPosition(task, task.planned_start, task.planned_end);
                return null;
            }
        }
    });
};
