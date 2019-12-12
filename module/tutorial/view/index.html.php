<?php
/**
 * The index view file of tutorial module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     tutorial
 * @version     $Id: browse.html.php 4728 2013-05-03 06:14:34Z sunhao@cnezsoft.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::import($jsRoot . 'misc/base64.js');?>
<?php $referer = $referer ? $referer : helper::createLink('my', 'index', '', 'tutorial');?>
<div id='pageContainer'>
  <div id='iframeWrapper'>
    <iframe id='iframePage' name='iframePage' src='<?php echo $referer;?>' frameborder='no' allowtransparency='true' scrolling='auto' hidefocus='' style='width: 100%; height: 100%; left: 0; top: 0'></iframe>
    <div id='taskModalBack'></div>
    <div id='taskModal'>
      <button class='close'><i class="icon icon-close"></i></button>
      <div class='finish-all'>
        <div class='start-icon'><i class='icon icon-check-circle icon-front'></i></div>
        <h3><?php echo $lang->tutorial->congratulation;?></h3>
        <button type='button' class='btn btn-success btn-reset-tasks'><i class='icon icon-restart'></i>  <?php echo $lang->tutorial->restart;?></button> &nbsp; <a href='<?php echo helper::createLink('tutorial', 'quit');?>' class='btn btn-success'><i class='icon icon-signout'></i> <?php echo $lang->tutorial->exit;?></a>
      </div>
      <div class='finish'>
        <div class='start-icon'><i class='icon icon-check-circle icon-front'></i></div>
        <h3><?php echo $lang->tutorial->congratulateTask;?></h3>
        <button type='button' class='btn btn-success btn-next-task btn-task'><?php echo $lang->tutorial->nextTask;?> <i class='icon icon-angle-right'></i></button>
      </div>
    </div>
  </div>
  <div id='sidebar'>
    <header class='bg-primary'>
      <div class='start-icon'><i class='icon icon-certificate icon-back'></i><i class='icon icon-flag icon-front text-secondary'></i></div>
      <h2><?php echo $lang->tutorial->common;?></h2>
      <div class='actions'>
        <a href='<?php echo helper::createLink('tutorial', 'quit');?>' class='btn btn-danger btn-sm'><i class="icon icon-signout"></i> <?php echo $lang->tutorial->exit;?></a>
      </div>
    </header>
    <section id='current'>
      <h4><?php echo $lang->tutorial->currentTask;?></h4>
      <div class='panel' id='task'>
        <div class='panel-heading bg-secondary'>
          <strong><span class='task-id-current'>1</span>. <span class='task-name task-name-current'></span></strong>
          <i class="icon icon-check pull-right"></i>
        </div>
        <div class='panel-body'>
          <div class='task-desc'></div>
          <a href='javascript:;' id='openTaskPage' class='btn-open-target-page hl-primary'>
            <div class='normal'><i class="icon icon-flag-alt"></i> <?php echo $lang->tutorial->openTargetPage;?></div>
            <div class='opened'><i class="icon icon-flag"></i> <?php echo $lang->tutorial->atTargetPage;?></div>
            <div class='reload'><i class="icon icon-restart"></i> <?php echo $lang->tutorial->reloadTargetPage;?></div>
          </a>
          <div class='alert-warning' style='padding:5px 10px;margin-bottom:0px'><?php echo $lang->tutorial->dataNotSave?></div>
        </div>
      </div>
      <div class='clearfix actions'>
        <button type='button' class='btn btn-sm btn-circle btn-prev-task btn-task btn-icon-left'><span class="label label-badge label-icon"><i class="icon icon-arrow-left"></i></span><?php echo $lang->tutorial->previous;?></button>
        <button type='button' class='btn btn-sm btn-circle btn-primary pull-right btn-task btn-next-task btn-icon-right'><?php echo $lang->tutorial->nextTask;?> <span class="label label-badge label-icon"><i class="icon icon-arrow-right"></i></span></button>
      </div>
    </section>
    <section id='all'>
      <h4><?php echo $lang->tutorial->allTasks;?> (<span class='task-num-finish'>2</span>/<span class='tasks-count'><?php echo count($lang->tutorial->tasks);?></span>)</h4>
      <div class='progress' id='tasksProgress'>
        <div class='progress-text'></div>
        <div class='progress-bar' style='width: 0%'>
        </div>
      </div>
      <ul id='tasks' class='nav nav-primary nav-stacked'>
        <?php
        $idx = 1;
        $tasks = array();
        ?>
        <?php foreach ($lang->tutorial->tasks as $name => $task):?>
        <?php
        $nav = $task['nav'];
        $task['name'] = $name;
        $task['id']   = $idx + 1;
        $task['url']  = helper::createLink($nav['module'], $nav['method'], isset($nav['vars']) ? $nav['vars'] : '', 'tutorial');
        $tasks[$name] = $task;
        ?>
        <li data-name='<?php echo $name;?>'><a class='btn-task' href='javascript:;' data-name='<?php echo $name;?>'><span><?php echo $idx++;?></span>. <span class='task-name'><?php echo $task['title'];?></span><i class='icon icon-check pull-right'></i></a></li>
        <?php endforeach;?>
      </ul>
    </section>
  </div>
</div>
<script>
$(function()
{
    var formatSetting = function(str)
    {
        var settings = {};
        if(typeof str === 'string')
        {
            $.each(str.split(','), function(idx, name)
            {
                if(name) settings[name] = true;
            });
        }
        return settings;
    };

    var tasks        = $.parseJSON('<?php echo helper::jsonEncode4Parse($tasks, JSON_HEX_QUOT | JSON_HEX_APOS);?>');
    var current      = '<?php echo $current;?>';
    var setting      = formatSetting('<?php echo $setting;?>');
    var lang         = 
    {
        tagetPageTip: '<?php echo $lang->tutorial->targetPageTip;?>',
        target      : '<?php echo $lang->tutorial->target;?>',
        requiredTip : '<?php echo $lang->tutorial->requiredTip;?>'
    };

    var $tasks        = $('#tasks'),
        $task         = $('#task'),
        $openTaskPage = $('#openTaskPage'),
        $progress     = $('#tasksProgress'),
        $modal        = $('#taskModal'),
        $modalBack    = $('#taskModalBack');
    var totalCount    = $tasks.children('li').length, finishCount = 0;

    var iWindow = window.frames['iframePage'];
    var iframe  = $('#iframePage').get(0);
    var checkTaskId = null, modalShowTaskId;
    var showToolTipTask = null;

    var showModal = function(showAll)
    {
        clearTimeout(modalShowTaskId);
        $modal.show();
        $modalBack.show();
        $modal.toggleClass('show-all', showAll);
        modalShowTaskId = setTimeout(function()
        {
            $modal.addClass('in');
            $modalBack.addClass('in');
        }, 10);
    };

    var hideModal = function()
    {
        clearTimeout(modalShowTaskId);
        $modal.removeClass('in');
        $modalBack.removeClass('in');
        modalShowTaskId = setTimeout(function()
        {
            $modal.hide();
            $modalBack.hide();
        }, 450);
    };

    var highlight = function($e, callback)
    {
        $e = $e.first();
        $e.closest('body').find('.hl-tutorial').removeClass('hl-tutorial hl-in');
        $e.addClass('hl-tutorial').parent().css('overflow', 'visible');
        setTimeout(function() {$e.addClass('hl-in'); callback && callback()}, 50);
    };

    var finishTask = function()
    {
        var task = tasks[current];
        if(task)
        {
            setting[current] = true;
            var postData = [];
            $.each(setting, function(name, value) {if(value) postData.push(name);});

            $.post('<?php echo inlink('ajaxSetTasks');?>', {finish: postData.join(',')}, function(e)
            {
                if(e.result === 'success')
                {
                    $task.addClass('finish').find('[data-target]').removeClass('active').addClass('finish');
                    updateUI();
                    showModal(finishCount >= totalCount);
                }
                else
                {
                    setting[current] = false;
                    alert('<?php echo $lang->tutorial->serverErrorTip;?>');
                }
            }, 'json').error(function() {alert(lang.timeout)});
        }
    };

    var resetTasks = function()
    {
        $.post('<?php echo inLink('ajaxSetTasks');?>', {finish: ''}, function(e)
        {
            if(e.result === 'success')
            {
                setting = {};
                updateUI();
            }
            else
            {
                alert('<?php echo $lang->tutorial->serverErrorTip;?>');
            }
        }, 'json').error(function() {alert(lang.timeout)});
    };

    var showToolTip = function($e, text, options)
    {
        if(!$e.length) return;
        $e.closest('body').find('[data-toggle=tooltip]').tooltip('destroy');
        var offset   = $e.offset();
        var winWidth = $(window).width();
        var placement = 'top';
        if (offset.left > (winWidth*2/3)) {
            placement = 'left';
        } else if (offset.left < (winWidth/3) && (offset.left + $e.outerWidth()) < (winWidth*2/3)) {
            placement = 'right';
        } else if (offset.top < 50) {
            placement = 'bottom';
        }
        options = $.extend(
        {
            trigger: 'manual',
            title: text,
            placement: placement,
            container: 'body',
            tipClass: 'tooltip-warning tooltip-max'
        }, options);
        $e = $e.first();
        if($e.css('display') == 'none')
        {
            $e.parent().addClass('tooltip-tutorial').after("<div id='typeLabel' class='text-danger help-text'>" + options.title + "</div>");
        }
        else
        {
            if(!$e.data('zui.tooltip')) $e.addClass('tooltip-tutorial').attr('data-toggle', 'tooltip').tooltip(options);
            $e.tooltip('show');
            if($e[0].getBoundingClientRect().top > $(window).height() || $e[0].getBoundingClientRect().top < 0) $e[0].scrollIntoView();
        }
    };

    var tryCheckTask = function()
    {
        if(checkTaskId) clearTimeout(checkTaskId);

        if(!(iWindow && iWindow.config && iWindow.$))
        {
            checkTaskId = setTimeout(tryCheckTask, 1000);
        }
        else
        {
            checkTaskId = setTimeout(checkTask, 200);
        }
    };

    var checkTask = function()
    {
        if(!iWindow || !iWindow.$) return tryCheckTask();
        var task = tasks[current];
        var $$ = iWindow.$;
        var pageConfig = iWindow.config;
        var currentModule  = (iWindow.TUTORIAL ? iWindow.TUTORIAL['module'] : pageConfig ? pageConfig.currentModule : '').toLowerCase();
        var currentMethod  = (iWindow.TUTORIAL ? iWindow.TUTORIAL['method'] : pageConfig ? pageConfig.currentMethod : '').toLowerCase();
        var targetStatus = status || {},
            $navTarget = $task.find('[data-target="nav"]').removeClass('active'),
            $formTarget = $task.find('[data-target="form"]').removeClass('active'),
            $submitTarget = $task.find('[data-target="submit"]').removeClass('active');
        targetStatus.nav = task.nav['module'].toLowerCase() === currentModule && task.nav['method'].toLowerCase() === currentMethod;

        if(targetStatus.nav)
        {
            // check form target
            var $form = $$(task.nav.form);
            var $formWrapper = $form.closest('.main-content');
            if(!$formWrapper.length) $formWrapper = $form;
            highlight($formWrapper);
            showToolTip($formWrapper, $formTarget.text());
            var fieldSelector = '';
            var requiredFields = task.nav.requiredFields || pageConfig.requiredFields;

            if(task.nav.formType === 'table')
            {
                fieldSelector = 'input[type="checkbox"]';
                var $checkboxes = $form.find(fieldSelector);
                targetStatus.form = $checkboxes.filter(':checked').length > 0;
                if(!targetStatus.form) {
                    targetStatus.waitField = $checkboxes.filter(':not(:checked):first').closest('td');
                }
            }
            else if(requiredFields)
            {
                targetStatus.form = true;
                requiredFields = requiredFields.split(',');
                $.each(requiredFields, function(idx, requiredId)
                {
                    fieldSelector += ',' + '#' + requiredId;
                    var $required = $$('#' + requiredId);
                    if($required.length)
                    {
                        var val = $required.val();
                        if(val === undefined || val === null || val === '' || val === '0')
                        {
                            targetStatus.form = false;
                            if(!targetStatus.waitField) targetStatus.waitField = $required;
                        }
                    }
                });
                if(fieldSelector.length > 1) fieldSelector = fieldSelector.substring(1);
            }

            if(!$form.data('bindCheckTaskEvent'))
            {
                $form.off('.tutorial').off('submit');
                $form.on('change.tutorial', fieldSelector, tryCheckTask);
                var onSubmit = function(e)
                {
                    var status = checkTask();
                    if(!status.submitOK)
                    {
                        if(status.waitField)
                        {
                            var fieldName = status.waitField.closest('td').prev('th').text();
                            if(!fieldName) fieldName = status.waitField.closest('.input-group').find('.input-group-addon:first').text();
                            if(fieldName) showToolTip(status.waitField, lang.requiredTip.replace('%s', fieldName));
                            highlight(status.waitField, function()
                            {
                                clearTimeout(showToolTipTask);
                                showToolTipTask = setTimeout(function()
                                {
                                    status.waitField.closest('td').find('#typeLabel').remove();
                                    showToolTip($formWrapper, $formTarget.text());
                                    highlight($formWrapper);
                                }, 2000);
                            });
                        }

                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    } else
                    {
                        finishTask();
                    }
                }
                if(task.nav.submit) $form.on('click.tutorial', task.nav.submit, onSubmit);
                else $form.submit(onSubmit);
            }

            if(targetStatus.form)
            {
                $submitTarget.addClass('active');
                if(task.nav.submit) showToolTip($form.find(task.nav.submit), $submitTarget.text(), {placement: 'top'});
            }
            else
            {
                $formTarget.addClass('active');
            }
        }
        else
        {
            // check nav target
            $navTarget.addClass('active');
            var menuModule = task.nav.menuModule || task.nav['module'];
            var $navbar = $$('#navbar');
            var $navbarItem = $navbar.find('[data-id="' + menuModule + '"]');
            var tagetPageTip = lang.tagetPageTip.replace('%s', task.nav.targetPageName || lang.target);
            if(!$navbarItem.hasClass('active'))
            {
                highlight($navbarItem);
                showToolTip($navbarItem, tagetPageTip);
            }
            else if(task.nav.menu)
            {
                if(task.nav.menu === '#pageNav')
                {
                    var $pageNav = $$('#pageNav');
                    var $targetBtn = $pageNav.find(task.nav.target);
                    var $targetBtnGroup = $targetBtn.closest('.btn-group');
                    if($targetBtnGroup.hasClass('open'))
                    {
                        highlight($targetBtn);
                        showToolTip($targetBtn, tagetPageTip);
                    }
                    else
                    {
                        highlight($targetBtnGroup);
                        showToolTip($targetBtnGroup, tagetPageTip);
                    }
                    if(!$targetBtnGroup.data('initTutorial'))
                    {
                        $targetBtnGroup.data('initTutorial', 1).on('click', tryCheckTask);
                    }
                }
                else
                {
                    var $modulemenu = $$('#subNavbar');
                    var $modulemenuItem = $modulemenu.find('[data-id="' + task.nav.menu + '"]');
                    if(!$modulemenuItem.hasClass('active'))
                    {
                        highlight($modulemenuItem);
                        showToolTip($modulemenuItem, tagetPageTip);
                    }
                    else if(task.nav.target)
                    {
                        var $targetItem = $$(task.nav.target);
                        highlight($targetItem);
                        showToolTip($targetItem, tagetPageTip);
                    }
                }
            }
        }
        $navTarget.toggleClass('finish', !!targetStatus.nav);
        $formTarget.toggleClass('finish', !!targetStatus.form);
        $submitTarget.toggleClass('finish', !!targetStatus.submit);
        $openTaskPage.toggleClass('open', targetStatus.nav);

        targetStatus.submitOK = targetStatus.nav && targetStatus.form;

        return targetStatus;
    };

    var onIframeLoad = function()
    {
        iWindow = window.frames['iframePage'];
        tryCheckTask();
        var title = (iWindow.$ ? iWindow.$('head > title').text() : '') + $('head > title').text();
        var url = createLink('tutorial', 'index', 'referer=' + Base64.encode(iWindow.location.href) + '&task=' + current);
        try{window.history.replaceState({}, title, url);}catch(e){}
    };

    var openIframePage = function(url)
    {
        url = url || tasks[current].url;
        try
        {
            iWindow.location.replace(url);
        }
        catch(e)
        {
            iframe.get(0).src = url;
        }
    };

    var showTask = function(taskName)
    {
        hideModal();

        taskName = taskName || current;
        current = taskName;

        if(!taskName) return;
        var task = tasks[taskName];
        if(!task) return;

        var $li = $tasks.children('li').removeClass('active').filter('[data-name="' + taskName + '"]').addClass('active');
        $task.toggleClass('finish', task.finish);
        $('.task-name-current').text(task.title);
        $('.task-id-current').text(task.id);
        $('.task-desc').html(task.desc).find('.task-nav').addClass('btn-open-target-page');
        $('.task-page-name').text(task.nav.targetPageName || lang.target);

        var $prev = $li.prev('li'), $next = $li.next('li');
        $('.btn-prev-task').toggleClass('hidden', !$prev.length).data('name', $prev.data('name'));
        $('.btn-next-task').toggleClass('hidden', !$next.length).data('name', $next.data('name'));
        tryCheckTask();
    };

    var updateUI = function()
    {
        var currentTask;

        finishCount = 0;
        totalCount  = 0;
        $tasks.children('li').each(function(idx)
        {
            var $li      = $(this);
            var name     = $li.data('name');
            var task     = tasks[name];
            var finish   = !!setting[name];
            task.id      = idx + 1;
            task.finish  = finish;
            finishCount += finish ? 1 : 0;
            totalCount++;

            $li.toggleClass('finish', finish);
            if(!current && !finish) current = name;
        });

        $('.task-num-finish').text(finishCount);
        var isFinishAll = finishCount >= totalCount;
        if(isFinishAll) current = $tasks.children('li').first().data('name');

        var progress = Math.round(100*finishCount/totalCount);
        $progress.toggleClass('finish', isFinishAll).find('.progress-bar').css('width', (100*finishCount/totalCount) + '%');
        $progress.find('.progress-text').text(progress + '%');
        if(progress == 100) $.getJSON(createLink('tutorial', 'ajaxFinish'));
        showTask(current);
    };

    updateUI();
    if(finishCount >= totalCount) showModal(true);

    $(document).on('click', '.btn-task', function()
    {
        showTask($(this).data('name'));
    }).on('click', '.btn-reset-tasks', function()
    {
        hideModal();
        resetTasks();
    });

    iframe.onload = iframe.onreadystatechange = function()
    {
        if (this.readyState && this.readyState != 'complete') return;
        onIframeLoad();
    };

    iWindow.onload = onIframeLoad;

    $modal.on('click', '.close', hideModal);

    $('[data-toggle="tooltip"]').tooltip();

    window.onbeforeunload = function()
    {
        $.getJSON(createLink('tutorial', 'quit'));
    }
});
</script>
<?php include '../../common/view/footer.lite.html.php';?>
