let scriptLoadedMap   = {};

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
    let progressText = ganttLang.exporting;
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

    exportType = exportType || 'image';
    let $ganttView      = $('#' + ganttID);
    let oldHeight       = $ganttView.css('height');
    let $ganttContainer = $('#ganttContainer');
    let $ganttDataArea  = $ganttView.find('.gantt_data_area');
    let $ganttDridData  = $ganttView.find('.gantt_grid_data');

    let ganttDridWidth  = $ganttDridData.width();
    let ganttAreaWidth  = $ganttDataArea.length > 0 ? $ganttDataArea.width() : 0;

    let ganttHeadHeight = 40;
    let ganttRowHeight  = $ganttView.find('.gantt_row').first().height() || 25;
    let ganttHeight     = ganttHeadHeight + (options['data'].length ? options['data'] .length : 0) * (ganttRowHeight + 3);
    let ganttWidth      = ganttDridWidth + ganttAreaWidth;

    $ganttContainer.css(
    {
        height: ganttHeight + 80,
        width: ganttWidth + 93
    });
    $ganttView.css('height', ganttHeight);

    let oldDridWidth = gantt.config.layout['cols'][0]['width'];
    gantt.config.layout['cols'][0]['width'] = ganttDridWidth;
    gantt.resetLayout();

    updateProgress(0.1);
    getRemoteScript(jsRoot + 'js/html2canvas/min.js', function()
    {
        updateProgress(0.2);
        let afterFinish = function(canvas)
        {
            $ganttContainer.css({width: '', height: ''});
            $ganttView.css('height', oldHeight);
            $('#mainContent #ganttDownload').remove();

            gantt.config.layout['cols'][0]['width'] = oldDridWidth;
            gantt.resetLayout();

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
        setTimeout(function()
        {
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
                        pdf.save(fileName + '.pdf');
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
                            navigator.msSaveOrOpenBlob(blob, fileName + '.png');
                        }
                        else
                        {
                            $('#mainContent').append('<a id="ganttDownload" style="display:none;" download="' + fileName + '.png" target="_blank" href="' + imageUrl + '"></a>');
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
        zui.Messager.show({content: ganttLang.exportFail + (errorText || ''), type: 'danger-outline'});
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
function updateCriticalPath()
{
    let showCriticalPath = !gantt.config.highlight_critical_path;

    $('#criticalPath').html(showCriticalPath ? ganttLang.hideCriticalPath : ganttLang.showCriticalP);
    gantt.config.highlight_critical_path = showCriticalPath;

    gantt.render();
}

function exitHandler()
{
    if (module == 'review' && method == 'assess' && !document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement)
    {
        loadCurrentPage();
    }
}

/* Validate task drag. */
function validateResources(id)
{
    let task = gantt.getTask(id);
    let from = new Date(task.start_date);
    let to   = new Date(task.end_date);
    let status = task.status;
    let type   = task.type;
    let statusLang = ganttLang.taskStatusList['wait'];
    flag = true;

    /* Check status. */
    if(status !== statusLang && type != 'point')
    {
        let tipMsg = ganttLang.errorPlanDrag;
        if(type == 'task') tipMsg = ganttLang.errorTaskDrag;

        zui.Messager.show({content: tipMsg.replace('%s', status), type: 'danger-outline', icon: 'exclamation-sign'});
        gantt.refreshData();
        return false;
    }

    task.begin    = from;
    task.deadline = (new Date(to.valueOf() - 1));;

    gantt.updateTask(task.id);
    let itemID;
    if(type == 'task')  itemID = task.id.split("-")[1];
    if(type == 'plan')  itemID = task.id;
    if(type == 'point') itemID = task.id.split("-")[2];

    /* Check data. */
    let postData = {
        'id'        : itemID,
        'startDate' : from.toLocaleDateString('en-CA'),
        'endDate'   : to.toLocaleDateString('en-CA'),
        'type'      : type
    };
    /* Sync Close. */
    $.ajax({
        url: $.createLink('programplan', 'ajaxResponseGanttDragEvent'),
        dataType: "json",
        data: postData,
        type: "post",
        success: function(response)
        {
            if(response.result == 'fail' && response.message)
            {
                zui.Messager.show({content: response.message, type: 'danger-outline', icon: 'exclamation-sign'});
                flag = false;
            }
        }
    });

    return flag;
}

function setScalesByZoom(zooming)
{
  if(zooming == 'day') gantt.config.scales = [{unit: "year", step: 1, format: "%Y"}, {unit: 'day', step: 1, format: '%m-%d'}];
  if(zooming == "week") gantt.config.scales = [{unit: "year", step: 1, format: "%Y"}, {unit: 'week', step: 1, format: ganttLang.zooming['week'] + " #%W"}, {unit:"day", step:1, date:"%D"}];
  if(zooming == "month") gantt.config.scales = [{unit: "year", step: 1, format: "%Y"}, {unit: 'month', step: 1, format: '%M'}, {unit:"week", step:1, date: ganttLang.zooming['week'] + " #%W"}];
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
    }, 300);
}

function initGantt()
{
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

    let getDeadlineBtn = function(task)
    {
        let date = task.deadline;
        //if(task.type == 'point' && canEditDeadline && (!task.rawStatus || task.rawStatus == 'fail' || task.rawStatus == 'draft')) return "<table><tr><td><span class='deadline'>" + gridDateToStr(new Date(date.valueOf())) + '</span> <a class="btn btn-primary editDeadline" title="' + ganttLang.edit + '"><i class="icon-common-edit icon-edit"></i> ' + ganttLang.edit + '</a></td></tr></table>';
        return date;
    }
    let getSubmitBtn = function(task)
    {
        if(task.type == 'point' && !task.rawStatus) return '<button class="btn btn-link submitBtn" title="' + ganttLang.submit + '"><i class="icon-confirm"></i></button>';
    }

    let isGanttExpand    = false;
    let delayTimer       = null;
    let handleFullscreen = function()
    {
        if(isGanttExpand)
        {
            $('body').addClass('gantt-fullscreen');
            $('#' + ganttID).css('height', $(window).height());
            isGanttExpand = false;
        }
        else
        {
            $('body').removeClass('gantt-fullscreen');
            resizeGanttView();
        }
        delayTimer = null;
    };
    let delayHandleFullscreen = function()
    {
        if(delayTimer) clearTimeout(delayTimer);
        delayTimer = setTimeout(handleFullscreen, 50);
    };

    let ganttData = options;
    if(!ganttData) ganttData = {};
    if(!ganttData.data) ganttData.data = [];

    gantt.serverList("userList", userList);

    gantt.config.readonly            = canGanttEdit ? false : true;
    gantt.config.details_on_dblclick = false;
    gantt.config.order_branch        = ganttType == 'assignedTo' ? false : true;
    gantt.config.drag_progress       = false;
    gantt.config.drag_links          = false;
    gantt.config.drag_move           = ganttType == 'assignedTo' ? false : true;
    gantt.config.drag_resize         = ganttType == 'assignedTo' ? false : true;
    gantt.config.smart_rendering     = true;
    gantt.config.smart_scales        = true;
    gantt.config.static_background   = true;
    gantt.config.show_task_cells     = false;
    gantt.config.row_height          = 32;
    gantt.config.details_on_create   = false;
    gantt.config.duration_unit       = "day";
    setScalesByZoom(zooming);
    if(!showChart) gantt.config.show_chart = false;

    gantt.config.columns = [];
    gantt.config.columns.push({name: 'text', width: '*', tree: true, resize: true, min_width:120, width:200});
    gantt.config.columns.push({name:"custom", label:"", align: "left", width: 40, template: getSubmitBtn});
    if(showFields.indexOf('PM') != -1) gantt.config.columns.push({name: 'owner_id', align: 'left', resize: true, width: 80, template: function(task){return getByIdForGantt(gantt.serverList('userList'), task.owner_id)}})
    if(showFields.indexOf('status') != -1) gantt.config.columns.push({name: 'status', align: 'center', resize: true, width: 80});
    gantt.config.columns.push({name: 'begin', align: 'center', resize: true, width: 80});
    if(showFields.indexOf('deadline') != -1) gantt.config.columns.push({name: 'deadline', align: 'center', resize: true, width: 140, template: getDeadlineBtn});
    gantt.config.columns.push({name: 'duration', align: 'center', resize: true, width: 60});
    if(showFields.indexOf('estimate') != -1) gantt.config.columns.push({name: 'estimate', align: 'center', resize: true, width: 60});
    if(showFields.indexOf('progress') != -1) gantt.config.columns.push({name: 'percent', align: 'center', resize: true, width:70, template: function(plan){ if(plan.percent) return Math.round(plan.percent) + '%';}});
    if(showFields.indexOf('taskProgress') != -1) gantt.config.columns.push({name: 'taskProgress', align: 'center', resize: true, width: 60});
    if(showFields.indexOf('realBegan') != -1) gantt.config.columns.push({name: 'realBegan', align: 'center', resize: true, width: 80});
    if(showFields.indexOf('realEnd') != -1) gantt.config.columns.push({name: 'realEnd', align: 'center', resize: true, width: 80});
    if(showFields.indexOf('consumed') != -1) gantt.config.columns.push({name: 'consumed', align: 'center', resize: true, width: 60});
    if(showFields.indexOf('delay') != -1)
    {
        gantt.config.columns.push({name: 'delay', align: 'center', resize: true, width: 60, template:function(item)
        {
            if(item.delayDays > 0) return "<div class='delayed'>" + item.delay + "</div>";
            return item.delay;
        }});
    }
    if(showFields.indexOf('delayDays') != -1) gantt.config.columns.push({name: 'delayDays', align: 'center', resize: false, width: 60});

    endField = gantt.config.columns.pop();
    endField.resize = false;
    gantt.config.columns.push(endField);

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

    gantt.locale.labels = ganttFields;

    let date2Str  = gantt.date.date_to_str(gantt.config.task_date);
    let today     = new Date();
    gantt.addMarker({
        start_date: today,
        css: "today",
        text: ganttLang.today,
        title: ganttLang.today + ": " + date2Str(today)
    });

    gantt.templates.grid_folder    = function(item) { return "" };
    gantt.templates.grid_file      = function(item) { return "" };
    gantt.templates.task_class     = function(start, end, task){return 'pri-' + (task.pri || 0);};
    gantt.templates.rightside_text = function(start, end, task)
    {
        if(typeof task.owner_id == 'undefined') return;
        if(task.type == 'point') return "<span class='status-" + task.rawStatus + "'>" + task.status + '</span>';
        return getByIdForGantt(gantt.serverList('userList'), task.owner_id);
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

    gantt.templates.task_end_date = function(data)
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

        gantt.attachEvent("onBeforeTaskMove", function(id, mode, e)
        {
            task = gantt.getTask(id);
            if(task.type == 'point') return false;
        });

        gantt.attachEvent('onBeforeExpand', function()
        {
            $('body').addClass('gantt-fullscreen');
            isGanttExpand = true;
            return true;
        });

        // Show task in modal on click task
        gantt.attachEvent('onTaskClick', function(id, e)
        {
            let editBtn = $(e.srcElement);
            if(editBtn.hasClass('icon-common-edit')) editBtn = editBtn.parent();

            if(editBtn.hasClass('editDeadline'))
            {
            let parentID     = id.split("-")[0];
            let stageEndDate = $("div[data-task-id='" + parentID + "']").find('div[data-column-name="end_date"] > .gantt_tree_content').text();
            let reviewID     = id;
            let deadlineDate = editBtn.prev().text();

            editBtn.datetimepicker(
            {
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0,
                format: "yyyy-mm-dd",
                startDate: new Date(),
                endDate: new Date(stageEndDate),
                initialDate: new Date(deadlineDate),
            })
            .on('changeDate', function(ev){
                let year  = ev.date.getFullYear();
                let month = ev.date.getMonth() + 1;
                let day   = ev.date.getUTCDate();
                let formattedDate = year + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;

                $.post($.createLink('review', 'ajaxChangeTRDeadline'), {'deadline' : formattedDate, 'id' : reviewID , 'projectID' : projectID}, function()
                {
                    location.reload();
                });

            })
            .on('show', function(){
                editBtn.css('display', 'inline-block');
            })
            .on('hide', function(){
                editBtn.css('display', 'none');
            });
            editBtn.datetimepicker('show');
            return false;
            }

            if(editBtn.hasClass('gantt_close') || editBtn.hasClass('gantt_open')) return false;

            let task = gantt.getTask(id);
            if(task.type == 'point' && task.rawStatus)
            {
                if(!canViewReview) return false;
                loadPage($.createLink('review', 'view', 'reviewID=' + task.reviewID));
            }
            if(task.type == 'plan' && !task.isParent)
            {
                if(!canViewTaskList) return false;
                $.apps.open($.createLink('execution', 'task', 'id=' + task.id), 'execution');
            }

            if(editBtn.hasClass('icon-confirm'))
            {
                let pointAttr = reviewPoints;
                let category  = id.split("-")[1];

                if(pointAttr[category]['disabled'])
                {
                    zui.Messager.show({content: pointAttr[category]['message'], type: 'danger-outline', icon: 'exclamation-sign'});
                    return false;
                }
                loadPage($.createLink('review', 'create', 'projectID=' + projectID + '&object=' + category));
            }

            /* The id of task item is like executionID-taskID. e.g. 1507-37829, 37829 is task id. */
            let taskID   = ganttType == 'assignedTo' ? id : 0;
            let position = id.indexOf('-');
            if(position > 0) taskID = parseInt(id.substring(position + 1));

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

        /* Link attachEvent onAfterTaskDrag */
        gantt.attachEvent("onBeforeTaskChanged", function(id, mode, task){return validateResources(id)});

        gantt.attachEvent("onRowDragStart", function(id, target, e)
        {
            //any custom logic here
            let task = gantt.getTask(id);
            if(task.type != 'task') return false;
            return true;
        });

        if(document.addEventListener)
        {
            document.addEventListener('fullscreenchange', exitHandler);
            document.addEventListener('webkitfullscreenchange', delayHandleFullscreen, false);
            document.addEventListener('mozfullscreenchange', delayHandleFullscreen, false);
            document.addEventListener('fullscreenchange', delayHandleFullscreen, false);
            document.addEventListener('MSFullscreenChange', delayHandleFullscreen, false);
        }
    }

    resizeGanttView();
    $(window).off('.gannt').on('resize.gannt', function(){resizeGanttView()});

    gantt.init(ganttID);
    gantt.parse(ganttData);
    gantt.showDate(new Date());

    // Make folder can open or close by click
    $('#' + ganttID).off('.gannt').on('click.gannt', '.gantt_close,.gantt_open', function()
    {
        let $task = $(this).closest('.gantt_row_task');
        let task  = gantt.getTask($task.attr('task_id'));
        if(task) gantt[task.$open ? 'close' : 'open'](task.id);
    });

    $('#ganttContainer').off('.gannt').on('mouseleave.gannt', function()
    {
        setTimeout(function(){$('.gantt_tooltip').remove()}, 100);
    });

    window.onPageUnmount = function()
    {
        $(window).off('.gannt');
        document.removeEventListener('fullscreenchange', exitHandler);
        document.removeEventListener('webkitfullscreenchange', delayHandleFullscreen, false);
        document.removeEventListener('mozfullscreenchange', delayHandleFullscreen, false);
        document.removeEventListener('fullscreenchange', delayHandleFullscreen, false);
        document.removeEventListener('MSFullscreenChange', delayHandleFullscreen, false);
    };
}

$(function()
{
    waitGantt(function(){initGantt();});
});
