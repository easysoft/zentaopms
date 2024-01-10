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
    var checkFormReadyId = null;
    var checkStatusId = null;
    var checkStatusCycle = null;
    var showToolTipTask = null;
    var submitBindCount = 0;

    var getApp = function(code)
    {
        if(!code) return appsWindow.$.apps.getLastApp();
        return $.extend({}, appsWindow.$.apps.map[code], appsWindow.$.apps.openedMap[code]);
    };

    var getAppWindow = function()
    {
        var app = appsWindow.$ ? appsWindow.$.apps.getLastApp() : null;
        return app ? appsWindow.frames['app-' + app.code] : null;
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
        if(!appsWindow.$) return false;

        var $menuMainNav = appsWindow.$('#menuNav');
        $menuMainNav.find('.hl-tutorial').removeClass('hl-tutorial hl-in');
        $menuMainNav.find('.tooltip-tutorial').tooltip('destroy').removeClass('tooltip-tutorial');
        var appWindow = getAppWindow();
        if(appWindow && appWindow.$)
        {
            var $appBody = appWindow.$('body');
            $appBody.find('.hl-tutorial').removeClass('hl-tutorial hl-in');
            $appBody.find('.tooltip-tutorial').tooltip('destroy').removeClass('tooltip-tutorial');
            $appBody.find('.popover').remove();
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
                result = JSON.parse(e);
                if(result.result === 'success')
                {
                    taskSuccess = false;
                    updateUI();
                    showModal(finishCount >= totalCount);
                }
                else
                {
                    setting[current] = false;
                    zui.Modal.alert(serverErrorTip);
                }
            }, 'json').fail(function() {zui.Modal.alert(lang.timeout)});
        }
    };

    var resetTasks = function()
    {
        $.post(ajaxSetTasksUrl, {finish: ''}, function(e)
        {
            result = JSON.parse(e);
            if(result.result === 'success')
            {
                taskSuccess = false;
                setting = {};
                window.reloadPage();
            }
            else
            {
                zui.Modal.alert(serverErrorTip);
            }
        }, 'json').fail(function() {zui.Modal.alert(lang.timeout)});
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
            title: {html: text},
            placement: placement,
            className: 'warning',
            mask: false,
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
            var $menuMoreItem = appsWindow.$('#menuMoreNav>li').last();
            highlight($menuMoreItem);
            showToolTip($menuMoreItem, text, $.extend({}, options, {tipClass: 'warning'}));
            var appCode = $e.data('app');
            appsWindow.$('#menuMoreList>li').removeClass('active').attr('data-tip', '');
            appsWindow.$('#menuMoreList>li[data-app="' + appCode + '"]').addClass('active hl-tutorial hl-in').attr('data-tip', text);
        }
        else
        {
            $e.parent().addClass('tooltip-tutorial').append("<div id='typeLabel' class='text-danger help-text'>" + options.title + "</div>");
        }
    };

    var checkTutorialState = function()
    {
        tryCheckStatus();
        var iWindow = getAppWindow();
        var title = (iWindow.$ ? iWindow.$('head > title').text() : '') + $('head > title').text();
        var url = $.createLink('tutorial', 'index', 'referer=' + btoa(iWindow.location.href) + '&task=' + current);
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
        if(progress == 100) $.getJSON($.createLink('tutorial', 'ajaxFinish'));
        showTask(current);
    };

    var task;
    var taskSuccess = false;

    var tryCheckStatus = function()
    {
        if(!checkStatusId) clearTimeout(checkStatusId);
        var iWindow = getAppWindow();
        if(!(iWindow && iWindow.config && iWindow.$))
        {
            checkStatusId = setTimeout(tryCheckStatus, 1000);
        }
        else
        {
            checkStatusId = setTimeout(checkStatus, 200);
        }
    }

    var checkStatus = function()
    {
        if(taskSuccess) return;
        var iWindow = getAppWindow();
        //if(!iWindow || !iWindow.$) return checkStatus();

        task = tasks[current];
        if(!task) checkStatus();
        var appCode = task.nav.app || task.nav.menuModule || task.nav['module'];
        var app = getApp(appCode);
        if(!app) return;

        $$ = iWindow.$;
        if(!checkIsFormPage(task, iWindow, appCode))
        {
            taskSuccess = false;
            setTaskStatus(true, false, false);
            getNeedHightlightDom(appCode, iWindow, app);
        }
        else
        {
            setTaskStatus(true, true, false);
            form = $$(task.nav.form);
            tryCheckFormStatusReady();
        }
    }

    var checkIsFormPage = function(task, iWindow, appCode)
    {
        var pageConfig = iWindow.config;
        var currentModule  = (iWindow.TUTORIAL ? iWindow.TUTORIAL['module'] : pageConfig ? pageConfig.currentModule : '').toLowerCase();
        var currentMethod  = (iWindow.TUTORIAL ? iWindow.TUTORIAL['method'] : pageConfig ? pageConfig.currentMethod : '').toLowerCase();
        return task.nav['module'].toLowerCase() === currentModule && task.nav['method'].toLowerCase() === currentMethod && (!task.nav.app || task.nav.app === appCode);
    }

    var getNeedHightlightDom = function(appCode, iWindow, app)
    {
        $$ = iWindow.$;
        firstMenu = isInFirstMenu(appCode, iWindow)
        if(firstMenu)
        {
            tip = lang.targetAppTip.replace('%s', app.text || lang.target);
            forcusOnDom(firstMenu, tip);
            return;
        }

        var menuModule = task.nav.menuModule || task.nav['module'];
        var $navbar    = $$('#navbar');
        if(task.nav.app == 'admin') $navbar = $$('#settings');
        var $navbarItem = $navbar.find('[data-id="' + menuModule + '"]');
        var targetPageTip = lang.targetPageTip.replace('%s', task.nav.targetPageName || lang.target);
        if($navbarItem.length && !$navbarItem.hasClass('active'))
        {
            forcusOnDom($navbarItem, targetPageTip);
            return;
        }
        else
        {
            var $domList = $$(task.nav.target)
            if($domList.length === 0) $domList = $$(task.nav.menu)
            forcusOnDom($domList.last(), targetPageTip);
        }

    }

    var isInFirstMenu = function(appCode)
    {
        var $appNav = appsWindow.$('#menuMainNav > li[data-app="' + appCode + '"]');
        var lastApp = appsWindow.$.apps.getLastApp();
        if(appCode !== lastApp.code)
        {
            if($appNav.css('display') === 'none' && appsWindow.$('#menuMoreList').css('display') !== 'none') $appNav = appsWindow.$('#menuMoreList > li[data-app="' + appCode + '"]');
            return $appNav;
        }

        return false;

    }

    var forcusOnDom = function(dom, tip)
    {
        clearTips();
        if(dom.length > 0) highlight(dom)
        if(dom.length > 0 && tip) showToolTip(dom, tip)
    }

    var bindFormListenEvent = function(form, fieldSelector)
    {
        form.off('.tutorial').off('submit');
        console.log('fieldSelector is', fieldSelector)
        if(fieldSelector.includes('dtable-checkbox'))
        {
            form.on('click.tutorial', fieldSelector, fieldChangeEvent)
        }
        else if(form.is('form'))
        {
            form.on('change.tutorial', fieldSelector, fieldChangeEvent)
            form.on('blur.tutorial', fieldSelector, fieldChangeEvent)
        }

        if(form.is('form'))
        {
            if(task.nav.submit) form.on('click.tutorial', task.nav.submit, onFormSubmit);
            else form.submit(onFormSubmit);
        }
        else
        {
            form.find(task.nav.submit).off();
            form.find(task.nav.submit).on('click.tutorial', onFormSubmit);
        }
    }

    var tryCheckFormStatusReady = function()
    {
        if(checkFormReadyId) clearTimeout(checkFormReadyId);

        var iWindow = getAppWindow();
        if(!(iWindow && iWindow.config && iWindow.$))
        {
            checkFormReadyId = setTimeout(tryCheckFormStatusReady, 1000);
        }
        else
        {
            checkFormReadyId = setTimeout(checkFormStatusReady, 200);
        }
    }

    var checkFormStatusReady = function()
    {
        var iWindow = getAppWindow();
        form = iWindow.$(task.nav.form);
        console.log(form)
        if(!form.is('form') && current !== 'linkStory') form = form.find('form')
        if(!form) return;

        var fieldSelector = getFieldSelector(task, iWindow);
        bindFormListenEvent(form, fieldSelector);

        formwrapper = getFormWrapper(form);

        $formTarget = $task.find('[data-target="form"]');
        forcusOnDom(formWrapper, $formTarget.text())
    }

    var getFormWrapper = function(form)
    {
        if(!form)
        {
            var iWindow = getAppWindow();
            form = iWindow.$(task.nav.form);
        }

        console.log('form is', form)
        formWrapper = form.closest('#mainContent');
        if(!formWrapper.length) formWrapper = form;

        return formWrapper;
    }

    var fieldChangeEvent = function()
    {
        clearInterval(checkStatusId)
        var iWindow = getAppWindow();
        var fieldSelector = getFieldSelector(task, iWindow);
        var fieldCheckResult = checkFieldStatusReady(iWindow, fieldSelector);

        if(!fieldCheckResult.submitOK)
        {
            taskSuccess = false
            if(targetStatus.waitField) requestFieldsWarining(targetStatus.waitField);
        }
        else
        {
            setTaskStatus(true, true, true);
            clearInterval(checkStatusId)
            taskSuccess = true;
            $submitBtn = $$(task.nav.submit);
            $submitTarget = $task.find('[data-target="submit"]');
            forcusOnDom($submitBtn, $submitTarget.text())
        }

    }

    var requestFieldsWarining = function(dom )
    {
        if(dom.hasClass("chosen-controled")) dom = dom.next();
        var fieldName = dom.siblings('label').text();
        if(!fieldName) fieldName = dom.find('label').text();
        if(!fieldName) fieldName = dom.parent().siblings('label').text();

        if(fieldName) forcusOnDom(dom, lang.requiredTip.replace('%s', fieldName))

        setTimeout(checkStatus, 1000);
    }

    var checkFieldStatusReady = function(iWindow, fieldSelector)
    {
        pageConfig = iWindow.config;
        targetStatus = {submitOK: true};
        var requiredFields = task.nav.requiredFields || pageConfig.requiredFields;
        if(task.nav.formType === 'table')
        {
            if(current === 'manageTeam') fieldSelector = 'input[name="' + task.nav.requiredFields + '"]';
            else fieldSelector = '.dtable-checkbox';

            var $formItem = $$(fieldSelector);
            targetStatus.form = $formItem && ($formItem.eq(1).hasClass('checked') || ($formItem.val() !== undefined && $formItem.val() !== null && $formItem.val() !== '' && $formItem.val() !== '0'));
            if(!targetStatus.form) {
                targetStatus.submitOK = false;
                targetStatus.waitField = $formItem.closest('div');
            }
        }
        else if(requiredFields)
        {
            requiredFields = requiredFields.split(',');
            $.each(requiredFields, function(_, requiredId)
            {
                var $required = $$('#' + requiredId);
                var $authBlock = !$required.is('input') ? $required.find('input').last() : $required;
                if($authBlock.length)
                {
                    var val = $authBlock.val();
                    if(val === undefined || val === null || val === '' || val === '0')
                    {
                        targetStatus.submitOK = false;
                        if(!targetStatus.waitField) targetStatus.waitField = $required;
                    }
                }
            });
        }

        return targetStatus;
    }

    var onFormSubmit = function(e)
    {
        var iWindow = getAppWindow();
        var fieldSelector = getFieldSelector(task, iWindow);
        var status = checkFieldStatusReady(iWindow, fieldSelector);
        if(status.submitOK)
        {
            setTaskStatus(true, true, true, true);
            taskSuccess = true
            finishTask();
        }
        else
        {
            requestFieldsWarining(status.waitField)
        }

        e.preventDefault();
        e.stopPropagation();

        return false;
    }

    var getFieldSelector = function(task, iWindow)
    {
        pageConfig = iWindow.config;
        var fieldSelector = '';
        var requiredFields = task.nav.requiredFields || pageConfig.requiredFields;

        if(task.nav.formType === 'table')
        {
            if(current === 'manageTeam') fieldSelector = 'input[name="' + task.nav.requiredFields + '"]';
            else fieldSelector = '.dtable-checkbox';
        }
        else if(requiredFields)
        {
            requiredFields = requiredFields.split(',');
            $.each(requiredFields, function(_, requiredId){ fieldSelector += ',' + '#' + requiredId;});
            if(fieldSelector.length > 1) fieldSelector = fieldSelector.substring(1);
        }

        return fieldSelector;
    }

    var setTaskStatus = function($nav, $form, $submitOk, $submitSuccess)
    {
        $navTarget    = $task.find('[data-target="nav"]');
        $formTarget   = $task.find('[data-target="form"]');
        $submitTarget = $task.find('[data-target="submit"]');

        if($nav && !$form && !$submitOk) $navTarget.addClass('active');
        if($nav && $form  && !$submitOk)
        {
            $navTarget.removeClass('active');
            $formTarget.addClass('active');
        }
        if($nav && $form  && $submitOk)
        {
            $navTarget.removeClass('active');
            $formTarget.removeClass('active');
            $submitTarget.addClass('active');
        }

        $navTarget.toggleClass('finish', !!$form);
        $formTarget.toggleClass('finish', !!$submitOk);
        $submitTarget.toggleClass('finish', !!$submitSuccess);
        $navTarget.toggleClass('wait', !$navTarget.is('.finish,.active'));
        $formTarget.toggleClass('wait', !$formTarget.is('.finish,.active'));
        $submitTarget.toggleClass('wait', !$submitTarget.is('.finish,.active'));
        $openTaskPage.toggleClass('open', $form);
    }

    /** Init apps iframe page */
    function initAppsPage()
    {
        var appsIframe = $('#iframePage').get(0);
        appsIframe.onload = appsIframe.onreadystatechange = function()
        {
            appsWindow.$(appsWindow.document).on('reloadapp.apps openapp.apps showapp.apps closeapp.apps hideapp.apps', function()
            {
                appsWindow.$('body').find('.popover').remove();
                tryCheckTutorialState(1000);
            });
            appsWindow.$('#menuMoreList').siblings('a').on('click', function(){
                tryCheckTutorialState(1000);
            });

            /* Open referer page in app tab */
            if(tutorialReferer) appsWindow.$.apps.openApp(tutorialReferer);

            updateUI();
        };
    }

    /* Quit tutorial mode */
    function quitTutorial()
    {
        var url = $.createLink('tutorial', 'quit');
            if(typeof navigator.sendBeacon === 'function') navigator.sendBeacon(url);
            else $.ajax({url: url, dataType: 'json', async: false});
    }

    /** Init current tutorial page */
    function initTutorial()
    {
        if(finishCount >= totalCount) showModal(true);

        $(document).off('click', '.btn-task').on('click', '.btn-task', function()
        {
            showTask($(this).data('name'));
            tryCheckStatus();
        }).on('click', '.btn-open-target-page', function()
        {
            appsWindow.$.apps.openApp(tasks[current].url);
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
