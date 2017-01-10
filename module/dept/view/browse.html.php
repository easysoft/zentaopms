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
<div class='row'>
  <div class='col-sm-4'>
    <div class='panel'>
      <div class='panel-heading'><?php echo html::icon($lang->icons['dept']);?> <strong><?php echo $title;?></strong></div>
      <div class='panel-body'>
        <div class='container'>
          <ul class='tree-lines' id='deptTree'></ul>
        </div>
      </div>
    </div>
  </div>
  <div class='col-sm-8'>
    <div class='panel panel-sm'>
      <div class='panel-heading'>
        <i class='icon-sitemap'></i> <strong><?php echo $lang->dept->manageChild;?></strong>
      </div>
      <div class='panel-body'>
        <form method='post' target='hiddenwin' action='<?php echo $this->createLink('dept', 'manageChild');?>' class='form-condensed'>
          <table class='table table-form'>
            <tr>
              <td>
                <nobr>
                <?php
                echo html::a($this->createLink('dept', 'browse'), $this->app->company->name);
                echo $lang->arrow;
                foreach($parentDepts as $dept)
                {
                    echo html::a($this->createLink('dept', 'browse', "deptID=$dept->id"), $dept->name);
                    echo $lang->arrow;
                }
                ?>
                </nobr>
              </td>
              <td class='w-300px'> 
                <?php
                $maxOrder = 0;
                foreach($sons as $sonDept)
                {
                    if($sonDept->order > $maxOrder) $maxOrder = $sonDept->order;
                    echo html::input("depts[id$sonDept->id]", $sonDept->name, "class='form-control' autocomplete='off'");
                }
                for($i = 0; $i < DEPT::NEW_CHILD_COUNT ; $i ++) echo html::input("depts[]", '', "class='form-control' autocomplete='off'");
               ?>
              </td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td>
                <?php echo html::submitButton() . html::backButton() . html::hidden('maxOrder', $maxOrder);?>
                <input type='hidden' value='<?php echo $deptID;?>' name='parentDeptID' />
              </td>
            </tr>
          </table>
        </form>
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
            for($i = 0; $i < DEPT::NEW_CHILD_COUNT ; $i ++) echo html::input("depts[]", '', "class='form-control' autocomplete='off'");
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
    var data = $.parseJSON('<?php echo helper::jsonEncode4Parse($tree);?>');
    var options = {
        name: 'deptTree',
        initialState: 'preserve',
        data: data,
        itemCreator: function($li, item)
        {
            var link = item.id !== undefined ? ('<a href="' + createLink('dept', 'browse', 'dept={0}'.format(item.id)) + '">' + item.name + '</a>') : ('<span class="tree-toggle">' + item.name + '</span>');
            var $toggle = $('<span class="dept-name" data-id="' + item.id + '">' + link + '</span>');
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
                template: '<a class="sort-handler" data-toggle="tooltip" href="javascript:;"><i class="icon icon-move"></i></a>'
            },
            edit:
            {
                linkTemplate: '<?php echo helper::createLink('dept', 'edit', "deptid={0}"); ?>',
                title: '<?php echo $lang->dept->edit ?>',
                template: '<a data-toggle="tooltip" href="javascript:;"><?php echo $lang->edit?></a>'
            },
            "delete":
            {
                linkTemplate: '<?php echo helper::createLink('dept', 'delete', "deptid={0}"); ?>',
                title: '<?php echo $lang->dept->delete ?>',
                template: '<a data-toggle="tooltip" href="javascript:;"><?php echo $lang->delete?></a>'
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
    };

    if(<?php echo common::hasPriv('dept', 'updateorder') ? 'false' : 'true' ?>) options.actions["sort"] = false;
    if(<?php echo common::hasPriv('dept', 'edit') ? 'false' : 'true' ?>) options.actions["edit"] = false;
    if(<?php echo common::hasPriv('dept', 'delete') ? 'false' : 'true' ?>) options.actions["delete"] = false;

    var $tree = $('#deptTree').tree(options);

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
