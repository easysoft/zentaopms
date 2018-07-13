<?php
/**
 * The browse view file of tree module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: browse.html.php 4796 2013-06-06 02:21:59Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('viewType', $viewType);?>
<?php $hasBranch = (strpos('story|bug|case', $viewType) !== false and (!empty($root->type) && $root->type != 'normal')) ? true : false;?>
<?php $name = $viewType == 'line' ? $lang->tree->line : ($viewType == 'doc' ? $lang->tree->cate : $lang->tree->name);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php $backLink = $this->session->{$viewType . 'List'} ? $this->session->{$viewType . 'List'} : 'javascript:history.go(-1)';?>
    <a href="<?php echo $backLink;?>" class="btn btn-link">
      <i class="icon icon-back icon-sm"></i> <?php echo $lang->goback;?>
    </a>
    <div class="divider"></div>
    <div class="page-title">
      <?php $rootName = $viewType == 'line' ? '' : $root->name;?>
      <span class="text" title='<?php echo $rootName;?>'>
        <?php
        if($viewType == 'doc')
        {
            echo $lang->doc->manageType . $lang->colon . $root->name;
        }
        elseif($viewType == 'line')
        {
            echo $lang->tree->manageLine;
        }
        else
        {
            echo $lang->tree->common . $lang->colon . $root->name;
        }
        ?>
      </span>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="side-col col-4">
    <div class="panel">
      <div class="panel-heading">
        <div class="panel-title"><?php echo $title;?></div>
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
          <?php echo strpos($viewType, 'doc') !== false ? $lang->doc->manageType : $lang->tree->$manageChild;?>
        </div>
        <?php if($viewType == 'story' and $allProduct):?>
        <div class="panel-actions btn-toolbar"><?php echo html::a('javascript:toggleCopy()', $lang->tree->syncFromProduct, '', "class='btn btn-sm'")?></div>
        <?php endif;?>
      </div>
      <div class="panel-body">
        <form id='childrenForm' method='post' target='hiddenwin' action='<?php echo $this->createLink('tree', 'manageChild', "root=$rootID&viewType=$viewType");?>'>
          <table class='table table-form table-auto'>
            <tr>
              <?php if($viewType != 'line'):?>
              <td class="text-middle text-right with-padding">
                <?php
                echo "<span>" . html::a($this->createLink('tree', 'browse', "root=$rootID&viewType=$viewType"), empty($root->name) ? '' : $root->name) . "<i class='icon icon-angle-right muted'></i></span>";
                foreach($parentModules as $module)
                {
                    echo "<span>" . html::a($this->createLink('tree', 'browse', "root=$rootID&viewType=$viewType&moduleID=$module->id"), $module->name) . " <i class='icon icon-angle-right muted'></i></span>";
                }
                ?>
              </td>
              <?php endif;?>
              <td>
                <div id='sonModule'>
                  <?php if($viewType == 'story' and $allProduct):?>
                  <div class='table-row row-module copy'>
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
                    <div class="table-col col-module"><?php echo html::input("modules[id$sonModule->id]", $sonModule->name, 'class="form-control" autocomplete="off"' . $disabled);?></div>
                    <?php if($hasBranch):?>
                    <div class="table-col col-module"><?php echo html::select("branch[id$sonModule->id]", $branches, $sonModule->branch, 'class="form-control" disabled');?></div>
                    <?php endif;?>
                    <div class="table-col col-shorts"><?php echo html::input("shorts[id$sonModule->id]", $sonModule->short, "class='form-control' placeholder='{$lang->tree->short}' $disabled autocomplete='off'") . html::hidden("order[id$sonModule->id]", $sonModule->order);?></div>
                    <div class="table-col col-actions">
                      <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
                    </div>
                  </div>
                  <?php endforeach;?>
                  <?php for($i = 0; $i < TREE::NEW_CHILD_COUNT ; $i ++):?>
                  <div class="table-row row-module row-module-new">
                    <div class="table-col col-module"><?php echo html::input("modules[]", '', "class='form-control' placeholder='{$name}' autocomplete='off'");?></div>
                    <?php if($hasBranch):?>
                    <div class="table-col col-module"><?php echo html::select("branch[]", $branches, $branch, 'class="form-control"');?></div>
                    <?php endif;?>
                    <div class="table-col col-shorts"><?php echo html::input("shorts[]", '', "class='form-control' placeholder='{$lang->tree->short}' autocomplete='off'");?></div>
                    <div class="table-col col-actions">
                      <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
                      <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItem(this)"><i class="icon icon-trash"></i></button>
                    </div>
                  </div>
                  <?php endfor;?>
                </div>

                <div id="insertItemBox" class="template">
                  <div class="table-row row-module row-module-new">
                    <div class="table-col col-module"><?php echo html::input("modules[]", '', "class='form-control' placeholder='{$name}' autocomplete='off'");?></div>
                    <?php if($hasBranch):?>
                    <div class="table-col col-module"><?php echo html::select("branch[]", $branches, $branch, 'class="form-control"');?></div>
                    <?php endif;?>
                    <div class="table-col col-shorts"><?php echo html::input("shorts[]", '', "class='form-control' placeholder='{$lang->tree->short}' autocomplete='off'");?></div>
                    <div class="table-col col-actions">
                      <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
                      <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItem(this)"><i class="icon icon-trash"></i></button>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <?php if($viewType != 'line'):?>
              <td></td>
              <?php endif;?>
              <td colspan="2" class="form-actions">
                <?php echo html::submitButton('', '', 'btn btn-primary btn-wide');?>
                <?php echo $this->session->{$viewType . 'List'} ? html::linkButton($this->lang->goback, $this->session->{$viewType .'List'}, 'self', '', 'btn btn-wide') : html::backButton('', '', 'btn btn-wide');?>
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

<script>
$(function()
{
    var data = $.parseJSON('<?php echo helper::jsonEncode4Parse($tree);?>');
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
            }
        },
        itemCreator: function($li, item)
        {
            var link = (item.id !== undefined && item.type != 'line') ? ('<a href="' + createLink('tree', 'browse', 'rootID=<?php echo $rootID ?>&viewType=<?php echo $viewType ?>&moduleID={0}&branch={1}'.format(item.id, item.branch)) + '">' + item.name + '</a>') : ('<span class="tree-toggle">' + item.name + '</span>');
            var $toggle = $('<span class="module-name" data-id="' + item.id + '">' + link + '</span>');
            if(item.type === 'bug') $toggle.append('&nbsp; <span class="text-muted">[B]</span>');
            if(item.type === 'case') $toggle.append('&nbsp; <span class="text-muted">[C]</span>');
            $li.append($toggle);
            if(item.nodeType || item.type) $li.addClass('tree-item-' + (item.nodeType || item.type));
            $li.toggleClass('active', <?php echo $currentModuleID ?> === item.id);
            return true;
        },
        actions:
        {
            sort:
            {
                title: '<?php echo $lang->tree->dragAndSort ?>',
                template: '<a class="sort-handler"><?php echo $lang->tree->sort ?></a>'
            },
            edit:
            {
                linkTemplate: '<?php echo helper::createLink('tree', 'edit', "moduleID={0}&type=$viewType"); ?>',
                title: '<?php echo $lang->tree->edit ?>',
                template: '<a><?php echo $lang->edit?></a>'
            },
            "delete":
            {
                linkTemplate: '<?php echo helper::createLink('tree', 'delete', "rootID=$rootID&moduleID={0}"); ?>',
                title: '<?php echo $lang->tree->delete ?>',
                template: '<a><?php echo $lang->delete?></a>'
            },
            subModules:
            {
                linkTemplate: '<?php echo helper::createLink('tree', 'browse', "rootID=$rootID&viewType=$viewType&moduleID={0}&branch={1}"); ?>',
                title: '<?php echo $viewType == 'line' ? '': $lang->tree->child ?>',
                template: '<a><?php echo $viewType == 'line' ? '': (strpos($viewType, 'doc') !== false ? $lang->doc->childType : $lang->tree->child)?></a>',
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
                window.open(action.linkTemplate.format(item.id), 'hiddenwin');
            }
            else if(action.type === 'sort')
            {
                var orders = {};
                $('#modulesTree').find('li:not(.tree-action-item)').each(function()
                {
                    var $li = $(this);
                    var item = $li.data();
                    orders['orders[' + item.id + ']'] = $li.attr('data-order') || item.order;
                });
                $.post('<?php echo $this->createLink('tree', 'updateOrder', "rootID=$rootID&viewType=$viewType");?>', orders).error(function()
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

    if(<?php echo common::hasPriv('tree', 'updateorder') ? 'false' : 'true' ?>) options.actions["sort"] = false;
    if(<?php echo common::hasPriv('tree', 'edit') ? 'false' : 'true' ?>) options.actions["edit"] = false;
    if(<?php echo common::hasPriv('tree', 'delete') ? 'false' : 'true' ?>) options.actions["delete"] = false;

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
});
</script>
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
