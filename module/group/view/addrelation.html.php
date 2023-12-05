<?php
/**
 * The addrelation view file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <?php if($app->rawMethod != 'addrelation'):?>
    <h2><?php echo $type == 'depend' ? $lang->group->deleteDependent : $lang->group->deleteRecommendation;?></h2>
    <?php else:?>
    <h2><?php echo $type == 'depend' ? $lang->group->addDependent : $lang->group->addRecommendation;?></h2>
    <?php endif;?>
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
            $privModules[$priv->moduleCode][] = $priv->id;
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
                <?php foreach($packages as $packageID => $packageName):?>
                <?php if(empty($modulePrivs[$privModule][$packageID])) continue;?>
                <li class='clearleft'>
                  <?php echo html::a('#', $packageName);?>
                  <ul>
                    <?php foreach($modulePrivs[$privModule][$packageID] as $id => $modulePriv):?>
                    <li><?php echo html::checkbox("relation[{$privModule}]", array($id => $modulePriv->name), '')?></li>
                    <?php endforeach;?>
                    <?php unset($modulePrivs[$privModule][$packageID]);?>
                  </ul>
                </li>
                <?php endforeach;?>
                <?php if(!empty($modulePrivs[$privModule])):?>
                <li class='clearleft'>
                  <?php echo html::a('#', $lang->group->unassigned);?>
                  <ul>
                    <?php foreach($modulePrivs[$privModule] as $packageID => $packagePrivs):?>
                    <?php foreach($packagePrivs as $id => $modulePriv):?>
                    <li><?php echo html::checkbox("relation[{$privModule}]", array($id => $modulePriv->name), '')?></li>
                    <?php endforeach;?>
                    <?php endforeach;?>
                  </ul>
                </li>
                <?php endif;?>
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
<?php js::set('type', $type);?>
<?php include '../../common/view/footer.lite.html.php';?>
