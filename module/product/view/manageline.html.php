<?php
/**
 * The html productlist file of productlist method of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainContent" class="main-row fade">
  <div class='side-col col-4'>
    <div class='panel'>
      <div class='panel-heading'>
        <div class="panel-title"><?php echo $lang->product->line;?></div>
      </div>
      <div class='panel-body'>
        <ul id='modulesTree' data-name='tree-line'></ul>
      </div>
    </div>
  </div>
  <div class='main-col col-8'>
    <div class='panel'>
      <div class='panel-heading'>
        <div class="panel-title"><?php echo $lang->product->manageLine;?></div>
        <div class='panel-body'>
          <form id='lineForm' method='post' target='hiddenwin'>
            <table class='table table-form table-auto'>
              <tr>
                <td>
                  <div id='son'>
                    <div class="table-row row-module row-module-new">
                      <div class="table-col text-center"><strong><?php echo $lang->product->lineName;?></strong></div>
                      <?php if($this->config->systemMode == 'ALM'):?>
                      <div class="table-col text-center"><strong><?php echo $lang->product->program;?></strong></div>
                      <?php endif;?>
                      <div class="table-col col-actions"> </div>
                    </div>
                    <?php $maxOrder = 0;?>
                    <?php foreach($lines as $line):?>
                    <div class="table-row row-module">
                      <div class="table-col col-module"><?php echo html::input("modules[id$line->id]", $line->name, 'class="form-control"');?></div>
                      <?php if($this->config->systemMode == 'ALM'):?>
                      <div class="table-col col-programs"><?php echo html::select("programs[id$line->id]", $programs, $line->root, "class='form-control chosen' required");?></div>
                      <?php endif;?>
                      <div class="table-col col-actions"> </div>
                    </div>
                    <?php endforeach;?>
                    <?php for($i = 0; $i <= 5 ; $i ++):?>
                    <div class="table-row row-module row-module-new">
                      <div class="table-col col-module"><?php echo html::input("modules[]", '', "class='form-control'");?></div>
                      <?php if($this->config->systemMode == 'ALM'):?>
                      <div class="table-col col-programs"><?php echo html::select("programs[]", $programs, '', "class='form-control chosen' required");?></div>
                      <?php endif;?>
                      <div class="table-col col-actions">
                        <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
                        <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItem(this)"><i class="icon icon-close"></i></button>
                      </div>
                    </div>
                  <?php endfor;?>
                  </div>
                  <div id="insertItemBox" class="template">
                    <div class="table-row row-module row-module-new">
                      <div class="table-col col-module"><?php echo html::input("modules[]", '', "class='form-control'");?></div>
                      <?php if($this->config->systemMode == 'ALM'):?>
                      <div class="table-col col-programs"><?php echo html::select("programs[]", $programs, '', "class='form-control chosen' required");?></div>
                      <?php endif;?>
                      <div class="table-col col-actions">
                        <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
                        <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItem(this)"><i class="icon icon-close"></i></button>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td colspan="2" class="form-actions">
                  <?php echo html::submitButton();?>
                  <?php echo html::backButton();?>
                </td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$(function()
{
    var data = $.parseJSON('<?php echo helper::jsonEncode4Parse($lines);?>');
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
            var name = item.name;
            var $toggle = $('<span class="module-name" data-id="' + item.id + '" title="' + name + '">' + name + '</span>');
            $li.append($toggle);
            if(item.nodeType || item.type) $li.addClass('tree-item-' + (item.nodeType || item.type));
            return true;
        },
        actions:
        {
            sort:
            {
                title: '<?php echo $lang->tree->dragAndSort ?>',
                template: '<a class="sort-handler"><i class="icon-move"></i></a>'
            },
            "delete":
            {
                linkTemplate: '<?php echo helper::createLink('tree', 'delete', "rootID=0&moduleID={0}"); ?>',
                title: '<?php echo $lang->delete ?>',
                template: '<a><i class="icon-trash"></i></a>'
            },
        },
        action: function(event)
        {
            var action = event.action, $target = $(event.target), item = event.item;
            if(action.type === 'delete')
            {
                hiddenwin.location.href = action.linkTemplate.format(item.id);
            }
            else if(action.type === 'sort')
            {
                var orders = {};
                $('#modulesTree').find('li:not(.tree-action-item)').each(function()
                {
                    var $li = $(this);
                    if($li.hasClass('tree-item-branch')) return;

                    var item = $li.data();
                    orders['orders[' + item.id + ']'] = $li.attr('data-order') || item.order;
                });
                $.post('<?php echo $this->createLink('tree', 'updateOrder', "rootID=0&viewType=line");?>', orders).error(function()
                {
                    bootbox.alert(lang.timeout);
                });
            }
        }
    };

    if(<?php echo common::hasPriv('tree', 'updateorder') ? 'false' : 'true' ?>) options.actions["sort"] = false;
    if(<?php echo common::hasPriv('tree', 'delete') ? 'false' : 'true' ?>) options.actions["delete"] = false;

    var $tree = $('#modulesTree').tree(options);
    var tree = $tree.data('zui.tree');
});
</script>
<?php include '../../common/view/footer.html.php';?>
