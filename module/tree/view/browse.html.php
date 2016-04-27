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
  <div class='col-sm-6 col-md-4 col-lg-3'>
    <div class='panel'>
      <div class='panel-heading'><i class='icon-cog'></i> <strong><?php echo $title;?></div>
      <div class='panel-body'>
        <div class='container'>
          <ul class='tree-lines' id='modulesTree'></ul>
        </div>
      </div>
    </div>
  </div>
  <div class='col-sm-6 col-md-8 col-lg-9'>
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
                    echo "<div class='col-table'>" . html::input("modules[id$sonModule->id]", $sonModule->name, 'class="form-control"' . $disabled) . '</div>';
                    if($hasBranch) echo "<div class='col-table'>" . html::select("branch[id$sonModule->id]", $branches, $sonModule->branch, 'class="form-control" disabled') . '</div>';
                    echo "<div class='col-table' style='width:70px'>" . html::input("shorts[id$sonModule->id]", $sonModule->short, "class='form-control' placeholder='{$lang->tree->short}' $disabled") . '</div>';
                    echo '</div>';
                }
                for($i = 0; $i < TREE::NEW_CHILD_COUNT ; $i ++)
                {
                    echo "<div class='row-table' style='margin-bottom:5px'>";
                    echo "<div class='col-table'>" . html::input("modules[]", '', "class='form-control' placeholder='{$lang->tree->name}'") . '</div>';
                    if($hasBranch) echo '<div class="col-table">' . html::select("branch[]", $branches, $branch, 'class="form-control"') . '</div>';
                    echo "<div class='col-table' style='width:120px'><div class='input-group'>" . html::input("shorts[]", '', "class='form-control' placeholder='{$lang->tree->short}'");
                    echo "<span class='input-group-addon fix-border'><a href='javascript:;' onclick='addItem(this)'><i class='icon icon-plus'></i></a></span>";
                    echo "<span class='input-group-addon'><a href='javascript:;' onclick='deleteItem(this)'><i class='icon icon-remove'></i></a></span>";
                    echo '</div></div>';
                    echo '</div>';
                }
                ?>
                </div>
              </td>
            </tr>
            <tr>
              <td></td>
              <td colspan='2'>
                <?php 
                echo html::submitButton() . html::backButton();
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
<script>
$(function()
{
    var data = $.parseJSON('<?php echo json_encode($tree);?>');
    var options = {
        initialState: 'preserve',
        data: data,
        itemCreator: function($li, item)
        {
            var $toggle = $('<span class="tree-toggle"><span class="module-name" data-id="' + item.id + '">' + item.name + '</span></span>');
            if(item.short)
            {
                $toggle.append('&nbsp; <span class="module-manager text-muted">(' + item.short + ')</span>');
            }
            $li.append($toggle);
            return true;
        },
        actions: 
        {
            sort:
            {
                title: '<?php echo $lang->tree->dragAndSort ?>',
                template: '<a class="sort-handler" data-toggle="tooltip" href="javascript:;"><i class="icon icon-move"></i>'
            },
            edit:
            {
                linkTemplate: '<?php echo helper::createLink('tree', 'edit', "moduleID={0}&type=$viewType"); ?>',
                title: '<?php echo $lang->tree->edit ?>',
                template: '<a data-toggle="tooltip" href="javascript:;"><i class="icon icon-pencil"></i>'
            },
            add:
            {
                title: '<?php echo strpos($viewType, 'doc') !== false ? $lang->doc->addType : $lang->tree->addChild;?>',
                template: '<a data-toggle="tooltip" href="javascript:;"><i class="icon icon-sitemap"></i>',
                linkTemplate: '<?php echo helper::createLink('tree', 'browse', "rootID=$rootID&viewType=$viewType&currentModuleID={0}"); ?>',
                templateInList: false
            },
            "delete":
            {
                linkTemplate: '<?php echo helper::createLink('tree', 'delete', "rootID=$rootID&moduleID={0}"); ?>',
                title: '<?php echo $lang->tree->delete ?>',
                template: '<a data-toggle="tooltip" href="javascript:;"><i class="icon icon-trash"></i>'
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
                    url: action.linkTemplate.format(item.id)
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
                $.post('<?php echo $this->createLink('tree', 'updateOrder', "root={$root->id}&viewType=$viewType");?>', orders).error(function()
                {
                    bootbox.alert(lang.timeout);
                });
            }
        }
    };

    if(<?php echo common::hasPriv('tree', 'updateorder') ? 'false' : 'true' ?>) options.actions["sort"] = false;

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
