<?php
namespace zin;
global $lang;

$fields = defineFieldList('bug');

$fields->field('product')
    ->required()
    ->items(data('products'))
    ->value(data('bug.productID'));

$fields->field('branch')
    ->items(data('branchs'))
    ->value(data('bug.branch'));

$fields->field('module')
    ->controlBegin('module')
    ->type('modulePicker')
    ->items(data('moduleOptionMenu'))
    ->value(data('bug.moduleID'))
    ->manageLink(createLink('tree', 'browse', 'rootID=' . data('bug.productID') . '&view=bug&currentModuleID=0&branch=' . data('bug.branch')))
    ->controlEnd();

$fields->field('openedBuild')
    ->control('inputGroup')
    ->itemBegin('openedBuild[]')->type('picker')->items(data('builds'))->multiple()->itemEnd()
    ->itemBegin()->type('addon')->id('buildBoxActions')->className('btn-group hidden')->itemEnd()
    ->itemBegin()->type('btn')->icon('refresh text-primary')->hint($lang->bug->loadAll)->id('allBuilds')->itemEnd();

$fields->field('assignedTo')
    ->control('inputGroup')
    ->itemBegin('assignedTo')->type('picker')->items(data('productMembers'))->value(data('bug.assignedTo'))->itemEnd()
    ->itemBegin()->type('btn')->icon('refresh text-primary')->hint($lang->bug->loadAll)->id('allUsers')->itemEnd();

$fields->field('deadline')
    ->control('datePicker');

$fields->field('title')
    ->width('full');

$fields->field('color')
    ->control('color');

$fields->field('type')
    ->width('1/6')
    ->items($lang->bug->typeList);

$fields->field('severity')
    ->width('1/6')
    ->control('severityPicker')
    ->items($lang->bug->severityList);

$fields->field('pri')
    ->width('1/6')
    ->control('priPicker')
    ->items($lang->bug->priList);

$fields->field('steps')
    ->width('full')
    ->control('editor');

$fields->field('files')
    ->width('full')
    ->control('files');

$fields->field('project')
    ->foldable()
    ->items(data('projects'))
    ->value(data('projectID'));

$fields->field('execution')
    ->id('executionBox')
    ->foldable()
    ->label(data('bug.projectModel') === 'kanban' ? $lang->bug->kanban : $lang->bug->execution)
    ->items(data('executions'))
    ->value(data('executionID'));

$fields->field('story')
    ->foldable()
    ->items(empty(data('bug.stories')) ? array() : data('bug.stories'))
    ->value(data('bug.storyID'));

$fields->field('task')
    ->foldable()
    ->items(array())
    ->value(data('bug.taskID'));

$fields->field('feedbackBy')
    ->foldable();

$fields->field('notifyEmail')
    ->foldable();

$fields->field('os')
    ->foldable()
    ->control('picker')
    ->items($lang->bug->osList)
    ->multiple();

$fields->field('browser')
    ->foldable()
    ->control('picker')
    ->items($lang->bug->browserList)
    ->multiple();

$fields->field('mailto')
    ->foldable()
    ->control('mailto')
    ->value(data('bug.mailto'));

$fields->field('keywords');

$fields->field('case')->control('hidden')->value(data('bug.caseID'));
$fields->field('caseVersion')->control('hidden')->value(data('bug.version'));
$fields->field('result')->control('hidden')->value(data('bug.runID'));
$fields->field('testtask')->control('hidden')->value(data('bug.testtask'));

$fields = defineFieldList('bug.kanban');
$fields->field('region')
    ->label($lang->kanbancard->region)
    ->items(data('regionPairs'))
    ->value(data('regionID'));
$fields->field('lane')
    ->label($lang->kanbancard->lane)
    ->items(data('lanePairs'))
    ->value(data('laneID'));
