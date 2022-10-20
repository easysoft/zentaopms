<?php
common::sortFeatureMenu();

/* Toolbar. */
$toolbar = toolbar();
foreach($this->lang->program->featureBar['browse'] as $key => $label)
{
    $tab = tab($label)->link(inlink('browse', "status=$key&orderBy=$orderBy"))->active($key == $status);
    $toolbar->append($tab);
}

if(common::hasPriv('project', 'batchEdit') and $programType != 'bygrid' and $hasProject === true)
{
    $toolbar->append(html::checkbox('editProject', array('1' => $lang->project->edit), '', $this->cookie->editProject ? 'checked=checked' : ''));
}
$toolbar->append('<a class="btn btn-link querybox-toggle" id="bysearchTab"><i class="icon icon-search muted"></i> ' . $lang->user->search . '</a>');

$actionbar = actionbar();
if(common::hasPriv('project', 'create'))
{
    $button = button('<i class="icon icon-plus"></i> ' . $this->lang->project->create)->link($this->createLink('project', 'createGuide', "programID=0&from=PGM"))->misc('class="btn btn-secondary" data-toggle="modal" data-target="#guideDialog"');
    $actionbar->append($button);
}
if(isset($lang->pageActions))
{
    $actionbar->append($lang->pageActions);
}

$menu = block('h');
$menu->toolbar   = $toolbar;
$menu->actionbar = $actionbar;

/* Table. */
$table = dtable();
$table->col('name', $this->lang->nameAB)->flex(1);
$table->col('status', $this->lang->program->status)->width(60)->type('html');
$table->col('pm', $this->lang->program->PM)->width(100)->type('html');
$table->col('budget', $this->lang->project->budget)->width(60);
$table->col('begin', $this->lang->project->begin)->width(60);
$table->col('end', $this->lang->project->end)->width(60);
$table->col('progress', $this->lang->project->progress)->width(100)->type('html');
$table->col('actions', $this->lang->actions)->width(60);

$rows = array();
$this->loadModel('project');
foreach($programs as $program)
{
    $row = new stdclass();
    $row->name   = $program->name;
    $row->status = '<span class="status-program status-<?php echo $program->status?>">' . zget($lang->project->statusList, $program->status, '') . '</span>';
    $row->parent = $program->parent;

    $row->pm = '';
    if(!empty($program->PM))
    {
        $userName = zget($users, $program->PM);
        $row->pm .= html::smallAvatar(array('avatar' => $usersAvatar[$program->PM], 'account' => $program->PM, 'name' => $userName), (($program->type == 'program' and $program->grade == 1 )? 'avatar-circle avatar-top avatar-' : 'avatar-circle avatar-') . zget($userIdPairs, $program->PM));
        $userID   = isset($PMList[$program->PM]) ? $PMList[$program->PM]->id : '';
        $row->pm .= html::a($this->createLink('user', 'profile', "userID=$userID", '', true), $userName, '', "title='{$userName}' data-toggle='modal' data-type='iframe' data-width='600'");
    }

    $programBudget = $this->project->getBudgetWithUnit($program->budget);
    $row->budget   = $program->budget != 0 ? zget($lang->project->currencySymbol, $program->budgetUnit) . ' ' . $programBudget : $this->lang->project->future;
    $row->begin    = $program->begin;
    $row->end      = $program->end == LONG_TIME ? $lang->program->longTime : $program->end;

    $row->progress = '';
    if(isset($progressList[$program->id]))
    {
        $row->progress = "<div class='progress-pie' data-doughnut-size='85' data-color='#00DA88' data-value='" . round($progressList[$program->id]) . "' data-width='26' data-height='26' data-back-color='#e8edf3'>";
        $row->progress = "<div class='progress-info'>" . round($progressList[$program->id]) . "</div></div>";
    }

    $row->actions = array();

    $rows[] = $row;
}
$table->search($status, $moduleName);
$table->data($rows);

$content = block();
$content->table = $table;

/* Layout. */
$page = page('list');
$page->top->menu      = $menu;
$page->right->content = $content;

$page->x();
