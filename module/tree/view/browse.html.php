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
  <div class='heading'><i class='icon-cogs'></i> <?php echo $title;?>  </div>
</div>
<div class='main'>
  <div class='panel'>
    <div class='panel-body'>
      <div class='container'>
        <ul class='tree-lines' id='modulesTree'></ul>
      </div>
    </div>
  </div>
</div>
<div class='modal fade' id='addChildModal'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
        <h4 class='modal-title'><i class='icon-sitemap'></i> <span class='module-name'></span> <i class="icon icon-angle-right"></i>
        <?php echo strpos($viewType, 'doc') !== false ? $lang->doc->addType : $lang->tree->addChild;?>
        <?php if($viewType == 'story' and $allProduct):?>
        <div class='pull-right'><?php echo html::a('javascript:toggleCopy()', $lang->tree->syncFromProduct, '', "class='btn btn-sm'")?></div>
        <?php endif;?>
        </h4>
      </div>
      <div class='modal-body'>
        <form method='post' target='hiddenwin' action='<?php echo $this->createLink('tree', 'manageChild', "root={$root->id}&viewType=$viewType");?>' class='form-condensed'>
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
          for($i = 0; $i < TREE::NEW_CHILD_COUNT ; $i ++)
          {
              echo "<div class='row-table'>";
              echo "<div class='col-table'>" . html::input("modules[]", '', "class='form-control' placeholder='{$lang->tree->name}'") . '</div>';
              if($hasBranch) echo '<div class="col-table">' . html::select("branch[]", $branches, $branch, 'class="form-control"') . '</div>';
              echo "<div class='col-table' style='width:120px'><div class='input-group'>" . html::input("shorts[]", '', "class='form-control' placeholder='{$lang->tree->short}'");
              echo "<span class='input-group-addon fix-border'><a href='javascript:;' onclick='addItem(this)'><i class='icon icon-plus'></i></a></span>";
              echo "<span class='input-group-addon'><a href='javascript:;' onclick='deleteItem(this)'><i class='icon icon-remove'></i></a></span>";
              echo '</div></div>';
              echo '</div>';
          }
          ?>
          <div class='text-center'>
            <?php 
            echo html::submitButton() . html::commonButton($lang->close, 'data-dismiss="modal"', 'btn');
            echo html::hidden('maxOrder', $maxOrder);
            echo html::hidden('parentModuleID', $currentModuleID);
            ?>
          </div>
        </form>
      </div>
    </div>
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
                template: '<a data-toggle="tooltip" href="javascript:;"><i class="icon icon-plus"></i>',
                templateInList: '<a href="javascript:;"><i class="icon icon-plus"></i> <?php echo strpos($viewType, 'doc') !== false ? $lang->doc->addType : $lang->tree->addChild;?></a>'
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
                var $modal = $('#addChildModal');
                var $ul = $target.parent().is('.tree-action-item') ? $target.closest('ul') : $target.closest('li').children('ul');
                var maxOrder = 0;
                $ul.children('li:not(.tree-action-item)').each(function() {
                    maxOrder = Math.max(maxOrder, $(this).data('order'));
                });
                $modal.find('input[name="parentModuleID"]').val(item ? item.id : 0);
                $modal.find('input[name="maxOrder"]').val(maxOrder);
                $modal.find('.module-name').text(item ? item.name : '');
                $modal.modal('show');
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
