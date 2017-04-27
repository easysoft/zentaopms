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
<?php $hasBranch = (strpos('story|bug|case', $viewType) !== false and $root->type != 'normal') ? true : false;?>
<div id='featurebar'>
  <div class='heading'><?php echo $lang->tree->common;?></div>
</div>
<div class='row'>
  <div class='col-sm-4'>
    <div class='panel'>
      <div class='panel-heading'><i class='icon-cog'></i> <strong><?php echo $title;?></strong></div>
      <div class='panel-body'>
        <div class='container'>
          <ul class='tree-lines' id='modulesTree'></ul>
        </div>
      </div>
    </div>
  </div>
  <div class='col-sm-8'>
    <form id='childrenForm' class='form-condensed' method='post' target='hiddenwin' action='<?php echo $this->createLink('tree', 'manageChild', "root={$root->id}&viewType=$viewType");?>'>
      <div class='panel'>
        <div class='panel-heading'>
          <i class='icon-sitemap'></i> 
          <?php $manageChild = 'manage' . ucfirst($viewType) . 'Child';?>
          <?php echo strpos($viewType, 'doc') !== false ? $lang->doc->manageType : $lang->tree->$manageChild;?>
          <?php if($viewType == 'story' and $allProduct):?>
          <div class='panel-actions pull-right'><?php echo html::a('javascript:toggleCopy()', $lang->tree->syncFromProduct, '', "class='btn btn-sm'")?></div>
          <?php endif;?>
        </div>
        <div class='panel-body'>
          <table class='table table-form'>
            <tr>
              <td class='parentModule'>
                <nobr>
                <?php
                echo html::a($this->createLink('tree', 'browse', "root={$root->id}&viewType=$viewType"), $root->name);
                echo $lang->arrow;
                foreach($parentModules as $module)
                {
                    echo html::a($this->createLink('tree', 'browse', "root={$root->id}&viewType=$viewType&moduleID=$module->id"), $module->name);
                    echo $lang->arrow;
                }
                ?>
                </nobr>
              </td>
              <td id='moduleBox'> 
                <?php
                if($viewType == 'story' and $allProduct)
                {
                    echo "<table class='copy w-p100'><tr>";
                    echo "<td class='w-260px'>" . html::select('allProduct', $allProduct, '', "class='form-control chosen' onchange=\"syncProductOrProject(this,'product')\"") . '</td>';
                    echo "<td class='w-200px'>" . html::select('productModule', $productModules, '', "class='form-control chosen'") . '</td>';
                    echo "<td class=''>" . html::commonButton($lang->tree->syncFromProduct, "id='copyModule' onclick='syncModule($currentProduct, \"story\")'") . '</td>';
                    echo '</tr></table>';
                }
                $maxOrder = 0;
                echo '<div id="sonModule">';
                foreach($sons as $sonModule)
                {
                    if($sonModule->order > $maxOrder) $maxOrder = $sonModule->order;
                    $disabled = $sonModule->type == $viewType ? '' : 'disabled';
                    echo "<div class='row-table' style='margin-bottom:5px'>";
                    echo "<div class='col-table'>" . html::input("modules[id$sonModule->id]", $sonModule->name, 'class="form-control" autocomplete="off"' . $disabled) . '</div>';
                    if($hasBranch) echo "<div class='col-table'>" . html::select("branch[id$sonModule->id]", $branches, $sonModule->branch, 'class="form-control" disabled') . '</div>';
                    echo "<div class='col-table' style='width:120px'><div class='input-group'>" . html::input("shorts[id$sonModule->id]", $sonModule->short, "class='form-control' placeholder='{$lang->tree->short}' $disabled autocomplete='off'") . html::hidden("order[id$sonModule->id]", $sonModule->order);
                    echo "<span class='input-group-btn' style='border-left:1px solid'><a href='javascript:;' onclick='insertItem(this)' class='btn btn-block'><i class='icon icon-plus'></i></a></span>";
                    echo '</div></div>';
                    echo '</div>';
                }
                for($i = 0; $i < TREE::NEW_CHILD_COUNT ; $i ++)
                {
                    echo "<div class='row-table addedItem' style='margin-bottom:5px'>";
                    echo "<div class='col-table'>" . html::input("modules[]", '', "class='form-control' placeholder='{$lang->tree->name}' autocomplete='off'") . '</div>';
                    if($hasBranch) echo '<div class="col-table">' . html::select("branch[]", $branches, $branch, 'class="form-control"') . '</div>';
                    echo "<div class='col-table' style='width:120px'><div class='input-group'>" . html::input("shorts[]", '', "class='form-control' placeholder='{$lang->tree->short}' autocomplete='off'");
                    echo "<span class='input-group-btn'><a href='javascript:;' onclick='addItem(this)' class='btn btn-block'><i class='icon icon-plus'></i></a></span>";
                    echo "<span class='input-group-btn'><a href='javascript:;' onclick='deleteItem(this)' class='btn btn-block'><i class='icon icon-remove'></i></a></span>";
                    echo '</div></div>';
                    echo '</div>';
                }

                echo "<div id='insertItemBox' class='hidden'>";
                echo "<div class='row-table' style='margin-bottom:5px'>";
                echo "<div class='col-table'>" . html::input("modules[]", '', "class='form-control' placeholder='{$lang->tree->name}' autocomplete='off'") . '</div>';
                if($hasBranch) echo '<div class="col-table">' . html::select("branch[]", $branches, $branch, 'class="form-control" disabled') . '</div>';
                echo "<div class='col-table' style='width:120px'><div class='input-group'>" . html::input("shorts[]", '', "class='form-control' placeholder='{$lang->tree->short}' autocomplete='off'") . html::hidden("order[]");
                echo "<span class='input-group-btn' style='border-left:1px solid'><a href='javascript:;' onclick='deleteItem(this)' class='btn btn-block'><i class='icon icon-remove'></i></a></span>";
                echo '</div></div>';
                echo '</div></div>';

                echo '</div>';
                ?>
              </td>
            </tr>
            <tr>
              <td></td>
              <td colspan='2'>
                <?php 
                echo html::submitButton();
                echo $this->session->{$viewType .'List'} ? html::linkButton($this->lang->goback, $this->session->{$viewType .'List'}) : html::backButton();
                echo html::hidden('parentModuleID', $currentModuleID);
                echo html::hidden('maxOrder', $maxOrder);
                ?>      
                <input type='hidden' value='<?php echo $currentModuleID;?>' name='parentModuleID' />
              </td>
            </tr>
          </table>
        </div>
      </div>
    </form>
  </div>
</div>
<?php if($viewType !== 'story'): ?>
<style>
<?php if($viewType != 'bug') echo ".tree-item-story > .tree-actions > .tree-action[data-type='edit'],";?>
.tree-item-story > .tree-actions > .tree-action[data-type='sort'],
.tree-item-story > .tree-actions > .tree-action[data-type='delete'] {display: none!important}
</style>
<?php endif;?>
<script>
$(function()
{
    var data = $.parseJSON('<?php echo helper::jsonEncode4Parse($tree);?>');
    var options = {
        name: 'tree-<?php echo $viewType ?>-edit',
        initialState: 'preserve',
        data: data,
        itemCreator: function($li, item)
        {
            var link = item.id !== undefined ? ('<a href="' + createLink('tree', 'browse', 'rootID=<?php echo $rootID ?>&viewType=<?php echo $viewType ?>&moduleID={0}&branch={1}'.format(item.id, item.branch)) + '">' + item.name + '</a>') : ('<span class="tree-toggle">' + item.name + '</span>');
            var $toggle = $('<span class="module-name" data-id="' + item.id + '">' + link + '</span>');
            if(item.type === 'bug') $toggle.append('&nbsp; <span class="text-muted">[B]</span>');
            if(item.type === 'case') $toggle.append('&nbsp; <span class="text-muted">[C]</span>');
            $li.append($toggle);
            if(item.nodeType || item.type) $li.addClass('tree-item-' + (item.nodeType || item.type));
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
                template: '<a href="javascript:;"><?php echo $lang->edit?></a>'
            },
            "delete":
            {
                linkTemplate: '<?php echo helper::createLink('tree', 'delete', "rootID=$rootID&moduleID={0}"); ?>',
                title: '<?php echo $lang->tree->delete ?>',
                template: '<a href="javascript:;"><?php echo $lang->delete?></a>'
            },
            subModules:
            {
                linkTemplate: '<?php echo helper::createLink('tree', 'browse', "rootID=$rootID&viewType=$viewType&moduleID={0}&branch={1}"); ?>',
                title: '<?php echo $lang->tree->child ?>',
                template: '<a href="javascript:;"><?php echo $lang->tree->child?></a>'
            }
        },
        action: function(event)
        {
            var action = event.action, $target = $(event.target), item = event.item;
            if(action.type === 'edit')
            {
                $target.modalTrigger(
                {
                    type: 'ajax',
                    url: action.linkTemplate.format(item.id),
                    keyboard: true
                }).trigger('click');
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
                $.post('<?php echo $this->createLink('tree', 'updateOrder', "root={$root->id}&viewType=$viewType");?>', orders).error(function()
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
    if(!tree.store.time) tree.expand($tree.find('li:not(.tree-action-item)').first());
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

    $tree.find('[data-toggle="tooltip"]').tooltip();
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
