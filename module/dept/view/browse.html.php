<?php
/**
 * The browse view file of dept module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @version     $Id: browse.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'><?php echo html::icon($lang->icons['dept']);?> <?php echo $lang->dept->common;?></div>
</div>
<div class='main'>
  <div class='panel'>
    <div class='panel-heading'>
      <?php echo html::icon($lang->icons['dept']);?> <strong><?php echo $title;?></strong>
    </div>
    <div class='panel-body'>
      <div class='container'>
        <ul class='tree-lines' id='deptTree'></ul>
      </div>
    </div>
  </div>
</div>
<div class='modal fade' id='addChildModal'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
        <h4 class='modal-title'><span class='dept-name'></span> <i class="icon icon-angle-right"></i> <?php echo $lang->dept->add;?></h4>
      </div>
      <div class='modal-body'>
        <form method='post' target='hiddenwin' action='<?php echo $this->createLink('dept', 'manageChild');?>' class='form-condensed'>
          <?php
            for($i = 0; $i < DEPT::NEW_CHILD_COUNT ; $i ++) echo html::input("depts[]", '', "class='form-control'");
          ?>
          <div class='text-center'>
            <?php echo html::submitButton() . html::commonButton($lang->close, 'data-dismiss="modal"', 'btn')?>
            <input type='hidden' value='0' name='parentDeptID' />
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
    var $tree = $('#deptTree').tree(
    {
        initialState: 'preserve',
        data: data,
        itemCreator: function($li, item)
        {
            var $toggle = $('<span class="tree-toggle"><span class="dept-name">' + item.name + '</span></span>');
            if(item.manager)
            {
                $toggle.append('&nbsp; <span class="dept-manager text-muted"><i class="icon icon-user"></i> ' + item.managerName + '</span>');
            }
            $li.append($toggle);
            return true;
        },
        actions: 
        {
            sort:
            {
                title: '<?php echo $lang->dept->dragAndSort ?>',
                template: '<a class="sort-handler" data-toggle="tooltip" href="javascript:;"><i class="icon icon-move"></i>'
            },
            edit:
            {
                linkTemplate: '<?php echo helper::createLink('dept', 'edit', "deptid={0}"); ?>',
                title: '<?php echo $lang->dept->edit ?>',
                template: '<a data-toggle="tooltip" href="javascript:;"><i class="icon icon-pencil"></i>'
            },
            add:
            {
                title: '<?php echo $lang->dept->add ?>',
                template: '<a data-toggle="tooltip" href="javascript:;"><i class="icon icon-plus"></i>',
                templateInList: '<a href="javascript:;"><i class="icon icon-plus"></i> <?php echo $lang->dept->add ?></a>'
            },
            "delete":
            {
                linkTemplate: '<?php echo helper::createLink('dept', 'delete', "deptid={0}"); ?>',
                title: '<?php echo $lang->dept->delete ?>',
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
                $modal.find('input[name="parentDeptID"]').val(item.id);
                $modal.find('.dept-name').text(item.name);
                $modal.modal('show');
            }
            else if(action.type === 'sort')
            {
                var orders = {};
                $('#deptTree').find('li:not(.tree-action-item)').each(function()
                {
                    var $li = $(this);
                    var item = $li.data();
                    orders['orders[' + item.id + ']'] = $li.attr('data-order') || item.order;
                });
                $.post('<?php echo $this->createLink('dept', 'updateOrder') ?>', orders).error(function()
                {
                    bootbox.alert(lang.timeout);
                });
            }
        }
    });

    var tree = $tree.data('zui.tree');
    if(!tree.store.time) tree.expand($tree.find('li:not(.tree-action-item)').first());

    $tree.on('mouseenter', 'li:not(.tree-action-item)', function(e)
    {
        $('#deptTree').find('li.hover').removeClass('hover');
        $(this).addClass('hover');
        e.stopPropagation();
    });

    $tree.find('[data-toggle="tooltip"]').tooltip();
});
</script>
<?php include '../../common/view/footer.html.php';?>
