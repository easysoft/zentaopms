<?php
common::sortFeatureMenu();

js::set('edit', $lang->edit);
js::set('browseType', $browseType);
js::set('orderBy', $orderBy);
js::set('selectAll', $lang->selectAll);
js::set('checkedProjects', $lang->program->checkedProjects);
js::set('cilentLang', $this->app->getClientLang());
js::set('editLang', $this->lang->edit);
js::set('pagerLang', $this->lang->pager);
js::set('recTotal', $pager->recTotal);
js::set('recPerPage', $pager->recPerPage);
js::set('pageID', $pager->pageID);
js::set('productSummary', '');
js::set('pagerLink', $this->createLink('product', 'all', "browseType=$browseType&orderBy=$orderBy&param=0&recTotal={$pager->recTotal}&recPerPage={recPerPage}&pageID={page}"));

/* Set toolbar. */
$toolbar = toolbar();
foreach($this->lang->product->featureBar['all'] as $key => $label)
{
    if($key == $browseType) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
    $tab = tab($label)->link(inlink('all', "browseType=$key&orderBy=$orderBy"))->active($key == $browseType);
    $toolbar->append($tab);
}

if(common::hasPriv('project', 'batchEdit')) $toolbar->append(html::checkbox('showEdit', array('1' => $lang->product->edit), $showBatchEdit));

$searchClass = $browseType == 'bySearch' ? 'active' : '';
$toolbar->append("<a class='querybox-toggle $searchClass' id='searchFormBtn'><i class='icon icon-search'></i> <span>" . $lang->user->search . '</span></a>');

/* Set actionbar. */
$actionbar = actionbar();
if(common::hasPriv('product', 'export'))
{
    $button = button("<i class='icon-export muted'> </i>" . $lang->export)->link($this->createLink('product', 'export', "status=$browseType&orderBy=$orderBy", 'html', true))->addClass('btn btn-link export');
    $actionbar->append($button);
}

if($config->systemMode == 'ALM' and common::hasPriv('product', 'manageLine'))
{
    $button = button("<i class='icon-edit'> </i>" . $lang->product->line)->link($this->createLink('product', 'manageLine', '', 'html', true))->addClass('btn btn-link iframe');
    $actionbar->append($button);
}

if(common::hasPriv('product', 'create'))
{
    $button = button("<i class='icon icon-plus'> </i>" . $lang->product->create)->link($this->createLink('product', 'create'))->addClass('btn primary create-product-btn');
    $actionbar->append($button);
}

$menu = block('h');
$menu->toolbar   = $toolbar;
$menu->actionbar = $actionbar;

/* Table. */
$table = dtable();
$table->buildCols($this->config->product->all->dtable->fieldList);

$rows = $this->product->buildRows($productStructure, array('programLines' => $programLines, 'users' => $users, 'usersAvatar' => $usersAvatar, 'userIdPairs' => $userIdPairs));

$table->search($browseType, $moduleName);
$sortLink = $this->createLink('product', 'all', "browseType=$browseType&orderBy=\${orderBy}&param=0&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");
$table->setSort($sortLink, $orderBy);
$table->data($rows);

$pagerLink = $this->createLink('product', 'all', "browseType=$browseType&orderBy=$orderBy&param=0&recTotal={$pager->recTotal}&recPerPage={recPerPage}&pageID={page}");
$table->appendFootToolBar(array('size' => 'sm', 'text' => $lang->edit, 'btnType' => 'primary', 'className' => 'edit-btn'));
$table->setPager($pager, $pagerLink);

$content = block();
$content->table = $table;

/* Layout. */
$page = page('list');
$page->top->menu      = $menu;
$page->right->content = $content;
$page->x();
