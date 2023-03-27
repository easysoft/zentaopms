<?php
namespace zin;

$cols = array_values($config->program->productView->dtable->fieldList);

$data = array();

$i    = 100;
$item = new stdClass();

$item->id                = "$i";
$item->name              = 'parent';
$item->type              = 'program';
$item->asParent          = true;
$item->PM                = '管理员';
$item->feedback          = 200;
$item->unclosedReqCount  = 100;
$item->closedReqRate     = intval(800 / $i);
$item->planCount         = $i;
$item->executionCount    = $i;
$item->testCaseCoverRate = intval(800 / $i);
$item->bugActivedCount   = $i;
$item->fixedRate         = intval(800 / $i);
$item->releaseCount      = $i;
$item->releaseCountBefore= 102;

$data[] = $item;

$i    = 101;
$item = new stdClass();

$item->id                = "$i";
$item->name              = 'ProductLine';
$item->type              = 'productLine';
$item->asParent          = true;
$item->parent            = "100";
$item->PM                = '管理员';
$item->feedback          = 200;
$item->unclosedReqCount  = 100;
$item->closedReqRate     = intval(800 / $i);
$item->planCount         = $i;
$item->executionCount    = $i;
$item->testCaseCoverRate = intval(800 / $i);
$item->bugActivedCount   = $i;
$item->fixedRate         = intval(800 / $i);
$item->releaseCount      = $i;
$item->releaseCountBefore= 102;

$data[] = $item;

$i    = 102;
$item = new stdClass();

$item->id                = "$i";
$item->name              = 'Product';
$item->type              = 'product';
$item->asParent          = false;
$item->parent            = "101";
$item->PM                = '管理员';
$item->feedback          = 200;
$item->unclosedReqCount  = 100;
$item->closedReqRate     = intval(800 / $i);
$item->planCount         = $i;
$item->executionCount    = $i;
$item->testCaseCoverRate = intval(800 / $i);
$item->bugActivedCount   = $i;
$item->fixedRate         = intval(800 / $i);
$item->releaseCount      = $i;
$item->releaseCountBefore= 102;

$data[] = $item;

$i    = 103;
$item = new stdClass();

$item->id                = "$i";
$item->name              = 'IndProduct';
$item->type              = 'product';
$item->asParent          = false;
$item->parent            = "0";
$item->PM                = '管理员';
$item->feedback          = 200;
$item->unclosedReqCount  = 100;
$item->closedReqRate     = intval(800 / $i);
$item->planCount         = $i;
$item->executionCount    = $i;
$item->testCaseCoverRate = intval(800 / $i);
$item->bugActivedCount   = $i;
$item->fixedRate         = intval(800 / $i);
$item->releaseCount      = $i;
$item->releaseCountBefore= 102;

$data[] = $item;

$i    = 104;
$item = new stdClass();

$item->id                = "$i";
$item->name              = 'SubProduct';
$item->type              = 'product';
$item->asParent          = false;
$item->parent            = "100";
$item->PM                = '管理员';
$item->feedback          = 200;
$item->unclosedReqCount  = 100;
$item->closedReqRate     = intval(800 / $i);
$item->planCount         = $i;
$item->executionCount    = $i;
$item->testCaseCoverRate = intval(800 / $i);
$item->bugActivedCount   = $i;
$item->fixedRate         = intval(800 / $i);
$item->releaseCount      = $i;
$item->releaseCountBefore= 102;

$data[] = $item;

set::title('产品视角');

featureBar
(
    set::current($status),
    set::linkParams("status={key}&orderBy=$orderBy"),
    (hasPriv('project', 'batchEdit') && $programType != 'bygrid' && $hasProject === true) ? item
    (
        set::type('checkbox'),
        set::text($lang->project->edit),
        set::checked($this->cookie->editProject)
    ) : NULL,
    li(searchToggle())
);

toolbar
(
    item(set(array(
        'text' => $lang->program->export,
        'icon' => 'export',
        'class'=> 'ghost',
        'url'  => createLink('program', 'exportTable')
    ))),
    div(setClass('nav-divider')),
    item(set(array(
        'text' => $lang->program->edit,
        'icon' => 'edit',
        'class'=> 'ghost',
        'url'  => createLink('program', 'exportTable')
    ))),
    item(set(array(
        'text' => $lang->program->createProduct,
        'icon' => 'plus',
        'class'=> 'btn secondary',
        'url'  => createLink('program', 'exportTable')
    ))),
    item(set(array(
        'text' => $lang->program->create,
        'icon' => 'plus',
        'class'=> 'btn primary',
        'url'  => createLink('program', 'exportTable')
    ))),
);

js
(
    'window.footerGenerator = function()',
    '{',
        'const count = this.layout.allRows.filter((x) => x.data.type === "product").length;',
        "const statistic = '{$summary}'.replace('%s', ' ' + count + ' ');",
        'return [{children: statistic, className: "text-dark"}, "flex", "pager"];',
    '}'
);

/* Render release count. */
js
(
<<<RENDERCELL
window.renderReleaseCountCell = function(result, {col, row})
{
    if(col.name !== 'releaseCount') return result;

    var changed = row.data.releaseCount - row.data.releaseCountBefore;

    if(changed === 0) return result;
    if(changed > 0) result[0] = {html: row.data.releaseCount + ' <span class="label size-sm circle primary-pale bd-primary">+' + changed + '</span>'};
    if(changed < 0) result[0] = {html: row.data.releaseCount + ' <span class="label size-sm circle warning-pale bd-warning">' + changed + '</span>'};

    return result;
}
RENDERCELL
);

dtable
(
    set::className('shadow rounded'),
    set::cols($cols),
    set::data($data),
    set::footPager(usePager()),
    set::nested(true),
    set::onRenderCell(jsRaw('window.renderReleaseCountCell')),
    set::footer(jsRaw('function(){return window.footerGenerator.call(this);}'))
);

render();
