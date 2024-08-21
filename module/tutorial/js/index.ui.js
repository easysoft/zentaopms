let currentGuide   = '';
let currentTask    = '';
let currentStep    = null;
let popover        = null;

function showLog(name, step, moreTitles, moreInfos)
{
    if(!config.debug) return;
    const titles = ['%c TUTORIAL '];
    const titleColors = ['color:#fff;font-weight:bold;background:#9c27b0'];
    if(step)
    {
        if(step.guide)
        {
            titles.push(`%c ${step.guide.title || step.guide.name} `);
            titleColors.push('background:rgba(156, 39, 176, 0.2);color:#9c27b0;');
        }
        if(step.task)
        {
            titles.push(`%c> ${step.task.title || step.task.name} `);
            titleColors.push('background:rgba(156, 39, 176, 0.2);color:#9c27b0;');
        }
        titles.push(`%c> ${step.index + 1}: ${step.title || step.name} `);
        titleColors.push('background:rgba(156, 39, 176, 0.2);color:#9c27b0;');
    }
    if(name)
    {
        titles.push(`%c ${name} `);
        titleColors.push('color:#9c27b0;font-weight:bold;');
    }
    if(!Array.isArray(moreTitles) && moreTitles) moreTitles = [moreTitles];
    if(Array.isArray(moreTitles) && typeof moreTitles[0] === 'string' && (moreTitles[0].startsWith('success:') || moreTitles[0].startsWith('error:')))
    {
        const message = moreTitles.shift();
        const [type, content] = message.split(':', 2);
        titles.push(`%c ${content} `);
        titleColors.push(`color:${type === 'error' ? '#f56c6c' : '#67c23a'};`);
    }
    console.groupCollapsed(titles.join(''), ...titleColors, ...(moreTitles || []));
    if(step) console.trace('step', step);
    if(moreInfos)
    {
        if($.isPlainObject(moreInfos)) Object.keys(moreInfos).forEach((infoName) => console.log(infoName, moreInfos[infoName]));
        else console.log(moreInfos);
    }
    console.groupEnd();
}

if(config.debug) showLog('Guides data', null, null, {guides});

const stepPresenters =
{
    openApp: function(step)
    {
        const scope    = getStepScope(step);
        const $menuNav = scope.$('#menuNav');

        if(!$menuNav.data('checkOpenAppStep'))
        {
            const checkOpenAppStep = (event, info) =>
            {
                if(!currentStep || currentStep.type !== 'openApp') return;
                if(event.type === 'shown' && info[0].options.target !== '#menuMoreList') return;
                showOpenAppStep(currentStep);
            };
            scope.$(scope).on('resize', checkOpenAppStep);
            scope.$('#menuMoreBtn').on('shown', checkOpenAppStep);
            $menuNav.data('checkOpenAppStep', true);
        }

        const $menuMainNav = $menuNav.find('#menuMainNav');
        const $appNav = $menuMainNav.children(`li[data-app="${step.app}"]`);
        let $targetNav = $appNav;
        if($appNav.hasClass('hidden'))
        {
            const $menuMoreList = $menuNav.find('#menuMoreList.in');
            if($menuMoreList.length) $targetNav = $menuMoreList.children(`li[data-app="${step.app}"]`);
            else                     $targetNav = $menuNav.find('#menuMoreBtn');
        }

        const popoverOptions = {placement: 'right'};
        if($targetNav.is('#menuMoreBtn'))
        {
            $.extend(popoverOptions, {title: null, footer: undefined, content: lang.clickAndOpenIt.replace('%s', $targetNav.text().trim()).replace('%s', $appNav.text().trim()), notFinalTarget: true});
        }
        else
        {
            $targetNav = $targetNav.find('a');
        }
        scope.$.apps.closeApp(step.app);
        return highlightStepTarget($targetNav, step, popoverOptions);
    },
    click: function(step)
    {
        const scope   = getStepScope(step);
        const $target = scope.$(step.target);
        return highlightStepTarget($target, step);
    },
    clickNavbar: function(step)
    {
        const scope   = getStepScope(step);
        const $target = scope.$('.#'.includes(step.target[0]) ? step.target : `#navbar>.nav>.item>a[data-id="${step.target}"]`);
        return highlightStepTarget($target, step);
    },
    clickMainNavbar: function(step)
    {
        const scope   = getStepScope(step);
        const $target = scope.$('.#'.includes(step.target[0]) ? step.target : `#mainNavbar nav>.item>a[data-id="${step.target}"]`);
        return highlightStepTarget($target, step);
    },
    form: function(step)
    {
        const scope   = getStepScope(step);
        const $target = scope.$(step.target || 'form');
        return highlightStepTarget($target, step);
    },
    saveForm: function(step)
    {
        const scope   = getStepScope(step);
        const $target = scope.$(step.target);

        /* Check form. */
        let $form   = $target.closest('form');
        if(!$form.length) $form = scope.$('form');
        if(!$form.length) return console.error(`[TUTORIAL] Cannot find form for step "${step.guide.name} > ${step.task.name} > ${step.title}"`, step);

        const $saveBtn = $form.find('[type="submit"]');
        return highlightStepTarget($saveBtn.length ? $saveBtn : $target, step);
    }
};

function destroyPopover(callback)
{
    if(!popover)
    {
        if(callback) callback();
        return;
    };
    const $trigger = $(popover.trigger);
    if($trigger.length)
    {
        $trigger.removeClass('tutorial-hl');
        $trigger.closest('body').find('.tutorial-light-box').addClass('opacity-0');
    }
    if(callback) setTimeout(callback, popover.shown ? 300 : 200);
    popover.hide(true);
    popover = null;
}

function highlightStepTarget($target, step, popoverOptions)
{
    if(config.debug) showLog('Highlight', step, null, {$target, popover, popoverOptions});
    if(popover) return destroyPopover(() => highlightStepTarget($target, step, popoverOptions));
    if(!$target.length) return console.error(`[TUTORIAL] Cannot find target for step "${step.guide.title || step.guide.name} > ${step.task.title || step.task.name} > ${step.title}"`, step);
    popoverOptions = $.extend(
    {
        key             : `tutorial-popover-${step.id}`,
        title           : step.title,
        strategy        : 'fixed',
        show            : true,
        limitInScreen   : true,
        mask            : false,
        trigger         : 'manual',
        destroyOnHide   : true,
        closeBtn        : false,
        content         : step.desc,
        contentClass    : 'popover-content px-4',
        className       : 'tutorial-popover rounded-md',
        titleClass      : 'popover-title text-lg pl-1',
        minWidth        : 280,
        maxWidth        : 400,
        headingClass    : 'popover-heading bg-transparent',
        elementShowClass: 'with-popover-show tutorial-hl',
        destroyOnHide   : true,
        shift           : true,
        hideOthers      : false,
        hideNewOnHide   : false,
        footer:
        {
            component: 'toolbar',
            props:
            {
                className: 'py-3 px-4 justify-between',
                items:
                [
                    {component: 'span', html: `${step.index + 1}/${step.task.steps.length}`},
                    {type: 'primary', text: lang.nextStep, onClick: () => goToNextStep(step)},
                ]
            }
        },
        onHide: function()
        {
            $(this.trigger).closest('body').find('.tutorial-light-box').addClass('opacity-0');
        },
        onLayout: function(info)
        {
            const $trigger = $(info.trigger);
            const $body = $trigger.closest('body');
            const triggerRect = info.trigger.getBoundingClientRect();
            let $lightElement = $body.find('.tutorial-light-box');
            if(!$lightElement.length) $lightElement = $('<div class="tutorial-light-box fixed pointer-events-none rounded" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.4), 0 0 0 9999px rgba(0, 0, 0, 0.4); z-index: 1690; transition: top .1s, left .1s, opacity .3s;"></div>').appendTo($body);
            $lightElement.removeClass('opacity-0').css(
            {
                top         : triggerRect.top,
                left        : triggerRect.left,
                width       : triggerRect.width,
                height      : triggerRect.height,
                borderRadius: $trigger.css('borderRadius')
            });
        }
    }, step.popover, popoverOptions);
    if(popoverOptions.title === null)
    {
        const text = $target.text().trim();
        if(['openApp'].includes(step.type)) popoverOptions.title = lang.clickTipFormat.replace('%s', text);
    }
    const scope = getStepScope(step);
    popover = new scope.zui.Popover($target, popoverOptions);
    if(!popoverOptions.notFinalTarget) $target.attr('zui-tutorial-step', step.id);
    $target.scrollIntoView();

    const $doc = scope.$(scope.document);
    if($doc.data('tutorialCheckBinding')) return;
    $doc.data('tutorialCheckBinding', true).on('click change', '[zui-tutorial-step]', function(event)
    {
        if(!currentStep || currentStep.checkType !== event.type) return;
        const stepID = this.getAttribute('zui-tutorial-step');
        if(stepID === currentStep.id)
        {
            if(config.debug) showLog(`Step target ${event.type}`, currentStep, null, {stepID, event});
            activeNextStep();
        }
    });
}

function updateTaskUI(task, change)
{
    if(change) $.extend(task, change);

    const guideName = task.guide.name;
    const taskName  = task.name;
    const $guide = $('#tutorialTabs').find(`.tutorial-guide[data-name="${guideName}"]`);
    if(!$guide.length) return;
    const $task = $guide.find(`.tutorial-task[data-name="${taskName}"]`);
    if(!$task.length) return;

    $task.attr('data-status', task.status).toggleClass('active', !!task.active);
    const $steps = $task.find('.tutorial-step').removeClass('active');
    if(task.currentStepIndex !== undefined) $steps.filter(`[data-step="${task.currentStepIndex}"]`).addClass('active');

    if(task.active)
    {
        const $tabToggle = $(`#tutorialTabs .tabs-nav>.nav-item[data-key="${task.guide.type}"]>a`);
        if($tabToggle.length && !$tabToggle.hasClass('active')) setTimeout(() => $tabToggle[0].click(), 200);
        $task.scrollIntoView();
    }

    const url = $.createLink('tutorial', 'index', `referer=&guide=${guideName}&task=${taskName}`);
    const prevState = window.history.state;
    if(prevState.url !== url) window.history.replaceState({url}, '', url);

    return true;
}

function getNextTask(task)
{
    const guideTaskNames = Object.keys(task.guide.tasks);
    if(guideTaskNames.length === task.index + 1) return;
    for(let i = 0; i < guideTaskNames.length; ++i)
    {
        const name = guideTaskNames[i];
        const thisTask = task.guide.tasks[name];
        if(thisTask.index === task.index + 1) return thisTask;
    }
}

function activeNextStep(step)
{
    step = step || currentStep;
    if(!step) return;
    if(config.debug) showLog('Active next step', step);
    destroyPopover(() =>
    {
        if(step.task.steps.length - 1 === step.index)
        {
            updateTaskUI(step.task, {status: 'done', active: false});
            const nextTask = getNextTask(step.task);
            zui.Modal.alert(
            {
                content: lang.congratulateTask.replace('<span class="task-name-current"></span>', step.task.title),
                actions: nextTask ? [{type: 'confirm', btnType: 'primary', text: lang.nextTask, onClick: () => activeTask(step.guide.name, nextTask.name)}, 'cancel'] : ['confirm']
            });
            return;
        }
        activeTaskStep(step.guide.name, step.task.name, step.index + 1);
    });
}

function goToNextStep(step)
{
    step = step || currentStep;
    if(!step) return;

    if(step.checkType === 'click')
    {
        const $target = getStepScope(step).$(`[zui-tutorial-step="${step.id}"]`);
        if($target.length) return $target.trigger('click', 'skipCheckStep');
    }

    setTimeout(() => activeNextStep(step), 200);
}

function toggleActiveTarget(type, name, toggle)
{
    if(!name) return false;
    const $target = $('#tutorialTabs').find(`.tutorial-${type}[data-name="${name}"]`);
    if(!$target.length) return;

    $target.toggleClass('active', toggle);
    $target.closest(`.tutorial-${type}-list`).toggleClass(`has-active-${type}`, toggle);
    return true;
}

function unactiveTask()
{
    toggleActiveTarget('task', currentTask, false);
    currentTask = '';
    currentStep = null;
}

function getHomeScope()
{
    return $('#iframePage')[0].contentWindow;
}

function getStepScope(step)
{
    if(step.scope && step.scope.name) return step.scope;
    step.scope = null;
    const homeScope = getHomeScope();
    if(step.type === 'openApp')
    {
        step.scope = homeScope;
    }
    else if(homeScope.$ && homeScope.$.apps)
    {
        const openedApp = step.app ? homeScope.$.apps.openedApps[step.app] : homeScope.$.apps.getLastApp();
        if(openedApp) step.scope = openedApp.iframe.contentWindow;
    }
    return step.scope;
}

function ensureStepScope(step, callback)
{
    const scope = getStepScope(step);
    if(scope && scope.$ && (scope.name === 'iframePage' || (!scope.$('body').hasClass('loading-page') && scope.$('body').attr('data-page') && (!step.page || scope.$('body').attr('data-page') === step.page))))
    {
        if(config.debug) showLog('Ensure step scope', step, null, {scope});
        return setTimeout(() => callback(scope), 300);
    }
    step.waitScopeTimer = setTimeout(() => ensureStepScope(step, callback), 200);
}

function openApp(url, app)
{
    const homeScope = getHomeScope();
    if(!homeScope.$ || !homeScope.$.apps)
    {
        setTimeout(() => openApp(url, app), 200);
        return;
    }
    if(Array.isArray(url)) url = $.createLink.apply(null, url);
    const openedApp = homeScope.$.apps.open(url, app, {forceReload: true});
    if(openedApp && openedApp.iframe.contentWindow.$)
    {
        openedApp.iframe.contentWindow.$('body').addClass('loading-page');
    }
}

function activeTaskStep(guideName, taskName, stepIndex)
{
    const guide = guides[guideName];
    const task  = guide.tasks[taskName];
    const step  = task.steps[stepIndex];
    task.guide = guide;
    if(currentStep === step) return;

    if(!updateTaskUI(task, {active: true, status: 'doing', currentStepIndex: stepIndex})) return;

    if(!step.id)
    {
        step.id        = `${guideName}-${taskName}-${stepIndex}`;
        step.guide     = guide;
        step.task      = task;
        step.index     = stepIndex;
        step.isLast    = stepIndex === task.steps.length - 1;
        step.checkType = step.type === 'form' ? 'change' : (step.type === 'saveForm' ? 'complete' : 'click');

        if(step.type === 'openApp' && !task.app) task.app = step.app;
        if(!step.app) step.app = task.app || guide.app;
        if(!step.app)
        {
            const taskNames = Object.keys(guide.tasks);
            for(let i = 0; i < taskNames.length; i++)
            {
                const thisTask = guide.tasks[taskNames[i]];
                if(thisTask.app && thisTask.index < task.index)
                {
                    step.app = thisTask.app;
                    break;
                }
            }
        }
    }

    if(step.url)                              openApp(step.url, step.app);
    else if(stepIndex === 0 && task.startUrl) openApp(task.startUrl, task.app);

    currentStep = step;
    if(config.debug) showLog('Active step', step);
    ensureStepScope(step, () =>
    {
        const presenter = stepPresenters[step.type];
        if(config.debug) showLog('Present step', step, null, {presenter});
        if(presenter) presenter(step);
    });
}

function activeTask(guideName, taskName, stepIndex)
{
    if(stepIndex === undefined) stepIndex = 0;
    if(currentGuide !== guideName) activeGuide(guideName);

    if(currentTask && currentTask !== taskName) unactiveTask();

    if(currentTask !== taskName)
    {
        currentTask = taskName;
        toggleActiveTarget('task', currentTask, true);
    }

    activeTaskStep(guideName, taskName, stepIndex);
}

function unactiveGuide()
{
    if(!toggleActiveTarget('guide', currentGuide, false)) return;

    if(currentTask)
    {
        const guide = guides[currentGuide];
        if(guide && guide.tasks[currentTask]) unactiveTask();
    }
    currentGuide = '';
}

function activeGuide(guideName)
{
    if(currentGuide && currentGuide !== guideName) unactiveGuide();

    if(currentGuide !== guideName)
    {
        currentGuide = guideName;
        toggleActiveTarget('guide', currentGuide, true);
    }
}

window.activeTask = activeTask;

window.getCurrentStepID = function()
{
    return currentStep ? currentStep.id : '';
};

window.handleClickGuide = function(event)
{
    const guideName = $(event.target).closest('.tutorial-guide').data('name');
    if(guideName === currentGuide) unactiveGuide();
    else activeGuide(guideName);
};

window.handleClickTask = function(event)
{
    const $task     = $(event.target).closest('.tutorial-task');
    const taskName  = $task.data('name');
    const guideName = $task.closest('.tutorial-guide').data('name');
    activeTask(guideName, taskName);
};
