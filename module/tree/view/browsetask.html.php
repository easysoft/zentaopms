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
<div id='featurebar'>
  <div class='heading'><?php echo $lang->tree->common;?></div>
</div>
<div class='row'>
  <div class='col-sm-4'>
    <div class='panel'>
      <div class='panel-heading'><i class='icon-cog'></i> <strong><?php echo $title;?></strong></div>
      <div class='panel-body'>
        <ul class='tree-lines' id='modulesTree'></ul>
      </div>
    </div>
  </div>
  <div class='col-sm-8'>
    <form id='childrenForm' class='form-condensed' method='post' target='hiddenwin' action='<?php echo $this->createLink('tree', 'manageChild', "root={$root->id}&viewType=task");?>'>
      <div class='panel'>
        <div class='panel-heading'>
          <i class='icon-sitemap'></i> 
          <?php $manageChild = 'manageTaskChild';?>
          <?php echo $lang->tree->$manageChild;?>
        </div>
        <div class='panel-body'>
          <table class='table table-form'>
            <tr>
              <td class='parentModule'>
                <nobr>
                <?php
                echo html::a($this->createLink('tree', 'browsetask', "root={$root->id}&productID=$productID&viewType=task"), $root->name);
                echo $lang->arrow;
                foreach($parentModules as $module)
                {
                    echo html::a($this->createLink('tree', 'browsetask', "root={$root->id}&productID=$productID&moduleID=$module->id"), $module->name);
                    echo $lang->arrow;
                }
                ?>
                </nobr>
              </td>
              <td id='moduleBox'> 
                <?php
                $maxOrder = 0;
                if($newModule and !$productID)
                {
                    foreach($products as $product)
                    {
                        echo '<span>' . html::input("products[id$product->id]", $product->name, 'class=form-control disabled="true" autocomplete="off"') . '</span>';
                    }
                }
                echo '<div id="sonModule">';
                foreach($sons as $sonModule)
                {
                    if($sonModule->order > $maxOrder) $maxOrder = $sonModule->order;
                    $disabled = $sonModule->type == 'task' ? '' : 'disabled';
                    echo "<div class='row-table' style='margin-bottom:5px;'>";
                    echo "<div class='col-table'>" . html::input("modules[id$sonModule->id]", $sonModule->name, "class='form-control' autocomplete='off' placeholder='{$lang->tree->name}' " . $disabled) . '</div>';
                    echo "<div class='col-table' style='width:120px'><div class='input-group'>" . html::input("shorts[id$sonModule->id]", $sonModule->short, "class='form-control' autocomplete='off' placeholder='{$lang->tree->short}' " . $disabled) . html::hidden("order[id$sonModule->id]", $sonModule->order);
                    echo "<span class='input-group-btn' style='border-left:1px solid'><a href='javascript:;' onclick='insertItem(this)' class='btn btn-block'><i class='icon icon-plus'></i></a></span>";
                    echo "</div></div>";
                    echo "</div>";
                }
                for($i = 0; $i < TREE::NEW_CHILD_COUNT ; $i ++)
                {
                    echo "<div class='row-table addedItem' style='margin-bottom:5px;'>";
                    echo "<div class='col-table'>" . html::input("modules[]", '', "class='form-control' autocomplete='off' placeholder='{$lang->tree->name}'") . '</div>';
                    echo "<div class='col-table' style='width:120px'><div class='input-group'>" . html::input("shorts[]", '', "class='form-control' autocomplete='off' placeholder='{$lang->tree->short}'");
                    echo "<span class='input-group-btn'><a href='javascript:;' onclick='addItem(this)' class='btn btn-block'><i class='icon icon-plus'></i></a></span>";
                    echo "<span class='input-group-btn'><a href='javascript:;' onclick='deleteItem(this)' class='btn btn-block'><i class='icon icon-remove'></i></a></span>";
                    echo '</div></div>';
                    echo html::hidden('branch[]', empty($module) ? 0 : $module->branch) . '</div>';
                }

                echo "<div id='insertItemBox' class='hidden'>";
                echo "<div class='row-table' style='margin-bottom:5px;'>";
                echo "<div class='col-table'>" . html::input("modules[]", '', "class='form-control' autocomplete='off' placeholder='{$lang->tree->name}'") . '</div>';
                echo "<div class='col-table' style='width:120px'><div class='input-group'>" . html::input("shorts[]", '', "class='form-control' autocomplete='off' placeholder='{$lang->tree->short}'") . html::hidden("order[]");
                echo "<span class='input-group-btn' style='border-left:1px solid'><a href='javascript:;' onclick='deleteItem(this)' class='btn btn-block'><i class='icon icon-remove'></i></a></span>";
                echo '</div></div>';
                echo html::hidden('branch[]', empty($module) ? 0 : $module->branch) . '</div>';
                echo '</div>';

                echo '</div>';
                ?>
              </td>
            </tr>
            <tr>
              <td></td>
              <td colspan='2'>
                <?php 
                echo html::submitButton();
                echo $this->session->taskList ? html::linkButton($this->lang->goback, $this->session->taskList) : html::backButton();
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
<style>
.story-item > .tree-actions > .tree-action[data-type='sort'],
.story-item > .tree-actions > .tree-action[data-type='edit'],
.story-item > .tree-actions > .tree-action[data-type='delete'] {display: none!important}
</style>
<script>
$(function()
{
    var data = $.parseJSON('<?php echo helper::jsonEncode4Parse($tree);?>');
    var options = {
        name: 'tree-project-edit',
        initialState: 'preserve',
        data: data,
        itemCreator: function($li, item)
        {
            
            var $toggle = $('<span class="module-name" data-id="' + item.id + '">' + link + '</span>');
            

            var title = (item.type === 'product' ? '<i class="icon icon-cube text-muted"></i> ' : '') + item.name;
            var link = item.id !== undefined ? ('<a href="' + createLink('tree', 'browsetask', 'rootID=<?php echo $rootID ?>&viewType=task&moduleID={0}'.format(item.id)) + '">' + title + '</a>') : ('<span class="tree-toggle">' + title + '</span>');
            var $toggle = $('<span class="module-name" data-id="' + item.id + '">' + link + '</span>');
            if(item.type === 'task')
            {
                $toggle.append('&nbsp; <span class="text-muted">[T]</span>');
                $li.addClass('task-item');
            }
            if(item.type === 'story') $li.addClass('story-item');
            $li.append($toggle);
            if(item.nodeType) $li.addClass('tree-item-' + item.nodeType);
            return true;
        },
        actions: 
        {
            sort:
            {
                title: '<?php echo $lang->tree->dragAndSort ?>',
                template: '<a class="sort-handler" href="javascript:;"><?php echo $lang->tree->sort ?></a>'
            },
            edit:
            {
                linkTemplate: '<?php echo helper::createLink('tree', 'edit', "moduleID={0}&type=task"); ?>',
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
                linkTemplate: '<?php echo helper::createLink('tree', 'browsetask', "rootID=$rootID&viewType=task&moduleID={0}"); ?>',
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
            else if(action.type === 'add')
            {
                window.location.href = action.linkTemplate.format(item.id);
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
                $.post('<?php echo $this->createLink('tree', 'updateOrder', "root={$root->id}&viewType=task");?>', orders).error(function()
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
<?php include '../../common/view/footer.html.php';?>
