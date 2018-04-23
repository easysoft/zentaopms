<?php
/**
 * The browse view file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='cards pd-0'>
    <?php foreach($extensions as $extension):?>
    <div class='cell'>
      <div class='detail'>
        <div class='detail-title'><strong><?php echo $extension->name;?></strong></div>
        <div class='detail-content'><?php echo $extension->desc;?></div>
        <div class='detail-actions'>
          <div class='pull-right'>
            <div class='btn-group'>
              <?php
              $structureCode  = html::a(inlink('structure',  "extension=$extension->code"), $lang->extension->structure, '',  "class='btn iframe'");
              $deactivateCode = html::a(inlink('deactivate', "extension=$extension->code"), $lang->extension->deactivate, '', "class='btn iframe'");
              $activateCode   = html::a(inlink('activate',   "extension=$extension->code"), $lang->extension->activate, '',   "class='btn iframe'");
              $uninstallCode  = html::a(inlink('uninstall',  "extension=$extension->code"), $lang->extension->uninstall, '',  "class='btn iframe'");
              $installCode    = html::a(inlink('install',    "extension=$extension->code"), $lang->extension->install, '',    "class='btn iframe'");
              $eraseCode      = html::a(inlink('erase',      "extension=$extension->code"), $lang->extension->erase, '',      "class='btn iframe'");
              
              if(isset($extension->viewLink))
              {
                  echo html::a($extension->viewLink, $lang->extension->view, '', "class='btn extension'");
              }
              if($extension->status == 'installed')
              {
                  echo $structureCode;
              }
              if($extension->status == 'installed' and !empty($extension->upgradeLink))
              {
                  echo html::a($extension->upgradeLink, $lang->extension->upgrade, '', "class='btn iframe'");
              }
    
              if($extension->type != 'patch')
              {
                  if($extension->status == 'installed')   echo $deactivateCode . $uninstallCode;
                  if($extension->status == 'deactivated') echo $activateCode   . $uninstallCode;
                  if($extension->status == 'available')   echo $installCode    . $eraseCode;
              }
              echo html::a($extension->site, $lang->extension->site, '_blank', 'class=btn');
              ?>          
            </div>
          </div>
          <?php
          echo "{$lang->extension->version}:    <i>{$extension->version}</i> ";
          echo "{$lang->extension->author}:     <i>{$extension->author}</i> ";
          $expireDate = $this->extension->getExpireDate($extension);
          if(!empty($expireDate)) echo "{$lang->extension->expireDate}:     <i>{$expireDate}</i>";
          ?>
        </div>
      </div>
    </div>
    <?php endforeach;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
