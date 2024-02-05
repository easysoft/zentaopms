<?php
declare(strict_types=1);
/**
* The docdynamic block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;
?>
<?php if(empty($actions)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<style>
.docdynamic-block .timeline > li .timeline-text {max-width: 600px; display: block; white-space: nowrap; overflow: hidden; text-overflow: clip; max-height: 20px;}
.docdynamic-block .panel-body {padding-top: 0;}
.docdynamic-block .timeline-text {margin-left: -18px;}
.docdynamic-block .label-action {padding: 0 6px;}
.docdynamic-block .label-action + a {padding-left: 6px;}
.timeline > li:before, .timeline > li > a:after, .timeline > li > div:after {position: absolute; left: -20px; display: block; width: 15px; height: 15px; content: ' '; border-radius: 50%;}
.timeline > li > a:after, .timeline > li > div:after {position: absolute; content: ' '; top: 11px; left: -17px; display: block; z-index: 3; width: 9px; height: 9px; background-color: #2e7fff; border-radius: 50%; opacity: 0;}
.timeline > li.active > a:after, .timeline > li.active > div:after {opacity: 1;}
.timeline > li.active:before {top: 8px; left: -30px; width: 15px; z-index: 3; height: 15px; background-color: #FFF; border: none;}
.timeline > li.collected > div:after {background-color: #FFAF65;}
.timeline > li.releaseddoc > div:after {background-color: var(--color-success-500);}
.timeline > li > div:after {left: -27px;}
.timeline > li > div > .timeline-tag, .timeline > li > div > .timeline-text > .label-action {color: #838A9D;}

.timeline-tag-left {padding-left: 115px;}
.timeline > li {position: relative; list-style: none;}
.timeline > li:before {position: absolute; content: ' '; border-radius: 50%; display: block; top: 12px; left: -26px; z-index: 3; width: 7px; height: 7px; background-color: #c4c4c4; border: none; border: 1px solid #c4c4c4;}
.timeline > li > a, .timeline > li > div {display: block; padding: 5px; line-height: 20px;}
.timeline > li + li:after {position: absolute; top: -12px; bottom: 20px; left: -23px; z-index: 1; display: block; content: ' '; border-left: 1px solid #eee;}
.timeline-tag { position: absolute; top: 5px; left: -115px; font-size: 12px; }

</style>
<ul class="timeline timeline-tag-left no-margin">
  <?php
  $i = 0;
  foreach($actions as $action)
  {
      $user  = zget($users, $action->actor);
      if($action->action == 'login' or $action->action == 'logout') $action->objectName = $action->objectLabel = '';
      $class = $action->major ? 'active' : '';
      if(in_array($action->action, array('releaseddoc', 'collected'))) $class .= " {$action->action}";
      echo "<li class='$class'><div>";
      if($action->objectLink) printf($lang->block->dynamicInfo, $action->date, $user, $action->actionLabel, $action->objectLabel, $action->objectLink, $action->objectName, $action->objectName);
      if(!$action->objectLink) printf($lang->block->noLinkDynamic, $action->date, $action->objectName, $user, $action->actionLabel, $action->objectLabel, ' ' . $action->objectName);
      echo "</div></li>";
      $i++;
  }
  ?>
</ul>
<?php endif;?>
<?php
blockPanel(set::className('docdynamic-block'), rawContent());

render();
