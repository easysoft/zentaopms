<?php
/**
 * The browse view file of tree module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: browse.html.php 4796 2013-06-06 02:21:59Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>

<?php if($viewType != 'story'):?>
<style>
li.tree-item-story > .tree-actions .tree-action[data-type=sort] {display: none;}
li.tree-item-story > .tree-actions .tree-action[data-type=delete] {display: none;}
</style>
<?php endif;?>
<?php if($viewType == 'report'):?>
<style>
#modulesTree > li[data-owner=system] > .tree-actions > a[data-type=delete] {display: none;}
#modulesTree > li > ul > li a[data-type=subModules] {display: none;}
</style>
<?php endif;?>
<?php js::set('viewType', $viewType);?>
<?php js::set('rootID', $rootID);?>
<?php js::set('noSubmodule', $lang->tree->noSubmodule);?>
<script>
if(viewType == 'report') $('#subNavbar a').not('[href*=report][href*=browsereport]').closest('li').removeClass('active');
if(viewType == 'dashboard') $('#subNavbar a').not('[href*=dashboard][href*=browse]').closest('li').removeClass('active');
</script>
<?php $this->app->loadLang('doc');?>
<?php $hasBranch = (strpos('story|bug|case', $viewType) !== false and (!empty($root->type) && $root->type != 'normal')) ? true : false;?>
<?php
$name = $lang->tree->name;
if($viewType == 'line')   $name = $lang->tree->line;
if($viewType == 'api')    $name = $lang->tree->dir;
if($viewType == 'doc')    $name = $lang->doc->catalogName;
if($viewType == 'report') $name = $lang->tree->reportGroup;
if($viewType == 'trainskill' or $viewType == 'trainpost') $name = $lang->tree->cate;

$childTitle = $lang->tree->child;
if(strpos($viewType, 'doc') !== false or $viewType == 'api') $childTitle = $lang->doc->childType;
if($viewType == 'line' or $viewType == 'trainskill' or $viewType == 'trainpost') $childTitle = '';
if($viewType == 'host') $childTitle = $lang->tree->childGroup;

$editTitle   = $lang->tree->edit;
$deleteTitle = $lang->tree->delete;
if($viewType == 'doc' or $viewType == 'api')
{
    $editTitle   = $lang->doc->editType;
    $deleteTitle = $lang->doc->deleteType;
}
?>
<!--div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php $backLink = $this->session->{$viewType . 'List'} ? $this->session->{$viewType . 'List'} : 'javascript:history.go(-1)';?>
    <?php echo html::a($backLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', 'class="btn btn-secondary"');?>
    <div class="divider"></div>
    <div class="page-title">
      <?php $rootName = $viewType == 'line' or $viewType == 'trainskill' or $viewType == 'trainpost' ? '' : $root->name;?>
      <span class="text" title='<?php echo $rootName;?>'>
        <?php
        if($viewType == 'doc')
        {
            echo $lang->doc->manageType . $lang->colon . $root->name;
        }
        elseif($viewType == 'api')
        {
            echo $lang->api->manageType . $lang->colon . $root->name;
        }
        elseif($viewType == 'line')
        {
            echo $lang->tree->manageLine;
        }
        elseif($viewType == 'trainskill')
        {
            echo $lang->tree->manageTrainskill;
        }
        elseif($viewType == 'trainpost')
        {
            echo $lang->tree->manageTrainpost;
        }
        else
        {
            echo $lang->tree->common . $lang->colon . $root->name;
        }
        ?>
      </span>
    </div>
  </div>
</div-->
<div id="mainContent" class="main-row">
  <div class="side-col col-4">
    <div class="panel">
      <div class="panel-heading">
        <div class="panel-title"><?php echo $childTitle;?></div>
        <?php if($app->tab == 'product' and $viewType == 'story'):?>
        <div class="panel-actions btn-toolbar">
          <?php echo html::a($this->createLink('tree', 'viewHistory', "productID=$rootID", '', true), $lang->history,  '', "class='btn btn-sm btn-primary iframe'");?>
        </div>
        <?php endif;?>
      </div>
      <div class="panel-body">
        <ul id='modulesTree' data-name='tree-<?php echo $viewType;?>'></ul>
      </div>
    </div>
  </div>
  <div class="main-col col-8">
    <div class="panel">
      <div class="panel-heading">
        <div class="panel-title">
          <?php $manageChild = 'manage' . ucfirst($viewType) . 'Child';?>
          <?php if(strpos($viewType, 'trainskill') === false and strpos($viewType, 'trainpost') === false) echo strpos($viewType, 'doc') !== false ? $lang->doc->manageType : $lang->tree->$manageChild;?>
        </div>
        <?php $parent = isonlybody() ? 'onlybody' : '';?>
        <?php if($viewType == 'story' and $allProduct and $canBeChanged):?>
        <div class="panel-actions btn-toolbar"><?php echo html::a('javascript:toggleCopy()', $lang->tree->syncFromProduct, '', "class='btn btn-sm btn-primary'")?></div>
        <?php elseif($viewType == 'feedback' and common::hasPriv('feedback', 'syncProduct') and !isset($syncConfig[$rootID])):?>
        <div class="panel-actions btn-toolbar"><?php echo html::a($this->createLink('feedback', 'syncProduct', "productID=$rootID&module=feedback&parent=$parent", '', true), $lang->tree->syncProductModule, '', "class='btn btn-sm btn-primary iframe' data-width='60%'");?></div>
        <?php elseif($viewType == 'ticket' and common::hasPriv('ticket', 'syncProduct') and !isset($syncConfig[$rootID])):?>
        <div class="panel-actions btn-toolbar"><?php echo html::a($this->createLink('ticket', 'syncProduct', "productID=$rootID&parent=$parent", '', true), $lang->tree->syncProductModule, '', "class='btn btn-sm btn-primary iframe' data-width='60%'");?></div>
        <?php endif;?>
      </div>
      <div class="panel-body">
        <form id='childrenForm' method='post' target='hiddenwin' action='<?php echo $this->createLink('tree', 'manageChild', "root=$rootID&viewType=$viewType");?>'>
          <table class='table table-form table-auto'>
            <tr>
              <?php if($viewType != 'line' && $viewType != 'trainskill' && $viewType != 'trainpost' && $viewType != 'host'):?>
              <td class="text-middle text-right with-padding">
                <?php
                echo "<span>" . html::a($this->createLink('tree', 'browse', "root=$rootID&viewType=$viewType&currentModuleID=0&branch=0&from=$from", '', ''), empty($root->name) ? '' : $root->name, '', "data-app='{$this->app->tab}'") . "<i class='icon icon-angle-right muted'></i></span>";
                foreach($parentModules as $module)
                {
                    echo "<span>" . html::a($this->createLink('tree', 'browse', "root=$rootID&viewType=$viewType&currentModuleID=$module->id&branch=0&from=$from"), $module->name, '', "data-app='{$this->app->tab}'") . " <i class='icon icon-angle-right muted'></i></span>";
                }
                ?>
              </td>
              <?php endif;?>
              <td>
                <div id='sonModule'>
                  <?php if($viewType == 'story' and $allProduct):?>
                  <div class='table-row row-module copy' style='display: none;'>
                    <div class='table-col col-module'><?php echo html::select('allProduct', $allProduct, '', "class='form-control chosen' onchange=\"syncProductOrProject(this,'product')\"");?></div>
                    <div class='table-col col-shorts'><?php echo html::select('productModule', $productModules, '', "class='form-control chosen'");?></div>
                    <div class='table-col col-actions'>
                      <?php echo html::commonButton('', "id='copyModule' onclick='syncModule($currentProduct, \"story\")'", 'btn btn-link btn-icon', 'icon icon-copy');?>
                    </div>
                  </div>
                  <?php endif;?>

                  <?php $maxOrder = 0;?>
                  <?php foreach($sons as $sonModule):?>
                  <?php if($sonModule->order > $maxOrder) $maxOrder = $sonModule->order;?>
                  <?php $disabled = $sonModule->type == $viewType ? '' : 'disabled';?>
                  <div class="table-row row-module">
                    <div class="table-col col-module"><?php echo html::input("modules[id$sonModule->id]", $sonModule->name, 'class="form-control"' . $disabled);?></div>
                    <?php if($hasBranch):?>
                    <div class="table-col col-module"><?php echo html::select("branch[id$sonModule->id]", $branches, $sonModule->branch, 'class="form-control" disabled');?></div>
                    <?php endif;?>
                    <div class="table-col col-shorts"><?php echo html::input("shorts[id$sonModule->id]", $sonModule->short, "class='form-control' placeholder='{$lang->tree->short}' $disabled") . html::hidden("order[id$sonModule->id]", $sonModule->order);?></div>
                    <div class="table-col col-actions"> </div>
                  </div>
                  <?php endforeach;?>
                  <?php for($i = 0; $i < TREE::NEW_CHILD_COUNT ; $i ++):?>
                  <div class="table-row row-module row-module-new">
                    <div class="table-col col-module"><?php echo html::input("modules[]", '', "class='form-control' placeholder='{$name}'");?></div>
                    <?php if($hasBranch):?>
                    <?php $disabledBranch = $branch === 'all' ? '' : 'disabled';?>
                    <div class="table-col col-module"><?php echo html::select("branch[]", $branches, $branch, "class='form-control' $disabledBranch");?></div>
                    <?php if($branch !== 'all') echo html::hidden("branch[]", $branch);?>
                    <?php endif;?>
                    <div class="table-col col-shorts"><?php echo html::input("shorts[]", '', "class='form-control' placeholder='{$lang->tree->short}'");?></div>
                    <div class="table-col col-actions">
                      <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
                      <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItem(this)"><i class="icon icon-close"></i></button>
                    </div>
                  </div>
                  <?php endfor;?>
                </div>

                <div id="insertItemBox" class="template">
                  <div class="table-row row-module row-module-new">
                    <div class="table-col col-module"><?php echo html::input("modules[]", '', "class='form-control' placeholder='{$name}'");?></div>
                    <?php if($hasBranch):?>
                    <div class="table-col col-module"><?php echo html::select("branch[]", $branches, $branch, 'class="form-control"');?></div>
                    <?php endif;?>
                    <div class="table-col col-shorts"><?php echo html::input("shorts[]", '', "class='form-control' placeholder='{$lang->tree->short}'");?></div>
                    <div class="table-col col-actions">
                      <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
                      <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItem(this)"><i class="icon icon-close"></i></button>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <?php if($viewType != 'line' && $viewType != 'trainskill' && $viewType != 'trainpost' && $viewType != 'host'):?>
              <td></td>
              <?php endif;?>
              <td colspan="2" class="form-actions">
                <?php if($canBeChanged) echo html::submitButton();?>
                <?php if(!isonlybody()) echo html::backButton();?>
                <?php echo html::hidden('parentModuleID', $currentModuleID);?>
                <?php echo html::hidden('maxOrder', $maxOrder);?>
              </td>
            </tr>
            </tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php js::set('tab', $this->app->tab);?>
<?php js::set('branch', $branch);?>
<script>
$(function()
{
    var data = $.parseJSON('<?php echo helper::jsonEncode4Parse($tree);?>');
    var orderModule = 0;
    var options =
    {
        initialState: 'preserve',
        data: data,
        sortable:
        {
            lazy: true,
            nested: true,
            canMoveHere: function($ele, $target)
            {
                if($ele && $target && $ele.parent().closest('li').attr('data-id') !== $target.parent().closest('li').attr('data-id')) return false;
            },
            start: function(e)
            {
                orderModule = e.element.data('id');
            }
        },
        itemCreator: function($li, item)
        {
            var link = (item.id !== undefined && item.type != 'line') ? ('<a href="' + createLink('tree', 'browse', 'rootID=<?php echo $rootID ?>&viewType=<?php echo $viewType ?>&moduleID={0}&branch={1}&from=<?php echo $from;?>&projectID=<?php echo isset($projectID) ? $projectID : 0; ?>'.format(item.id, branch)) + '" data-app="' + tab + '" title="' + item.name + '">' + item.name + '</a>') : ('<span class="tree-toggle">' + item.name + '</span>');
            var $toggle = $('<span class="module-name" data-id="' + item.id + '">' + link + '</span>');
            if(item.type === 'bug') $toggle.append('&nbsp; <span class="text-muted">[B]</span>');
            if(item.type === 'case') $toggle.append('&nbsp; <span class="text-muted">[C]</span>');
            if(item.type === 'feedback') $toggle.append('&nbsp; <span class="text-muted">[F]</span>');
            if(item.type === 'ticket') $toggle.append('&nbsp; <span class="text-muted">[T]</span>');
            $li.append($toggle);
            if(item.nodeType || item.type) $li.addClass('tree-item-' + (item.nodeType || item.type));
            $li.toggleClass('active', <?php echo $currentModuleID ?> === item.id);
            $li.attr('data-owner', item.owner);
            return true;
        },
        actions:
        {
            sort:
            {
                title: '<?php echo $lang->tree->dragAndSort ?>',
                template: '<a class="sort-handler"><i class="icon-move"></i></a>'
            },
            edit:
            {
                linkTemplate: '<?php echo helper::createLink('tree', 'edit', "moduleID={0}&type=$viewType"); ?>',
                title: '<?php echo $editTitle;?>',
                template: '<a><i class="icon-edit"></i></a>'
            },
            "delete":
            {
                linkTemplate: '<?php echo helper::createLink('tree', 'delete', "rootID=$rootID&moduleID={0}"); ?>',
                title: '<?php echo $deleteTitle;?>',
                template: '<a><i class="icon-trash"></i></a>'
            },
            subModules:
            {
                linkTemplate: '<?php echo helper::createLink('tree', 'browse', "rootID=$rootID&viewType=$viewType&moduleID={0}&branch={1}"); ?>',
                title: '<?php echo $childTitle;?>',
                template: '<a><?php echo $viewType == 'line' ? '' : '<i class="icon-split"></i>';?></a>',
            }
        },
        action: function(event)
        {
            var action = event.action, $target = $(event.target), item = event.item;
            if(action.type === 'edit')
            {
                new $.zui.ModalTrigger({
                    type: 'ajax',
                    url: action.linkTemplate.format(item.id),
                    keyboard: true
                }).show();
            }
            else if(action.type === 'delete')
            {
                hiddenwin.location.href = action.linkTemplate.format(item.id);
            }
            else if(action.type === 'sort' && event.item == null)
            {
                var orders = {};
                $('#modulesTree').find('li:not(.tree-action-item)').each(function()
                {
                    var $li = $(this);
                    if($li.hasClass('tree-item-branch')) return;

                    var item = $li.data();
                    orders['orders[' + item.id + ']'] = $li.attr('data-order') || item.order;
                });

                $.post(createLink('tree', 'updateOrder', 'rootID=' + rootID + '&viewType=' + viewType +'&moduleID=' + orderModule), orders, function(data)
                {
                    $('.main-col').load(location.href + ' .main-col .panel');
                }).error(function()
                {
                    bootbox.alert(lang.timeout);
                });
            }
            else if(action.type === 'subModules')
            {
                window.location.href = action.linkTemplate.format(item.id, item.branch);
            }
        }
    };

    if(<?php echo (common::hasPriv('tree', 'updateorder') and $canBeChanged) ? 'false' : 'true' ?>) options.actions["sort"] = false;
    if(<?php echo (common::hasPriv('tree', 'edit') and $canBeChanged) ? 'false' : 'true' ?>) options.actions["edit"] = false;
    if(<?php echo (common::hasPriv('tree', 'delete') and $canBeChanged) ? 'false' : 'true' ?>) options.actions["delete"] = false;
    if(<?php echo $canBeChanged ? 'false' : 'true' ?>) options.actions["subModules"] = false;

    var $tree = $('#modulesTree').tree(options);

    var tree = $tree.data('zui.tree');
    if(<?php echo $currentModuleID ?>)
    {
        var $currentLi = $tree.find('.module-name[data-id=' + <?php echo $currentModuleID ?> + ']').closest('li');
        if($currentLi.length) tree.show($currentLi);
    }

    $tree.on('mouseenter', 'li:not(.tree-action-item)', function(e)
    {
        $('#modulesTree').find('li.hover').removeClass('hover');
        $(this).addClass('hover');
        e.stopPropagation();
    });

    $('#subNavbar > ul > li > a[href*=tree][href*=browse]').not('[href*=<?php echo $viewType;?>]').parent().removeClass('active');
    if(window.config.viewType == 'line') $('#modulemenu > .nav > li > a[href*=product][href*=all]').parent('li[data-id=all]').addClass('active');
    if(viewType == 'case' || viewType == 'caselib') $('#subNavbar li[data-id="' + viewType +'"]').addClass('active');
    if(viewType == 'ticket')
    {
        $('#navbar li[data-id="browse"]').removeClass('active');
        $('#navbar li[data-id="' + viewType +'"]').addClass('active');
    }
    if(viewType == 'report')
    {
        $('#modulesTree > li[data-owner=system] > .tree-actions > a[data-type=delete]').remove();
        $('#modulesTree > li > ul > li a[data-type=subModules]').remove();
    }
});

if("<?php $from == 'doc'?>") parent.$('#triggerModal .modal-content .modal-header .close').on('click', function(){parent.location.reload();});
</script>
<style>
.module-name {display: inline-block; max-width: calc(100% - 85px); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;}
</style>
<?php
if(strpos($viewType, 'doc') !== false)
{
    include '../../doc/view/footer.html.php';
}
else
{
    include '../../common/view/footer.html.php';
}
?>
