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

    var tasks        = tutorialTasks;
    var current      = defaultTask;
    var setting      = formatSetting(settingString);
    var lang         =
    {
        targetPageTip : langTargetPageTip,
        targetAppTip  : langTargetAppTip,
        target        : langTarget,
        requiredTip   : langRequiredTip,
    };

    var $tasks        = $('#tasks'),
        $task         = $('#task'),
        $openTaskPage = $('#openTaskPage'),
        $progress     = $('#tasksProgress'),
        $modal        = $('#taskModal'),
        $modalBack    = $('#taskModalBack');
    var totalCount    = $tasks.children('li').length, finishCount = 0;

    var appsWindow = window.frames['iframePage'];
    var checkTaskId = null, modalShowTaskId;
    var showToolTipTask = null;

    var getApp = function(code)
    {
        if(!code) return appsWindow.$.apps.getLastApp();
        return $.extend({}, appsWindow.$.apps.appsMap[code], appsWindow.$.apps.openedApps[code]);
    };

    var getAppWindow = function()
    {
        var app = appsWindow.$.apps.getLastApp();
        return appsWindow.frames['app-' + app.code];
    };

    var getAppIframe = function()
    {
        var app = appsWindow.$.apps.getLastApp();
        return appsWindow.$('#appIframe-' + app.code).get(0);
    };

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

    var clearTips = function()
    {
        var $menuMainNav = appsWindow.$('#menuNav');
        $menuMainNav.find('.hl-tutorial').removeClass('hl-tutorial hl-in');
        $menuMainNav.find('.tooltip-tutorial').tooltip('destroy').removeClass('tooltip-tutorial');
        var appWindow = getAppWindow();
        if(appWindow && appWindow.$)
        {
            var $appBody = appWindow.$('body');
            $appBody.find('.hl-tutorial').removeClass('hl-tutorial hl-in');
            $appBody.find('.tooltip-tutorial').tooltip('destroy').removeClass('tooltip-tutorial');
        }
    };

    var highlight = function($e, callback)
    {
        $e = $e.first();
        var ele      = $e[0];
        var bounds   = ele.getBoundingClientRect();
        var winWidth = $e.closest('body').outerWidth();
        if(bounds.width < (winWidth / 2) && bounds.right > winWidth)
        {
            ele[ele.scrollIntoViewIfNeeded ? 'scrollIntoViewIfNeeded' : 'scrollIntoView']({behavior: 'instant', block: 'center'});
        }

        $e.closest('body').find('.hl-tutorial').removeClass('hl-tutorial hl-in');
        $e.addClass('hl-tutorial').parent().css('overflow', 'visible');
        setTimeout(function() {$e.addClass('hl-in'); callback && callback()}, 50);
    };

    var finishTask = function()
    {
        clearTips();

        var task = tasks[current];
        if(task)
        {
            setting[current] = true;
            var postData = [];
            $.each(setting, function(name, value) {if(value) postData.push(name);});

            $.post(ajaxSetTasksUrl, {finish: postData.join(',')}, function(e)
            {
                if(e.result === 'success')
                {
                    $task.addClass('finish').find('[data-target]').removeClass('active').removeClass('wait').addClass('finish');
                    updateUI();
                    showModal(finishCount >= totalCount);
                }
                else
                {
                    setting[current] = false;
                    alert(serverErrorTip);
                }
            }, 'json').error(function() {alert(lang.timeout)});
        }
    };

    var resetTasks = function()
    {
        clearTips();

        $.post(ajaxSetTasksUrl, {finish: ''}, function(e)
        {
            if(e.result === 'success')
            {
                setting = {};
                updateUI();
            }
            else
            {
                alert(serverErrorTip);
            }
        }, 'json').error(function() {alert(lang.timeout)});
    };

    var showToolTip = function($e, text, options)
    {
        if(!$e.length) return;
        var container = 'body';
        var ele       = $e[0];
        var bounds    = ele.getBoundingClientRect();
        var winWidth  = $e.closest('body').outerWidth();
        if(bounds.width < (winWidth / 2) && (bounds.right + bounds.width) >= winWidth)
        {
            ele[ele.scrollIntoViewIfNeeded ? 'scrollIntoViewIfNeeded' : 'scrollIntoView']({behavior: 'instant', block: 'center'});
            container = null;
        }

        if($(ele).hasClass('create-bug-btn') || $(ele).hasClass('create-story-btn')) container = '#mainMenu'; // Fix bug #21092.
        if(!container && $(ele).hasClass('form-control')) container = '.table-form'; // Fix bug #21093

        $e.closest('body').find('.tooltip-tutorial').tooltip('destroy');
        var offset   = $e.offset();
        var winWidth = $(window).width();
        var placement = 'top';
        if(offset.left > (winWidth*2/3))
        {
            placement = 'left';
        }
        else if(offset.left < (winWidth/3) && (offset.left + $e.outerWidth()) < (winWidth*2/3))
        {
            placement = 'right';
        }
        else if (offset.top < 50)
        {
            placement = 'bottom';
        }

        options = $.extend(
        {
            trigger: 'manual',
            title: text,
            placement: placement,
            container: container,
            tipClass: 'tooltip-warning tooltip-max',
            html: true
        }, options);
        $e = $e.first();
        if($e.css('display') !== 'none')
        {
            if(!$e.data('zui.tooltip')) $e.addClass('tooltip-tutorial').attr('data-toggle', 'tooltip').tooltip(options);
            $e.tooltip('show', text);
            if($e[0].getBoundingClientRect().top > $(window).height() || $e[0].getBoundingClientRect().top < 0) $e[0].scrollIntoView();
        }
        else if($e.parent().is('#menuMainNav'))
        {
            var $menuMoreItem = appsWindow.$('#menuMoreNav>li.dropdown');
            highlight($menuMoreItem);
            showToolTip($menuMoreItem, text, $.extend({}, options, {tipClass: 'tooltip-warning tooltip-max tooltip-menu-more text-nowrap', container: false}));
            var appCode = $e.data('app');
            appsWindow.$('#menuMoreList>li').removeClass('active').attr('data-tip', '');
            appsWindow.$('#menuMoreList>li[data-app="' + appCode + '"]').addClass('active hl-tutorial hl-in').attr('data-tip', text);
        }
        else
        {
            $e.parent().addClass('tooltip-tutorial').append("<div id='typeLabel' class='text-danger help-text'>" + options.title + "</div>");
        }
    };

    var tryCheckTask = function()
    {
        if(checkTaskId) clearTimeout(checkTaskId);

        var iWindow = getAppWindow();
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
        clearTips();

        var iWindow = getAppWindow();
        if(!iWindow || !iWindow.$) return tryCheckTask();
        var task = tasks[current];
        var appCode = task.nav.app || task.nav.menuModule || task.nav['module'];
        var app = getApp(appCode);
        if(!app) return;

        var $$ = iWindow.$;
        var pageConfig = iWindow.config;
        var currentModule  = (iWindow.TUTORIAL ? iWindow.TUTORIAL['module'] : pageConfig ? pageConfig.currentModule : '').toLowerCase();
        var currentMethod  = (iWindow.TUTORIAL ? iWindow.TUTORIAL['method'] : pageConfig ? pageConfig.currentMethod : '').toLowerCase();
        var targetStatus = {},
            $navTarget = $task.find('[data-target="nav"]').removeClass('active'),
            $formTarget = $task.find('[data-target="form"]').removeClass('active'),
            $submitTarget = $task.find('[data-target="submit"]').removeClass('active');
        targetStatus.nav = task.nav['module'].toLowerCase() === currentModule && task.nav['method'].toLowerCase() === currentMethod && (!task.nav.app || task.nav.app === appCode);

        if(targetStatus.nav)
        {
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
                            if(status.waitField.hasClass("chosen-controled")) status.waitField = status.waitField.next();
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
                    }
                    else
                    {
                        finishTask();
                    }
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
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
            /* Active current nav target in task panel */
            $navTarget.addClass('active');

            /* Highlight app button in left menu */
            var $appNav = appsWindow.$('#menuMainNav > li[data-app="' + appCode + '"]');
            if(!app.show)
            {
                var targetAppTip = lang.targetAppTip.replace('%s', app.text || lang.target);
                highlight($appNav);
                showToolTip($appNav, targetAppTip);
            }
            else
            {
                var menuModule = task.nav.menuModule || task.nav['module'];
                var $navbar    = $$('#navbar');
                if(task.nav.app == 'admin') $navbar = $$('.settings-list');
                var $navbarItem = $navbar.find('[data-id="' + menuModule + '"]');
                var targetPageTip = lang.targetPageTip.replace('%s', task.nav.targetPageName || lang.target);
                if($navbarItem.length && !$navbarItem.hasClass('active'))
                {
                    highlight($navbarItem);
                    showToolTip($navbarItem, targetPageTip);
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
                            showToolTip($targetBtn, targetPageTip);
                        }
                        else
                        {
                            highlight($targetBtnGroup);
                            showToolTip($targetBtnGroup, targetPageTip);
                        }
                        if(!$targetBtnGroup.data('initTutorial'))
                        {
                            $targetBtnGroup.data('initTutorial', 1).on('click', tryCheckTask);
                        }
                    }
                    else if(task.nav.menu[0] === '#')
                    {
                        var $customMenu = $$(task.nav.menu).last();
                        if($customMenu.length)
                        {
                            highlight($customMenu);
                            showToolTip($customMenu, targetPageTip);
                        }
                        else if(task.nav.target)
                        {
                            var $targetItem = $$(task.nav.target);
                            highlight($targetItem);
                            showToolTip($targetItem, targetPageTip);
                        }
                    }
                    else
                    {
                        var $modulemenu = $$('#subNavbar');
                        if(task.nav.app == 'admin') $modulemenu = $$('#navbar');
                        var $modulemenuItem = $modulemenu.find('[data-id="' + task.nav.menu + '"]');
                        if($modulemenuItem.length && !$modulemenuItem.hasClass('active'))
                        {
                            highlight($modulemenuItem);
                            showToolTip($modulemenuItem, targetPageTip);
                        }
                        else if(task.nav.target)
                        {
                            var $targetItem = $$(task.nav.target);
                            highlight($targetItem);
                            showToolTip($targetItem, targetPageTip);
                        }
                    }
                }
                else if(task.nav.target)
                {
                    var $targetItem = $$(task.nav.target);
                    highlight($targetItem);
                    showToolTip($targetItem, targetPageTip);
                }
            }
        }
        $navTarget.toggleClass('finish', !!targetStatus.nav);
        $formTarget.toggleClass('finish', !!targetStatus.form);
        $submitTarget.toggleClass('finish', !!targetStatus.submit);
        $navTarget.toggleClass('wait', !$navTarget.is('.finish,.active'));
        $formTarget.toggleClass('wait', !$formTarget.is('.finish,.active'));
        $submitTarget.toggleClass('wait', !$submitTarget.is('.finish,.active'));
        $openTaskPage.toggleClass('open', targetStatus.nav);

        targetStatus.submitOK = targetStatus.nav && targetStatus.form;

        return targetStatus;
    };

    var checkTutorialState = function()
    {
        tryCheckTask();
        var iWindow = getAppWindow();
        var title = (iWindow.$ ? iWindow.$('head > title').text() : '') + $('head > title').text();
        var url = createLink('tutorial', 'index', 'referer=' + Base64.encode(iWindow.location.href) + '&task=' + current);
        try{window.history.replaceState({}, title, url);}catch(e){}
    };

    var checkTimer = 0;
    var tryCheckTutorialState = function(delay)
    {
        clearTimeout(checkTimer);
        checkTimer = setTimeout(checkTutorialState, delay || 200);
    };

    var showTask = function(taskName)
    {
        clearTips();
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
        finishCount = 0;
        totalCount  = 0;
        $tasks.children('li').each(function(idx)
        {
            var $li      = $(this);
            var name     = $li.data('name');
            var task     = tasks[name];
            var finish   = !!setting[name];
            task.finish  = finish;
            finishCount += finish ? 1 : 0;
            totalCount++;

            $li.toggleClass('finish', finish);
            if(!current && !finish) current = name;
        });

        $('.task-num-finish').text(finishCount);
        $('.tasks-count').text(totalCount);
        var isFinishAll = finishCount >= totalCount;
        if(isFinishAll) current = $tasks.children('li').first().data('name');

        var progress = Math.round(100*finishCount/totalCount);
        $progress.toggleClass('finish', isFinishAll).find('.progress-bar').css('width', (100*finishCount/totalCount) + '%');
        $progress.find('.progress-text').text(progress + '%');
        if(progress == 100) $.getJSON(createLink('tutorial', 'ajaxFinish'));
        showTask(current);
    };

    /** Init apps iframe page */
    function initAppsPage()
    {
        var appsIframe = $('#iframePage').get(0);
        appsIframe.onload = appsIframe.onreadystatechange = function()
        {
            appsWindow.$(appsWindow.document).on('reloadapp loadapp showapp closeapp hideapp', function()
            {
                tryCheckTutorialState(1000);
            });

            /* Open referer page in app tab */
            if(tutorialReferer) appsWindow.$.apps.open(tutorialReferer);

            updateUI();
        };
    }

    /* Quit tutorial mode */
    function quitTutorial()
    {
        var url = createLink('tutorial', 'quit');
            if(typeof navigator.sendBeacon === 'function') navigator.sendBeacon(url);
            else $.ajax({url: url, dataType: 'json', async: false});
    }

    /** Init current tutorial page */
    function initTutorial()
    {
        if(finishCount >= totalCount) showModal(true);

        $(document).on('click', '.btn-task', function()
        {
            showTask($(this).data('name'));

            /* Code for task #51133. */
            var status = checkTask();
            if(status.submitOK) window.location.reload();
        }).on('click', '.btn-open-target-page', function()
        {
            appsWindow.$.apps.open(tasks[current].url);
        }).on('click', '.btn-reset-tasks', function()
        {
            hideModal();
            resetTasks();
        });

        $modal.on('click', '.close', hideModal);

        $('[data-toggle="tooltip"]').tooltip();

        window.appsWindow = appsWindow;
        window.getAppIframe = getAppIframe;
        window.getAppWindow = getAppWindow;
        window.checkTutorialState = checkTutorialState;
    }

    initTutorial();
    initAppsPage();
});
