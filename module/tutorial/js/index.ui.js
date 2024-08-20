let currentGuide   = '';
let currentTask    = '';
let currentStep    = null;
let popover        = null;

if(config.debug) console.log('[TUTORIAL] guides', guides);

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
    if(config.debug)
    {
        console.groupCollapsed('[TUTORIAL] Destroy popover', popover.gid, {popover, $trigger});
        console.trace();
        console.groupEnd();
    }
    if(callback) setTimeout(callback, popover.shown ? 300 : 200);
    popover.hide(true);
    popover = null;
}

function highlightStepTarget($target, step, popoverOptions)
{
    if(config.debug)
    {
        console.groupCollapsed('[TUTORIAL] Highlight target', {step, $target, popover, popoverOptions});
        console.trace();
        console.groupEnd();
    }
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
        headingClass    : 'popover-heading bg-transparent',
        elementShowClass: 'with-popover-show tutorial-hl',
        destroyOnHide   : true,
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
            if(!$lightElement.length) $lightElement = $('<div class="tutorial-light-box fixed pointer-events-none rounded" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.4), 0 0 0 9999px rgba(0, 0, 0, 0.4); z-index: 999; transition: top .2s, left .2s, opacity .2s;"></div>').appendTo($body);
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
    popover = new zui.Popover($target, popoverOptions);
    if(!popoverOptions.notFinalTarget) $target.attr('zui-tutorial-step', step.id);
    $target.scrollIntoView();

    const $doc = scope.$(scope.document);
    if($doc.data('tutorialCheckBinding')) return;
    $doc.data('tutorialCheckBinding', true).on('click change', '[zui-tutorial-step]', function(event)
    {
        if(!currentStep || currentStep.checkType !== event.type) return;
        const stepID = this.getAttribute('zui-tutorial-step');
        if(stepID === currentStep.id) activeNextStep();
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
    if(config.debug)
    {
        console.groupCollapsed('[TUTORIAL] Active next step', step.id, step.index, step.title, step);
        console.trace();
        console.groupEnd();
    }
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

function showOpenAppStep(step)
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
}

function showClickStep(step)
{
    const scope   = getStepScope(step);
    const $target = scope.$(step.target);
    return highlightStepTarget($target, step);
}

function showClickNavbarStep(step)
{
    const scope   = getStepScope(step);
    const $target = scope.$('.#'.includes(step.target[0]) ? step.target : `#navbar>.nav>.item>a[data-id="${step.target}"]`);
    return highlightStepTarget($target, step);
}

function showClickMainNavbarStep(step)
{
    const scope   = getStepScope(step);
    const $target = scope.$('.#'.includes(step.target[0]) ? step.target : `#mainNavbar nav>.item>a[data-id="${step.target}"]`);
    return highlightStepTarget($target, step);
}

function showFormStep(step)
{
    const scope   = getStepScope(step);
    const $target = scope.$(step.target || 'form');
    return highlightStepTarget($target, step);
}

function showSaveFormStep(step)
{
    const scope   = getStepScope(step);
    const $target = scope.$(step.target);

    /* Check form. */
    let $form   = $target.closest('form');
    if(!$form.length) $form = scope.$('form');
    if(!$form.length) return console.error(`[TUTORIAL] Cannot find form for step "${step.guide.title || step.guide.name} > ${step.task.title || step.task.name} > ${step.title}"`, step);

    const $saveBtn = $form.find('[type="submit"]');
    return highlightStepTarget($saveBtn.length ? $saveBtn : $target, step);
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
    if(step.scope) return step.scope;
    const homeScope = getHomeScope();
    if(step.type === 'openApp')
    {
        step.scope = homeScope;
    }
    else
    {
        const openedApp = step.app ? homeScope.$.apps.openedApps[step.app] : homeScope.$.apps.getLastApp();
        if(openedApp) step.scope = openedApp.iframe.contentWindow;
    }
    return step.scope;
}

function ensureStepScope(step, callback)
{
    const scope = getStepScope(step);
    if(config.debug) console.log(`[TUTORIAL] Ensure step scope "${step.guide.title || step.guide.name} > ${step.task.title || step.task.name} > ${step.title}"`, {step, scope});
    if(scope && scope.$ && (scope.name === 'iframePage' || (!scope.$('body').hasClass('loading-page') && scope.$('body').attr('data-page') && (!step.page || scope.$('body').attr('data-page') === step.page)))) return setTimeout(() => callback(scope), 300);
    step.waitScopeTimer = setTimeout(() => ensureStepScope(step, callback), 200);
}

function openApp(url, app)
{
    if(Array.isArray(url)) url = $.createLink.apply(null, url);
    const homeScope = getHomeScope();
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
        step.checkType = step.type === 'form' ? 'change' : 'click';

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
    if(config.debug) console.log(`[TUTORIAL] Active step "${guideName} > ${taskName} > ${step.title}"`, step);
    ensureStepScope(step, () =>
    {
        if(config.debug) console.log(`[TUTORIAL] Show step "${guideName} > ${taskName} > ${step.title}"`, step);
        if(step.type === 'click')       return showClickStep(step);
        if(step.type === 'clickNavbar') return showClickNavbarStep(step);
        if(step.type === 'form')        return showFormStep(step);
        if(step.type === 'saveForm')    return showSaveFormStep(step);
        if(step.type === 'openApp')     return showOpenAppStep(step);
        if(step.type === 'clickMainNavbar') return showClickMainNavbarStep(step);
    });
}

function activeTask(guideName, taskName, step)
{
    if(step === undefined) step = 0;
    if(currentGuide !== guideName) activeGuide(guideName);

    if(currentTask && currentTask !== taskName) unactiveTask();

    if(currentTask !== taskName)
    {
        currentTask = taskName;
        toggleActiveTarget('task', currentTask, true);
    }

    activeTaskStep(guideName, taskName, step);
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
