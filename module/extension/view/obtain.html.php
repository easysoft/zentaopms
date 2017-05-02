<?php
/**
 * The obtain view file of extension module of ZenTaoPMS.
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
<div class='side'>
  <form class='side-search mgb-20' method='post' action='<?php echo inlink('obtain', 'type=bySearch');?>'>
    <div class="input-group">
      <?php echo html::input('key', $this->post->key, "class='form-control' placeholder='{$lang->extension->bySearch}' autocomplete='off'");?>
      <span class="input-group-btn">
        <?php echo html::submitButton('<i class="icon-search"></i>', '', 'btn'); ?>
      </span>
    </div>
  </form>
  <div class='list-group'>
      <?php
      echo html::a(inlink('obtain', 'type=byUpdatedTime'), $lang->extension->byUpdatedTime, '', "class='list-group-item' id='byupdatedtime'");
      echo html::a(inlink('obtain', 'type=byAddedTime'),   $lang->extension->byAddedTime, '', "class='list-group-item' id='byaddedtime'");
      echo html::a(inlink('obtain', 'type=byDownloads'),   $lang->extension->byDownloads, '', "class='list-group-item' id='bydownloads'");
      ?>
  </div>
  <div class='panel panel-sm'>
    <div class='panel-heading'><?php echo $lang->extension->byCategory;?></div>
    <div class='panel-body'>
      <?php $moduleTree ? print($moduleTree) : print($lang->extension->errorGetModules);?>
    </div>
  </div>
</div>
<div class='main'>
  <?php if($extensions):?>
  <div class='cards pd-0 mg-0'>
  <?php foreach($extensions as $extension):?>
    <?php 
    $currentRelease = $extension->currentRelease;
    $latestRelease  = isset($extension->latestRelease) ? $extension->latestRelease : '';
    ?>
    <div class='card'>
      <div class='card-heading'>
        <small class='pull-right text-important'>
          <?php 
          if($latestRelease and $latestRelease->releaseVersion != $currentRelease->releaseVersion) 
          {
              printf($lang->extension->latest, $latestRelease->viewLink, $latestRelease->releaseVersion, $latestRelease->zentaoCompatible);
          }?>
        </small>
        <h5 class='mg-0'><?php echo $extension->name . "($currentRelease->releaseVersion)";?></h5>
      </div>
      <div class='card-content text-muted'>
        <?php echo $extension->abstract;?>
      </div>
      <div class='card-actions'>
        <div style='margin-bottom: 10px'>
          <?php
          echo "{$lang->extension->author}:     {$extension->author} ";
          echo "{$lang->extension->downloads}:  {$extension->downloads} ";
          echo "{$lang->extension->compatible}: {$lang->extension->compatibleList[$currentRelease->compatible]} ";
          
          echo " {$lang->extension->depends}: ";
          if(!empty($currentRelease->depends))
          {
              foreach(json_decode($currentRelease->depends, true) as $code => $limit)
              {
                  echo $code;
                  if($limit != 'all')
                  {
                      echo '(';
                      if(!empty($limit['min'])) echo '>= v' . $limit['min'];
                      if(!empty($limit['max'])) echo '<= v' . $limit['min'];
                      echo ')';
                  }
                  echo ' ';
              }
          }
          ?>
        </div>
        <?php
          echo "{$lang->extension->grade}: ",   html::printStars($extension->stars);
        ?>
        <div class='pull-right' style='margin-top: -15px'>
          <div class='btn-group'>
          <?php
          $installLink = inlink('install',  "extension=$extension->code&downLink=" . helper::safe64Encode($currentRelease->downLink) . "&md5={$currentRelease->md5}&type=$extension->type&overridePackage=no&ignoreCompitable=yes");
          echo html::a($extension->viewLink, $lang->extension->view, '', 'class="btn extension"');
          if($currentRelease->public)
          {
              if($extension->type != 'computer' and $extension->type != 'mobile')
              {
                  if(isset($installeds[$extension->code]))
                  {
                      if($installeds[$extension->code]->version != $extension->latestRelease->releaseVersion and $this->extension->checkVersion($extension->latestRelease->zentaoCompatible))
                      {
                          $upgradeLink = inlink('upgrade',  "extension=$extension->code&downLink=" . helper::safe64Encode($currentRelease->downLink) . "&md5=$currentRelease->md5&type=$extension->type");
                          echo html::a($upgradeLink, $lang->extension->upgrade, '', 'class="iframe btn"');
                      }
                      else
                      {
                          echo html::commonButton("<i class='icon-ok'></i> " . $lang->extension->installed, "disabled='disabled'", 'text-success');
                      }
                  }
                  else
                  {
                      $label = $currentRelease->compatible ? $lang->extension->installAuto : $lang->extension->installForce;
                      echo html::a($installLink, $label, '', 'class="iframe btn"');
                  }
              }
          }
          echo html::a($currentRelease->downLink, $lang->extension->downloadAB, '', 'class="manual btn"');
          echo html::a($extension->site, $lang->extension->site, '_blank', 'class=btn');
          ?>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach;?>
  </div>
  <?php if($pager):?>
  <div class='clearfix'>
    <?php $pager->show()?>
  </div>
  <?php endif; ?>
  <?php else:?>
  <div class='alert alert-danger'>
    <i class='icon icon-remove-sign'></i>
    <div class='content'>
      <h4><?php echo $lang->extension->errorOccurs;?></h4>
      <div><?php echo $lang->extension->errorGetExtensions;?></div>
    </div>
  </div>
  <?php endif;?>
</div>
<script>
$('#<?php echo $type;?>').addClass('active')
$('#module<?php echo $moduleID;?>').addClass('active')
</script>
<?php include '../../common/view/footer.html.php';?>
