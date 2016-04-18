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
        <h4 class='modal-title'><span class='module-name'></span> <i class="icon icon-angle-right"></i> <?php echo $lang->tree->addChild;?></h4>
      </div>
      <div class='modal-body'>
        <form method='post' target='hiddenwin' action='<?php echo $this->createLink('tree', 'manageChild', "root={$root->id}&viewType=task");?>' class='form-condensed'>
          <?php
            for($i = 0; $i < TREE::NEW_CHILD_COUNT ; $i ++)
            {
                echo "<div class='row-table'>";
                echo "<div class='col-table'>" . html::input("modules[]", '', "class='form-control' placeholder='{$lang->tree->name}'") . '</div>';
                echo "<div class='col-table' style='width:70px'>";
                echo html::input("shorts[]", '', "class='form-control' placeholder='{$lang->tree->short}'");
                echo html::hidden('branch[]', empty($module) ? 0 : $module->branch);
                echo '</div></div>';
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
<style>
.story-item .tree-action[data-type='sort'],
.story-item .tree-action[data-type='edit'],
.story-item .tree-action[data-type='delete'] {display: none!important}
</style>
<script>
$(function()
{
    var data = $.parseJSON('<?php echo json_encode($tree);?>');
    var $tree = $('#modulesTree').tree(
    {
        initialState: 'preserve',
        data: data,
        itemCreator: function($li, item)
        {
            var title = (item.type === 'product' ? '<i class="icon icon-cube text-muted"></i> ' : '') + item.name;
            var $toggle = $('<span class="tree-toggle"><span class="module-name" data-id="' + item.id + '">' + title + '</span></span>');
            if(item.short)
            {
                $toggle.append('&nbsp; <span class="module-manager text-muted">(' + item.short + ')</span>');
            }
            if(item.type === 'task')
            {
                $toggle.append('&nbsp; <span class="text-muted">[T]</span>');
            }
            if(item.type === 'story')
            {
                $li.addClass('story-item');
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
                linkTemplate: '<?php echo helper::createLink('tree', 'edit', "moduleID={0}&type=task"); ?>',
                title: '<?php echo $lang->tree->edit ?>',
                template: '<a data-toggle="tooltip" href="javascript:;"><i class="icon icon-pencil"></i>'
            },
            add:
            {
                title: '<?php echo $lang->tree->addChild ?>',
                template: '<a data-toggle="tooltip" href="javascript:;"><i class="icon icon-plus"></i>',
                templateInList: '<a href="javascript:;"><i class="icon icon-plus"></i> <?php echo $lang->tree->addChild ?></a>'
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
                $.post('<?php echo $this->createLink('tree', 'updateOrder', "root={$root->id}&viewType=task");?>', orders).error(function()
                {
                    bootbox.alert(lang.timeout);
                });
            }
        }
    });

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
