<?php
/**
 * The addrecommendation view file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<style>
.tree .relationBox li{float:left; width: 150px;}
</style>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->group->addRecommendation;?></h2>
  </div>
  <div class='main-row'>
    <div class="main-col">
      <div><?php printf($lang->group->selectedPrivs, $priv->name)?></div>
      <div class='w-300px'><?php echo html::select('module', $modules, '', "class='form-control chosen'")?></div>
      <form id='dataform' method='post' target='hiddenwin'>
        <ul class='tree' data-ride='tree'>
          <li>
            <?php echo html::a('#', $modules[$priv->module]);?>
            <ul class='relationBox'>
              <?php foreach($modulePrivs[$priv->module] as $id => $modulePriv):?>
              <?php if($id == $priv->id) continue;?>
              <li><?php echo html::checkbox('relation', array($id => $modulePriv->name), (empty($recommends) or isset($recommends[$id])) ? $id : '')?></li>
              <?php endforeach;?>
            </ul>
          </li>
        </ul>
        <div class='text-center'><?php echo html::submitButton();?></div>
      </form>
    </div>
  </div>
</div>
<?php js::set('privID', $priv->id);?>
<script>
$(function()
{
    $('#module').change(function()
    {
        if($(this).val() == '') return;
        $('ul.tree').load(createLink('group', 'ajaxGetPrivTree', 'privID=' + privID + '&module=' + $(this).val()), function(){$('ul.tree').tree();});
    })
})
</script>
<?php include '../../common/view/footer.lite.html.php';?>
