let currentGuide   = '';
let currentTask    = '';
let currentStep    = 0;
const lastTaskStep = {};
let popover        = null;

function highlightStepTarget(global, $target, step, popoverOptions)
{
    if(popover) popover.destroy();
    popoverOptions = $.extend(
    {
        title           : step.title,
        show            : true,
        mask            : false,
        trigger         : 'manual',
        destroyOnHide   : true,
        closeBtn        : false,
        content         : step.desc,
        contentClass    : 'px-4',
        className       : 'tutorial-popover rounded-md',
        titleClass      : 'popover-title text-lg pl-1',
        minWidth        : 280,
        elementShowClass: 'with-popover-show tutorial-hl',
        actions:
        {
            className: 'py-3 px-4 justify-between mt-2',
            items:
            [
                {component: 'span', html: `${step.index + 1}/${step.task.steps.length}`},
                {type: 'primary', text: lang.nextStep},
            ]
        }
    }, step.popover, popoverOptions);
    popover = new global.zui.Popover($target, popoverOptions);
    console.log('> highlightStepTarget', $target, {popover, step});
}

function showOpenAppStep(step)
{
    const indexWindow  = $('#iframePage')[0].contentWindow;
    const index$       = indexWindow.$;
    const $menuMainNav = index$('#menuMainNav');
    let   $appNav      = $menuMainNav.children(`li[data-app="${step.app}"]`);
    console.log('> showOpenAppStep', step, $menuMainNav, $appNav);
    if($appNav.length) return highlightStepTarget(indexWindow, $appNav.find('a'), step, {placement: 'right'});
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

    step.guide  = guide;
    step.task   = task;
    step.index  = stepIndex;
    step.isLast = stepIndex === task.steps.length - 1;

    if(step.type === 'openApp') return showOpenAppStep(step);
    console.log('activeTaskStep', guideName, taskName, step);
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

    step = step || lastTaskStep[taskName] || 0;
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
