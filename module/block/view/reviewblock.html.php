<?php
/**
 * The task block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(empty($reviews)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-hover table-fixed table-fixed-head tablesorter block-tasks <?php if(!$longBlock) echo 'block-sm';?>'>
    <thead class='text-center'>
      <tr>
        <th class='c-id'>             <?php print($lang->idAB);?></th>
        <th class='c-title text-left'><?php print($lang->my->auditField->title);?></th>
        <th class='c-type w-120px'>   <?php print($lang->my->auditField->type);?></th>
        <th class='c-date w-150px'>   <?php print($lang->my->auditField->time);?></th>
        <th class='c-status w-110px'> <?php print($lang->my->auditField->status);?></th>
        <th class='c-actions-1'><?php print($lang->actions)?></th>
      </tr>
    </thead>
    <tbody class='text-center'>
      <?php foreach($reviews as $review):?>
      <?php
      $type = $review->type;
      if($type == 'projectreview') $type = 'review';

      $typeName = '';
      if(isset($lang->{$review->type}->common)) $typeName = $lang->{$review->type}->common;
      if($type == 'story') $typeName = $lang->SRCommon;
      if($review->type == 'projectreview') $typeName = $lang->project->common;
      if(isset($flows[$review->type])) $typeName = $flows[$review->type];

      $statusList = array();
      if(isset($lang->$type->statusList)) $statusList = $lang->$type->statusList;
      if($type == 'attend') $statusList = $lang->attend->reviewStatusList;
      if(!in_array($type, array('story', 'testcase', 'feedback', 'review')) and strpos(",{$config->my->oaObjectType},", ",$type,") === false)
      {
          $statusList = $lang->approval->nodeList;
      }
      ?>
      <tr>
        <td class='c-id'><?php echo $review->id?></td>
        <td class='c-title text-left' title='<?php echo $review->title?>'>
          <?php
          $titleHtml = $review->title;
          if($type == 'attend')
          {
              $titleHtml = html::a($this->createLink($type, 'review', "objectID=$review->id", 'html', true), $review->title, '', "data-toggle='modal'");
          }
          else
          {
              $class = "class='iframe' data-width='90%'";
              if(strpos(",{$config->my->oaObjectType},", ",{$type},") !== false) $class = "data-toggle='modal'";
              $titleHtml = html::a($this->createLink($type, 'view', "objectID=$review->id", 'html', true), $review->title, '', $class);
          }
          echo $titleHtml;
          ?>
        </td>
        <td class='c-type'><?php echo $typeName;?></td>
        <td class='c-time text-left'><?php echo $review->time?></td>
        <td class='c-status'><?php echo zget($statusList, $review->status, '')?></td>
        <td class='c-actions text-left'>
          <?php
          $module = $type;
          $method = 'review';
          $params = "id=$review->id";

          $reviewIcon = '<i class="icon-glasses"></i>';
          $passIcon   = '<i class="icon-check"></i>';
          $rejectIcon = '<i class="icon-close"></i>';

          if($module == 'review')
          {
              $method = 'assess';
              $params = "reviewID=$review->id";
              common::printLink($module, $method, $params, $reviewIcon, '', "class='btn' title='{$lang->review->common}'");
          }
          elseif($module == 'attend')
          {
              common::printLink($module, 'review', "attendID={$review->id}&status=", $reviewIcon, '', "class='btn' data-toggle='modal' title='{$lang->review->common}'", true, true);
          }
          elseif(strpos(",{$config->my->oaObjectType},", ",$module,") !== false)
          {
              common::printLink($module, 'view', $params, $reviewIcon, '', "class='btn' data-toggle='modal' title='{$lang->review->common}'", true, true);
          }
          elseif(!in_array($module, array('story', 'testcase', 'feedback')))
          {
              common::printLink($module, 'approvalreview', $params, $reviewIcon, '', "class='btn' data-toggle='modal' title='{$lang->review->common}'", true, true);
          }
          else
          {
              common::printLink($module, $method, $params, $reviewIcon, '', "class='btn iframe' title='{$lang->review->common}'", true, true);
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
