let currentGuide   = '';
let currentTask    = '';
let currentStep    = null;
let popover        = null;

function highlightStepTarget($target, step, popoverOptions)
{
    if(popover)
    {
        const triggerElement = popover.getTriggerElement();
        if(triggerElement) triggerElement.classList.remove('tutorial-hl');
        popover.destroy();
    }
    popoverOptions = $.extend(
    {
        key             : 'tutorialPopover',
        title           : step.title,
        strategy        : 'fixed',
        show            : true,
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
        footer:
        {
            component: 'toolbar',
            props: {
                className: 'py-3 px-4 justify-between',
                items:
                [
                    {component: 'span', html: `${step.index + 1}/${step.task.steps.length}`},
                    {type: 'primary', text: lang.nextStep, onClick: () => goToNextStep(step)},
                ]
            }
        }
    }, step.popover, popoverOptions);
    if(popoverOptions.title === null)
    {
        const text = $target.text().trim();
        if(['openApp'].includes(step.type)) popoverOptions.title = lang.clickTipFormat.replace('%s', text);
    }
    const scope = getStepScope(step);
    popover = new scope.zui.Popover($target, popoverOptions);

    const $doc = scope.$(scope.document);
    if($doc.data('tutorialCheckBinding')) return;
    $doc.data('tutorialCheckBinding', true).on('click', '[zui-tutorial-step]', function()
    {
        if(!currentStep) return;
        const stepID = this.getAttribute('zui-tutorial-step');
        if(stepID === currentStep.id) activeNextStep();
    });
}

function activeNextStep(step)
{
    step = step || currentStep;
    if(!step || step.task.steps.length - 1 === step.index) return;
    activeTaskStep(step.guide.name, step.task.name, step.index + 1);
}

function goToNextStep(step)
{
    step = step || currentStep;
    if(!step) return;

    const $target = getStepScope(step).$(`[zui-tutorial-step="${step.id}"]`);
    if(!$target.length) $target.click();
    activeNextStep(step);
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

    if(!$targetNav.length) return;
    const popoverOptions = {placement: 'right'};
    if($targetNav.is('#menuMoreBtn'))
    {
        popoverOptions.title   = null;
        popoverOptions.footer  = undefined;
        popoverOptions.content = lang.clickAndOpenIt.replace('%s', $targetNav.text().trim()).replace('%s', $appNav.text().trim());
    }
    else
    {
        $targetNav = $targetNav.find('a');
        $targetNav.attr('zui-tutorial-step', step.id);
    }
    return highlightStepTarget($targetNav, step, popoverOptions);
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

function getStepScope(step)
{
    if(step.scope) return step.scope;
    const homeWindow = $('#iframePage')[0].contentWindow;
    if(step.type === 'openApp')
    {
        step.scope = homeWindow;
    }
    else
    {
        const openedApp = homeWindow.$.apps.openedApps[step.app];
        if(openedApp) step.scope = openedApp.iframe.contentWindow;
    }
    return step.scope;
}

function activeTaskStep(guideName, taskName, stepIndex)
{
    const $guide = $('#tutorialTabs').find(`.tutorial-guide[data-name="${guideName}"]`);
    if(!$guide.length) return;
    const $task = $guide.find(`.tutorial-task[data-name="${taskName}"]`);
    if(!$task.length) return;

    const $steps = $task.find('.tutorial-step').removeClass('active');
    $steps.filter(`[data-step="${stepIndex}"]`).addClass('active');

    const guide = guides[guideName];
    const task  = guide.tasks[taskName];
    const step  = task.steps[stepIndex];

    if(!step.id)
    {
        step.id     = `${guideName}-${taskName}-${stepIndex}`;
        step.guide  = guide;
        step.task   = task;
        step.index  = stepIndex;
        step.isLast = stepIndex === task.steps.length - 1;
    }

    currentStep = step;

    if(step.type === 'openApp') return showOpenAppStep(step);
}

function activeTask(guideName, taskName, step = 0)
{
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
