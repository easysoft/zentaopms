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

        if(!$menuNav.data('tutorial.checkOpenAppStep'))
        {
            const checkOpenAppStep = (event, info) =>
            {
                if(!currentStep || currentStep.type !== 'openApp') return;
                if(event.type === 'shown' && info[0].options.target !== '#menuMoreList') return;
                stepPresenters.openApp(currentStep);
            };
            scope.$(scope).on('resize', checkOpenAppStep);
            scope.$('#menuMoreBtn').on('shown', checkOpenAppStep);
            $menuNav.data('tutorial.checkOpenAppStep', true);
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
        return highlightStepTarget((scope) => scope.$(step.target), step, {placement: 'bottom'});
    },
    clickNavbar: function(step)
    {
        return highlightStepTarget((scope) => scope.$('.#'.includes(step.target[0]) ? step.target : `#navbar>.nav>.item>a[data-id="${step.target}"]`), step);
    },
    clickMainNavbar: function(step)
    {
        return highlightStepTarget((scope) => scope.$('.#'.includes(step.target[0]) ? step.target : `#mainNavbar .nav>.item>a[data-id="${step.target}"]`), step);
    },
    form: function(step)
    {
        return highlightStepTarget((scope) => {
            const $target = scope.$(step.target || 'form');

            /* Check form. */
            const $form = getStepForm(scope, $target);
            if(!$form.length) console.error(`[TUTORIAL] Cannot find form for step "${step.guide.name} > ${step.task.name} > ${step.title}"`, step);
            $form.attr('zui-tutorial-step', step.id);
            step.$form = $form;

            const $panel = $target.closest('.panel');
            return $panel.length ? $panel : $target;
        }, step, (!step.popover || !step.popover.placement) ? {placement: 'right'} : null);
    },
    saveForm: function(step)
    {
        return highlightStepTarget((scope) => {
            const $target = scope.$(step.target);

            /* Check form. */
            const $form = getStepForm(scope, $target);
            if(!$form.length) console.error(`[TUTORIAL] Cannot find form for step "${step.guide.name} > ${step.task.name} > ${step.title}"`, step);
            $form.attr('zui-tutorial-step', step.id);
            step.$form = $form;

            const $saveBtn = $form.find('[type="submit"]');
            return $saveBtn.length ? $saveBtn : $target;
        }, step);
    },
    selectRow: function(step)
    {
        return highlightStepTarget((scope) => {
            const $target = scope.$(step.target || '.dtable');
            let $table   = $target.closest('[z-use-dtable]');
            if(!$table.length) $table = $target.find('[z-use-dtable]');
            if(!$table.length) $table = scope.$('[z-use-dtable]');
            if(!$table.length) console.error(`[TUTORIAL] Cannot find table for step "${step.guide.name} > ${step.task.name} > ${step.title}"`, step);
            step.$table = $table;

            if(!$table.data('tutorial.selectRowBinding'))
            {
                $table.data('tutorial.selectRowBinding', true);
                const dtable = $table.zui('dtable');
                dtable.setOptions({onCheckChange: function()
                {
                    if(!this.getChecks().length) return;
                    activeNextStep(step);
                }});
            }

            return $target;
        }, step);
    }
};

function getStepForm(scope, $target)
{
    let $form   = $target.closest('form');
    if(!$form.length) $form = $target.find('form');
    if(!$form.length) $form = scope.$('form');

    if(!$form.data('tutorial.formBinding'))
    {
        $form.data('tutorial.formBinding', true);
        const ajaxForm = $form.zui('ajaxForm');
        ajaxForm.setOptions({beforeSubmit: () =>
        {
            if(currentStep.type === 'form')
            {
                const nextStep = currentStep.task.steps[currentStep.index + 1];
                activeNextStep((nextStep && nextStep.type === 'saveForm') ? nextStep : currentStep);
            }
            return false;
        }});
    }

    return $form;
}

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

function isStepFormFilled(step, event)
{
    if(!step.$form || !step.$form.length) return;
    let requiredFields = step.requiredFields;
    if(!requiredFields)
    {
        requiredFields = [];
        const scope = getStepScope(step);
        step.$form.find('.form-group.required').each(function()
        {
            if(!scope.zui.dom.isVisible(this, {checkZeroSize:true})) return;
            const $group = $(this);
            let name = $group.attr('data-name');
            if(!name) name = $group.find('[name]').attr('name');
            if(name) requiredFields.push(name);
        });
        step.requiredFields = requiredFields;
    }
    if(typeof requiredFields === 'string') requiredFields = requiredFields.split(',');
    const formData = new FormData(step.$form[0]);
    for(let i = 0; i < requiredFields.length; i++)
    {
        const fieldName = requiredFields[i];
        if(!formData.has(fieldName) || !formData.get(fieldName)) return false;
    }
    return true;
}

function highlightStepTarget($target, step, popoverOptions)
{
    if(config.debug) showLog('Highlight', step, null, {$target, popover, popoverOptions});
    if(popover) return destroyPopover(() => highlightStepTarget($target, step, popoverOptions));
    ensureStepScope(step, (scope) =>
    {
        if(typeof $target === 'function') $target = $target(scope);
        $target = $target.first();
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
            contentClass    : 'popover-content px-4 whitespace-pre-line',
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
            offset          : 32,
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
                if(!$lightElement.length) $lightElement = $('<div class="tutorial-light-box fixed pointer-events-none rounded" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.4), 0 0 0 9999px rgba(0, 0, 0, 0.4); z-index: 1690; transition: opacity .3s;"></div>').appendTo($body);
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
        step.$target = $target;
        popover = new scope.zui.Popover($target, popoverOptions);
        if(!popoverOptions.notFinalTarget) $target.attr('zui-tutorial-step', step.id);
        $target.scrollIntoView();

        const $doc = scope.$(scope.document);
        if($doc.data('tutorial.checkBinding')) return;
        $doc.data('tutorial.checkBinding', true).on('click change', '[zui-tutorial-step]', function(event, info)
        {
            if(!currentStep || currentStep.checkType !== event.type) return;

            const stepID = this.getAttribute('zui-tutorial-step');
            if(stepID !== currentStep.id) return;

            if(currentStep.type === 'form' && !isStepFormFilled(currentStep, event)) return;

            if(config.debug) showLog(`Step target ${event.type}`, currentStep, null, {stepID, event});
            activeNextStep();
        });
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
    formatStep(step);
    if(config.debug) showLog('Active next step', step);

    if(step.endUrl) openApp(step.endUrl, step.app);
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
    else if(step.type === 'saveForm' && step.$form)
    {
        step.$form[0].submit();
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
    const waitStepScope = (reason) =>
    {
        if(config.debug) showLog(`Wait step scope: ${reason}`, step, null, {scope});
        if(step.waitScopeTimer) clearTimeout(step.waitScopeTimer);
        step.waitScopeTimer = setTimeout(() =>
        {
            delete step.waitScopeTimer;
            ensureStepScope(step, callback);
        }, 500);
    };
    if(!scope || !scope.$) return waitStepScope('no scope');
    if(scope.name !== 'iframePage')
    {
        const $body = scope.$('body');
        if($body.hasClass('loading-page')) return waitStepScope('page is loading');
        const scopePage = ($body.attr('data-page') || '').toLowerCase();
        if(!scopePage) return waitStepScope('page is not ready');
        const scopePageRaw = ($body.attr('data-page-raw') || '').toLowerCase();
        const stepPage = (step.page || '').toLowerCase();
        if(stepPage && stepPage !== scopePage && stepPage !== scopePageRaw) return waitStepScope(`step page "${stepPage}" not match "${scopePage}" or "${scopePageRaw}"`);
    }
    return setTimeout(() => callback(scope), 200);
}

function openApp(url, app)
{
    if(url.includes('#app='))
    {
        const [urlPart, appPart] = url.split('#app=', 2);
        url = urlPart;
        app = appPart;
    }
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

function formatStep(step, guideName, taskName, stepIndex)
{
    if(step.id) return step;

    guideName = guideName || step.guideName;
    taskName  = taskName || step.taskName;
    stepIndex = typeof stepIndex !== 'number' ? step.index : stepIndex;

    const guide = guides[guideName];
    const task  = guide.tasks[taskName];

    step.id        = `${guideName}-${taskName}-${stepIndex}`;
    step.guide     = guide;
    step.task      = task;
    step.index     = stepIndex;
    step.isLast    = stepIndex === task.steps.length - 1;
    step.checkType = step.type === 'form' ? 'change' : 'click';

    if(step.type === 'openApp' && !task.app) task.app = step.app;
    if(!step.app && stepIndex) step.app = task.steps[stepIndex - 1].app;
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

    return step;
}

function activeTaskStep(guideName, taskName, stepIndex)
{
    const guide = guides[guideName];
    const task  = guide.tasks[taskName];
    const step  = task.steps[stepIndex];

    if(currentStep === step) return;
    task.guide = guide;
    if(!updateTaskUI(task, {active: true, status: 'doing', currentStepIndex: stepIndex})) return;

    formatStep(step, guideName, taskName);

    if(step.url)                              openApp(step.url, step.app);
    else if(stepIndex === 0 && task.startUrl) openApp(step.task.startUrl, step.task.app);

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
