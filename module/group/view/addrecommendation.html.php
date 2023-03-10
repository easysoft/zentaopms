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
#dataform {min-height:240px;}
</style>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->group->addRecommendation;?></h2>
  </div>
  <div class='main-row'>
    <div class="main-col">
      <div>
        <?php
        $privName    = array();
        $privModules = array();
        foreach($privs as $priv)
        {
            $privName[] = $priv->name;
            $privModules[$priv->module][] = $priv->id;
        }
        $privName = implode('、', $privName);
        printf($lang->group->selectedPrivs, $privName);
        ?>
      </div>
      <div class='w-300px'><?php echo html::select('module', $modules, '', "class='form-control chosen'")?></div>
      <form id='dataform' method='post' target='hiddenwin'>
        <div class='treeBox'>
          <?php foreach($privModules as $privModule => $privIdList):?>
          <ul class='tree' data-ride='tree'>
            <li>
              <?php echo html::a('#', $modules[$privModule]);?>
              <ul class='relationBox'>
                <?php foreach($modulePrivs[$privModule] as $id => $modulePriv):?>
                <?php if(in_array($id, $privIdList)) continue;?>
                <li><?php echo html::checkbox("relation[{$priv->module}]", array($id => $modulePriv->name), (empty($recommends) or isset($recommends[$id])) ? $id : '')?></li>
                <?php endforeach;?>
              </ul>
            </li>
          </ul>
          <?php endforeach;?>
        </div>
        <div class='text-center'><?php echo html::submitButton();?></div>
      </form>
    </div>
  </div>
</div>
<?php js::set('privIdList', implode(',', array_keys($privs)));?>
<script>
$(function()
{
    $('ul.tree:first > li').addClass('open').addClass('id');
    $('#module').change(function()
    {
        if($(this).val() == '') return;
        $('.treeBox').load(createLink('group', 'ajaxGetPrivTree', 'privIdList=' + privIdList + '&module=' + $(this).val()), function()
        {
            $('ul.tree').tree();
            $('ul.tree:first > li').addClass('open').addClass('id');
        });
    })
})
</script>
<?php include '../../common/view/footer.lite.html.php';?>
