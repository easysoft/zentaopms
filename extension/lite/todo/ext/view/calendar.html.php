<?php
/**
 * The view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     calendar
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getExtensionRoot() . 'biz/common/ext/view/calendar.html.php';?>
<style>
#todoLists .todo-item[data-type="todo"] {padding-left: 5px;}
#sidebar {width: 275px;}
#sidebar > .cell {width: 100%; left: 8px;}
#sidebar > .sidebar-toggle {left: 3px; right: auto;}
.hide-sidebar #sidebar > .cell {display: none;}
.hide-sidebar #sidebar > .sidebar-toggle > .icon:before {content: "\e314";}
#date {float: left; margin-right: 10px; margin-left: 0px;}
.cell .nav>li>a:focus {background-color: unset;}
</style>
<?php js::set('moreLang', $this->lang->side->more);?>
<?php js::set('moduleList', $config->todo->moduleList);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a('#', $lang->todo->todoCalendar, '', "class='btn btn-link'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('my', 'todo')):?>
    <div class="btn-group panel-actions">
      <?php echo html::a(helper::createLink('todo', 'calendar'), "<i class='icon-cards-view'></i> &nbsp;", '', "class='btn btn-icon text-primary' title='{$lang->todo->calendar}' id='switchButton'");?>
      <?php echo html::a(helper::createLink('my', 'todo', "type=all"), "<i class='icon-list'></i> &nbsp;", '', "class='btn btn-icon' title='{$lang->todo->list}' id='switchButton'");?>
    </div>
    <?php endif;?>
    <?php if(common::hasPriv('todo', 'export')) echo html::a('javascript:exportCalendar("' . helper::createLink('todo', 'export', "userID={$this->app->user->id}&orderBy=id_desc&date=_date_") . '")', "<i class='icon-export muted'> </i> " . $lang->todo->export, '', "class='btn btn-link'");?>
    <?php if(common::hasPriv('todo', 'create') or common::hasPriv('todo', 'batchCreate')):?>
    <div class='btn-group dropdown'>
    <?php common::printLink('todo', common::hasPriv('todo', 'create') ? 'create' : 'batchCreate', '', "<i class='icon icon-plus'></i> " . (common::hasPriv('todo', 'create') ? $lang->todo->create : $lang->todo->batchCreate), '', "id='create' class='btn btn-primary iframe' data-width='80%' data-app='my'", '', 'true');?>
    <?php if(common::hasPriv('todo', 'create') and common::hasPriv('todo', 'batchCreate')):?>
    <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
    <ul class='dropdown-menu pull-right'>
      <li><?php echo html::a($this->createLink('todo', 'create', '', '', true), $lang->todo->create, '', "class='iframe' data-width='80%'");?></li>
      <li><?php echo html::a($this->createLink('todo', 'batchCreate', '', '', true), $lang->todo->batchCreate, '', "class='iframe' data-width='80%'");?></li>
    </ul>
    <?php endif;?>
    </div>
    <?php endif;?>
  </div>
</div>
<div class="main-row">
  <div class="main-col">
    <div class="cell">
      <div id="todoCalendar" class="calendar">
        <header class="calender-header table-row">
          <div class="btn-toolbar col-4 table-col text-middle">
            <button type="button" class="btn btn-info btn-mini btn-today"><?php echo $lang->today;?></button>
            <button type="button" class="btn btn-info btn-icon btn-mini btn-prev"><i class="icon-chevron-left"></i></button>
            <span id="date" class="calendar-caption"></span>
            <button type="button" class="btn btn-info btn-icon btn-mini btn-next"><i class="icon-chevron-right"></i></button>
          </div>
        </header>
      </div>
    </div>
  </div>
  <div class="side-col" id="sidebar">
    <?php
    $todos = $this->dao->select('id,account,date,name,status,assignedTo')->from(TABLE_TODO)
        ->where('status')->in('wait,doing')
        ->andWhere('type')->ne('cycle')
        ->andWhere('assignedTo')->eq($this->app->user->account)
        ->andWhere('vision')->eq($this->config->vision)
        ->fetchAll();
    $undoneTodos = array();
    $futureTodos = array();
    foreach($todos as $todo)
    {
        if($todo->date == '2030-01-01')
        {
            $futureTodos[$todo->id] = $todo->name;
        }
        else
        {
            $undoneTodos[$todo->id] = $todo->name;
        }
    }
    ?>
    <div class="sidebar-toggle" style='left: 5px;'><i class="icon icon-angle-right"></i></div>
    <div class="cell">
      <ul class="nav nav-secondary nav-justified">
        <li class="active"><a href="#tab_undone" data-toggle='tab' class="object-calendar"><?php echo $lang->todo->periods['before'];?><span class="label label-light label-badge label-todo" id="'undoneTotal';?>" data-object="undone"><?php echo count($undoneTodos);?></span></a></li>
        <li><a href="#tab_future" data-toggle='tab' class="object-calendar"><?php echo $lang->todo->periods['future'];?><span class="label label-light label-badge label-todo hidden" id="'futureTotal';?>" data-object="future"><?php echo count($futureTodos);?></span></a></li>
        <li><a href="#tab_task" data-toggle='tab' class="object-calendar"><?php echo $lang->side->task;?><span class="label label-light label-badge label-todo hidden" id="taskTotal" data-object="task"><?php echo count($todoList['task']);?></span></a></li>
      </ul>
      <div class='tab-content' id="todoLists">
        <div class='tab-pane fade active in' id='tab_undone'>
          <?php if(!empty($undoneTodos)):?>
          <ul class='todo-list'>
          <?php $i = 1;?>
          <?php foreach($undoneTodos as $id => $todo):?>
            <li data-index='<?php echo $i++;?>'>
              <?php echo html::a($this->createLink('todo', 'view', "id=$id", 'html', true), $todo, '', "class='iframe todo-item' data-id='{$id}' data-type='todo' data-title='{$todo}' title='{$todo}' data-width='70%'");?>
            </li>
          <?php endforeach;?>
          </ul>
          <?php endif;?>
        </div>
        <div class='tab-pane fade' id='tab_future'>
          <?php if(!empty($futureTodos)):?>
          <ul class='todo-list'>
          <?php $i = 1;?>
          <?php foreach($futureTodos as $id => $todo):?>
            <li data-index='<?php echo $i++;?>'>
              <?php echo html::a($this->createLink('todo', 'view', "id=$id", 'html', true), $todo, '', "class='iframe todo-item' data-id='{$id}' data-type='todo' data-title='{$todo}' title='{$todo}' data-width='70%'");?>
            </li>
          <?php endforeach;?>
          </ul>
          <?php endif;?>
        </div>
        <div class='tab-pane fade' id='tab_task'>
          <?php if(!empty($todoList['task'])):?>
          <ul class='todo-list'>
          <?php $i = 1;?>
          <?php foreach($todoList['task'] as $id => $todo):?>
            <?php $projectID = isset($todoProjects['task'][$id]) ? $todoProjects['task'][$id] : 0;?>
            <li data-index='<?php echo $i++;?>'>
              <?php $class = common::hasPriv('todo', 'create') ? 'todo-item' : 'todoList'?>
              <?php echo html::a($this->createLink('task', 'view', "id=$id", 'html', true), $todo, '', "class='iframe $class' data-id='{$id}' data-type='task' data-title='{$todo}' title='{$todo}' data-width='70%'");?>
            </li>
          <?php endforeach;?>
          </ul>
          <?php endif;?>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
config.ajaxGetTodosUrl   = '<?php echo $this->createLink('todo', 'ajaxGetTodos', "userID={$this->app->user->id}&year={year}");?>';
config.ajaxChangeDaysUrl = '<?php echo $this->createLink('todo', 'ajaxChangeDays', 'id={id}&date={date}');?>';
config.ajaxFinishUrl     = '<?php echo $this->createLink('todo', 'finish', 'id={id}');?>';
config.ajaxActivateUrl   = '<?php echo $this->createLink('todo', 'activate', 'id={id}');?>';
config.todoCreateUrl     = '<?php echo $this->createLink('todo', 'create', '', 'json');?>';
config.todoViewUrl       = '<?php echo $this->createLink('todo', 'view', 'id={id}', '', true);?>';
config.batchAddUrl       = '<?php echo $this->createLink('todo', 'batchCreate', 'date={date}', '', true);?>';
config.userAccount       = '<?php echo $this->app->user->account;?>';
config.textNetworkError  = '<?php echo $lang->textNetworkError;?>';
config.textHasMoreItems  = '<?php echo $lang->textHasMoreItems;?>';
</script>
<script>
var todoModalTrigger = new $.zui.ModalTrigger(
{
    width: '70%',
    type: 'iframe',
    rememberPos: 'todoViewModal',
    waittime: 5000
});

var batchAddModalTrigger = new $.zui.ModalTrigger(
{
    width: '80%',
    type: 'iframe',
    waittime: 5000
});

var displayDate = 0;
var calendar    = false;
$(function()
{

    var expandedDays   = {};
    var minExpandCount = 6;
    var $calendar      = $('#todoCalendar');
    var toggleLoading  = function(loading)
    {
        $calendar.toggleClass('loading', !!loading);
    };
    calendar = $calendar.calendar(
    {
        hideEmptyWeekends: true,
        data:
        {
            events: [],
            calendars:
            {
                defaultCal: {color: '#fff'}
            }
        },
        beforeDisplay: function(display, doDisplay) {
            var date = display.date;
            var thisDisplayDate = date.getFullYear();
            if(displayDate === thisDisplayDate)
            {
                return doDisplay();
            }
            else
            {
                displayDate = thisDisplayDate;
            }

            var calendar = this;
            toggleLoading(true);
            $.ajax(
            {
                url: config.ajaxGetTodosUrl.replace('{year}', date.getFullYear()),
                dataType: 'json',
                success: function(data)
                {
                    $.each(data, function(index, todo)
                    {
                        todo.finish = todo.status === 'done' || todo.status === 'closed';
                        todo.allDay = !todo.end;
                    });
                    calendar.resetData({events: data});
                    doDisplay();
                },
                error: function()
                {
                    $.zui.messager.danger(config.textNetworkError);
                },
                complete: function() {toggleLoading(false);}
            });
            return false;
        },
        eventCreator: function(event, $cell, calendar)
        {
            var pri = event.pri != '' ? '(' + event.pri + ')' : '';
            var $event = $('<div title="' + pri + event.title + '" data-id="' + (event.id || '') + '" class="event">' + (event.finish ? '<i class="icon todo-check icon-check-circle"></i>' : '<i class="icon  todo-check icon-check-circle-empty"></i>') + '<span class="title">' + pri + event.title + '</span>' + (event.allDay ? '' : '<span class="time">' + event.start.format('hh:mm') + '</span>') + '</div>');
            $event.toggleClass('has-time', !event.allDay)
                  .toggleClass('expired', !event.finish && !$cell.hasClass('future'));
            return $event;
        },
        dayFormater: function($cell, date, dayEvents, calendar)
        {
            if(dayEvents && dayEvents.maxPos >= minExpandCount)
            {
                var hideManyEvents = !expandedDays[date.toDateString()];
                $cell.toggleClass('hide-many-events', hideManyEvents);
                if(hideManyEvents)
                {
                    var $cellContent = $cell.find('.day > .content');
                    var $showMore = $cellContent.find('.show-more-events');
                    if(!$showMore.length)
                    {
                        $showMore = $('<div class="show-more-events" />').appendTo($cellContent);
                    }
                    else
                    {
                        $showMore.show();
                    }
                    $showMore.text(config.textHasMoreItems.format(dayEvents.maxPos - minExpandCount + 1));
                }
            }
            else
            {
                $cell.removeClass('hide-many-events');
            }
            if($cell.is('.future')) $cell.addClass('with-plus-sign');
        },
        beforeChange: function(change)
        {
            toggleLoading(true);
            $.ajax(
            {
                url: config.ajaxChangeDaysUrl.replace('{id}', change.event.id).replace('{date}', change.to.getTime()),
                error: function()
                {
                    $.zui.messager.danger(config.textNetworkError);
                    calendar.display();
                },
                complete: function() {toggleLoading(false);}
            });
            displayDate = 0;
            this.display();
        },
        eventSorter: function(a, b)
        {
            var result = (a.finish ? 1 : 0) - (b.finish ? 1 : 0);
            if(result === 0) result = (a.allDay ? (-1) : 0) - (b.allDay ? (-1) : 0);
            if(result === 0) result = a.start - b.start;
            if(result === 0) return a.id - b.id;
            return result;
        }
    }).data('zui.calendar');
    $calendar.on('click', '.show-more-events', function(e)
    {
        var $cell = $(this).hide().closest('.cell-day');
        $cell.removeClass('hide-many-events');
        expandedDays[$cell.find('.day').attr('data-date')] = true;
        e.stopPropagation();
    }).on('click', '.event', function(e)
    {
        var event = $(this).data('event');
        todoModalTrigger.show({url: config.todoViewUrl.replace('{id}', event.id)});
        e.stopPropagation();
    }).on('click', '.day', function(e)
    {
        var $day = $(this);
        if($day.parent().hasClass('future')) batchAddModalTrigger.show({url: config.batchAddUrl.replace('{date}', $day.data('date').format('yyyyMMdd')), showHeader:false});
        e.stopPropagation();
    }).on('click', '.todo-check', function(e)
    {
        // 当点击日历待办勾选框时
        var $event = $(this).closest('.event');
        var event = $event.data('event');
        toggleLoading(true);
        var actionUrl = event.finish ? config.ajaxActivateUrl : config.ajaxFinishUrl;
        $.ajax({
              url: actionUrl.replace('{id}', event.id),
              success: function()
              {
                  event.finish = !event.finish;
                  calendar.display();
              },
              error: function()
              {
                  $.zui.messager.danger(config.textNetworkError);
                  calendar.display();
              },
              complete: function() {toggleLoading(false);}
        });
        displayDate = 0;
        e.preventDefault();
        e.stopPropagation();
    });

    $('#todoLists').droppable(
    {
        selector: '.todo-item',
        target: function()
        {
            return $calendar.find('.cell-day');
        },
        before: function()
        {
            $calendar.addClass('event-dragging');
        },
        always: function()
        {
            $calendar.removeClass('event-dragging');
        },
        drop: function(e)
        {
            var $item  = $(e.element);
            var $day   = $(e.target).children('.day');
            var date   = $day.data('date');
            var item   = $item.data();
            var $li    = $item.parent();
            var finish = $li.hasClass('active');
            var todoEvent = {
                id: item.id,
                title: item.title,
                start: date,
                end: null,
                finish: finish,
                status: finish ? 'done' : 'wait',
                allDay: true
            };
            if(item.type === 'todo')
            {
                $.ajax(
                {
                    url: config.ajaxChangeDaysUrl.replace('{id}', item.id).replace('{date}', date.getTime()),
                    error: function()
                    {
                        $.zui.messager.danger(config.textNetworkError);
                        calendar.display();
                    },
                    success: function() {
                        calendar.addEvents([todoEvent]);
                        $li.remove();
                    },
                    complete: function() {toggleLoading(false);}
                });
            }
            else if(moduleList.indexOf(item.type) !== -1)
            {
                $.ajax(
                {
                    type: 'POST',
                    dataType: 'json',
                    url: config.todoCreateUrl,
                    data: {
                        date: date.format('yyyy-MM-dd'),
                        type: item.type,
                        idvalue: item.id,
                        name: item.title,
                        begin: '',
                        end: '',
                    },
                    error: function()
                    {
                        $.zui.messager.danger(config.textNetworkError);
                        calendar.display();
                    },
                    success: function() {
                        calendar.addEvents([todoEvent]);
                        $li.remove();

                        /* After adding the to-do, refresh the number. */
                        var objectClass = $('#' + item.type + 'Total').attr('class');
                        var todoTotal   = $('#tab_' + item.type).find('ul').children('li').length;
                        $('#' + item.type + 'Total').text(todoTotal);
                    },
                    complete: function() {toggleLoading(false);}
                });
            }
            displayDate = 0;
        }
    })

    $('#batchCreate').click(function(e)
    {
        batchAddModalTrigger.show({url: $(this).attr('href'), showHeader:false});
        return false;
    })

    $('.pull-left .btn-link').click(function()
    {
        $(this).css('background', 'unset');
    })

    addPager('#tab_undone');
    addPager('#tab_future');
    addPager('#tab_task');
});

function addPager(selecter)
{
    var preNum = 15;
    var tab    = $(selecter);
    var count  = tab.find('li').length;
    var page   = Math.ceil(count / preNum);
    if(page > 1)
    {
        for(i = page; i > 0; i--)
        {
            tab.append("<span class='page-num btn' data-id='" + i + "'>" + i + '</span>')
        }
        $(selecter + ' span.page-num').click(function()
        {
            var tab  = $(this).parent();
            var page = $(this).data('id');
            tab.find('.page-num').removeClass('active');
            $(this).addClass('active');
            page = parseInt(page) *  preNum;
            tab.find('li').hide();
            for(i = page; i > page - preNum; i--)
            {
                var item = tab.find('[data-index=' + i + ']');
                if(item.prop('data-remove') != '1') item.show();
            }
        });
        $(selecter + ' span.page-num[data-id=1]').click();
    }
}

/**
 * Get the to-do list under the object.
 *
 * @access public
 * @return void
 */
$(".object-calendar").click(function()
{
    $('.object-calendar .label-badge.label-todo').addClass('hidden');
    $(this).find('.label-badge.label-todo').removeClass('hidden');
});

function selectMore(){$("#tabMore").attr("class" , 'active')}
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
