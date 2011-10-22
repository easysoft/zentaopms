<?php
/**
 * The obtain view file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<table class='cont-lt1'>
  <tr valign='top'>
    <td class='side'>
      <div class='box-title'><?php echo $lang->extension->obtain;?></div>
      <div class='box-content a-center'>
        <?php
        echo "<span id='byaddedtime'>"   . html::a(inlink('obtain', 'type=byAddedTime'),   $lang->extension->byAddedTime)   . '</span><br />';
        echo "<span id='bydownloads'>"   . html::a(inlink('obtain', 'type=byDownloads'),   $lang->extension->byDownloads)   . '</span><br />';
        echo "<span id='byupdatedtime'>" . html::a(inlink('obtain', 'type=byUpdatedTime'), $lang->extension->byUpdatedTime) . '</span><br />';
        ?>
      </div>
      <div class='box-title'><?php echo $lang->extension->bySearch;?></div>
      <div class='box-content a-center'>
        <form method='post' action='<?php echo inlink('obtain', 'type=bySearch');?>'>
        <?php echo html::input('key', $this->post->key, "class='text-1'") . html::submitButton($lang->extension->bySearch);?>
        </form>
      </div>
      <div class='box-title'><?php echo $lang->extension->byCategory;?></div>
      <div class='box-content' id='tree'>
        <?php $moduleTree ? print($moduleTree) : print($lang->extension->errorGetModules);?>
      </div>
    </td>
    <td class='divider'></td>
    <td> 
      <?php if($extensions):?>
      <?php foreach($extensions as $extension):?>
        <table class='table-1'>
          <caption><?php echo "#$extension->id $extension->name";?></caption> 
          <tr valign='middle'>
            <td>
              <div class='mb-10px'><?php echo $extension->abstract;?></div>
              <div>
                <?php
                echo "{$lang->extension->version}:    <i>{$extension->releaseVersion}</i> ";
                echo "{$lang->extension->author}:     <i>{$extension->author}</i> ";
                echo "{$lang->extension->downloads}:  <i><strong>{$extension->downloads}</strong></i> ";
                echo "{$lang->extension->public}:     <i>{$lang->extension->publicList[$extension->public]}</i> ";
                echo "{$lang->extension->compatible}: <i><strong>{$lang->extension->compatibleList[$extension->compatible]}</strong></i> ";
                ?>
              </div>
            </td>
            <td class='w-200px a-left'>
              <?php 
              $installLink   = inlink('install',  "extension=$extension->code&downLink=" . helper::safe64Encode($extension->downLink) . "&md5=$extension->md5&type=$extension->type&&overridePackage=no&ignoreCompitable=yes");
              echo html::a($extension->viewLink, $lang->extension->view, '', 'class="button-c extension"');
              if($extension->public)
              {
                  if($extension->type != 'computer' and $extension->type != 'mobile')
                  {
                      if(isset($installeds[$extension->code]))
                      {
                          if($installeds[$extension->code]->version != $extension->releaseVersion)
                          {
                              $upgradeLink = inlink('upgrade',  "extension=$extension->code&downLink=" . helper::safe64Encode($extension->downLink) . "&md5=$extension->md5&type=$extension->type");
                              echo html::a($upgradeLink, $lang->extension->upgrade, '', 'class="iframe button-c"');
                          }
                          else
                          {
                              echo html::commonButton($lang->extension->installed, "disabled='disabled' style='color:gray'");
                          }
                      }
                      else
                      {
                          $label = $extension->compatible ? $lang->extension->installAuto : $lang->extension->installForce;
                          echo html::a($installLink, $label, '', 'class="iframe button-c"');
                      }
                  }
              }
              echo html::a($extension->downLink, $lang->extension->downloadAB, '', 'class="manual button-c"');
              echo html::a($extension->site, $lang->extension->site, '_blank', 'class=button-c');
            ?>
          </td></tr>
          </table>
        <?php endforeach;?>
        <?php if($pager) $pager->show();?>
      <?php else:?>
        <div class='box-title'><?php echo $lang->extension->errorOccurs;?></div>
        <div class='box-content'><?php echo $lang->extension->errorGetExtensions;?></div>
      <?php endif;?>
    </td>
  </tr>
</table>
<script>
$('#<?php echo $type;?>').addClass('active')
$('#module<?php echo $moduleID;?>').addClass('active')
</script>
<?php include '../../common/view/footer.html.php';?>
