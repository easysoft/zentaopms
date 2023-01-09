<?php
common::sortFeatureMenu();

js::set('status', $status);
js::set('orderBy', $orderBy);
js::set('edit', $lang->edit);
js::set('selectAll', $lang->selectAll);
js::set('hasProject', $hasProject);
js::set('checkedProjects', $lang->program->checkedProjects);
js::set('programSummary', $summary);
js::set('cilentLang', $this->app->getClientLang());
js::set('editLang', $this->lang->edit);
js::set('pagerLang', $this->lang->pager);
js::set('recTotal', $pager->recTotal);
js::set('recPerPage', $pager->recPerPage);
js::set('pageID', $pager->pageID);
js::set('pagerLink', $this->createLink('program', 'browse', "status=$status&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={recPerPage}&pageID={page}"));

/* Set toolbar. */
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

$searchClass = $status == 'bySearch' ? 'active' : '';
$toolbar->append("<a class='querybox-toggle $searchClass' id='searchFormBtn'><i class='icon icon-search'></i> <span>" . $lang->user->search . '</span></a>');

/* Set actionbar. */
$actionbar = actionbar();
if(common::hasPriv('project', 'create'))
{
    $button = button('<i class="icon icon-plus"></i> ' . $this->lang->project->create)->link($this->createLink('project', 'createGuide', "programID=0&from=PGM"))->addClass('btn secondary')->misc('data-toggle="modal" data-target="#guideDialog"');
    $actionbar->append($button);
}

if(common::hasPriv('program', 'create'))
{
    $button = button("<i class='icon icon-plus'></i> " . $this->lang->program->create)->link($this->createLink('program', 'create'))->addClass('btn primary');
    $actionbar->append($button);
}

$menu = block('h');
$menu->toolbar   = $toolbar;
$menu->actionbar = $actionbar;

/* Table. */
$table = dtable();
$table->buildCols($this->config->program->dtable->fieldList);

$rows = array();
$this->loadModel('project');
foreach($programs as $program)
{
    $rows[] = $this->program->buildRowData($program, $PMList, $progressList);
}

$table->search($status, $moduleName);
$sortLink = $this->createLink('program', 'browse', "status=$status&orderBy=\${orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&param=$param");
$table->setSort($sortLink, $orderBy);
$table->data($rows);

$content = block();
$content->table = $table;

/* Layout. */
$page = page('list');
$page->top->menu      = $menu;
$page->right->content = $content;
$page->x('list');
