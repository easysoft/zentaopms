<?php
/**
 * The browse view file of tree module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<script language='javascript'>
var rootID = '<?php echo $rootID;?>';
function syncModule()
{
    link = createLink('tree', 'ajaxGetSonModules', 'moduleID=' + $('#productModule').val() + '&rootID=' + rootID);
    $.getJSON(link, function(modules)
    {
        $('.helplink').addClass('hidden');
        $.each(modules, function(key, value)
        {   
            moduleName = value;
            $('.text-3').each(function()
            {
                if(this.value == moduleName) modules[key] = null;
                if(!this.value) $(this).parent().addClass('hidden');
            })
        });  
        $.each(modules, function(key, value)
        {   
            if(value) $('#moduleBox').append("<span><input name=modules[] value=" + value + " style=margin-bottom:5px class=text-3 /><br /><span>");
        })
    })
}
$(document).ready(function()
{
    $("a.iframe").colorbox({width:480, height:320, iframe:true, transition:'none'});
});
</script>
<div class="yui-d0 yui-t3">
  <div class="yui-main">
    <div class="yui-b">
    <form method='post' target='hiddenwin' action='<?php echo $this->createLink('tree', 'manageChild', "root={$root->id}&viewType=$viewType");?>'>
      <table align='center' class='table-1'>
        <caption><?php echo $lang->tree->manageChild;?></caption>
        <tr>
          <td width='10%'>
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
            if($viewType != 'story' and strpos($viewType, 'doc') === false)
            {
                echo html::select('productModule', $productModules, '', 'class=select-3');
                echo html::commonButton($lang->tree->syncFromProduct, 'onclick=syncModule()');
                echo '<br />';
            }
            $maxOrder = 0;
            foreach($sons as $sonModule)
            {
                if($sonModule->order > $maxOrder) $maxOrder = $sonModule->order;
                echo '<span>' . html::input("modules[id$sonModule->id]", $sonModule->name, 'class=text-3 style="margin-bottom:5px"') . '<br /></span>';
            }
            for($i = 0; $i < TREE::NEW_CHILD_COUNT ; $i ++) echo '<span>' . html::input("modules[]", '', 'class=text-3 style="margin-bottom:5px"') . '<br /></span>';
            ?>
          </td>
        </tr>
        <tr>
          <td class='a-center' colspan='3'>
            <?php 
            echo html::submitButton() . html::resetButton();
            echo html::hidden('parentModuleID', $currentModuleID);
            echo html::hidden('maxOrder', $maxOrder);
            ?>      
            <input type='hidden' value='<?php echo $currentModuleID;?>' name='parentModuleID' />
          </td>
        </tr>
      </table>
      </form>
    </div>
  </div>

  <div class="yui-b">
    <form method='post' target='hiddenwin' action='<?php echo $this->createLink('tree', 'updateOrder', "root={$root->id}&viewType=$viewType");?>'>
    <table class='table-1'>
      <caption><?php echo $header->title;?></caption>
      <tr>
        <td>
          <div id='main'><?php echo $modules;?></div>
          <div class='a-center'>
            <?php if(common::hasPriv('tree', 'updateorder')) echo html::submitButton($lang->tree->updateOrder);?>
          </div>
        </td>
      </tr>
    </table>
    </form>
  </div>

</div>  
<?php include '../../common/view/footer.html.php';?>
