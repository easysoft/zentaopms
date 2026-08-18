<?php
/**
 * The mail view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     testcase
 * @link        https://www.zentao.net
 */
?>
<?php $mailTitle = 'TESTCASE #' . $object->id . ' ' . $object->title;?>
<?php include $this->app->getModuleRoot() . 'common/view/mail.header.html.php';?>
<?php $steps = $this->loadModel('testcase')->getSteps($object->id, $object->version);?>
<tr>
  <td>
    <table cellpadding='0' cellspacing='0' width='600' style='border: none; border-collapse: collapse;'>
      <tr>
        <td style='padding: 10px; background-color: #F8FAFE; border: none; font-size: 14px; font-weight: 500; border-bottom: 1px solid #e5e5e5;'>
          <?php $color = empty($object->color) ? '#333' : $object->color;?>
          <?php echo html::a($domain . helper::createLink('testcase', 'view', "caseID=$object->id", 'html'), $mailTitle, '', "style='color: {$color}; text-decoration: underline;'");?>
        </td>
      </tr>
    </table>
  </td>
</tr>
<tr>
  <td style='padding: 10px; border: none;'>
    <div style='padding-bottom: 5px; font-size: 14px; font-weight: 500; color: #114f8e;'><?php echo $this->lang->testcase->steps;?></div>
    <table cellpadding='0' cellspacing='0' width='600' style='border: 1px solid #e5e5e5; border-collapse: collapse;'>
      <tr>
        <th style='width: 50%; padding: 5px; background-color: #F8FAFE; border: 1px solid #e5e5e5; font-size: 13px; color: #114f8e; text-align: left;'><?php echo $this->lang->testcase->stepDesc;?></th>
        <th style='width: 50%; padding: 5px; background-color: #F8FAFE; border: 1px solid #e5e5e5; font-size: 13px; color: #114f8e; text-align: left;'><?php echo $this->lang->testcase->stepExpect;?></th>
      </tr>
      <?php foreach($steps as $step):?>
      <tr>
        <td style='padding: 5px; border: 1px solid #e5e5e5; vertical-align: top; white-space: pre-wrap;'><span style='white-space: nowrap; color: #91758b; padding-right: 8px; padding-left: <?php echo ($step->grade - 1) * 8 + 5;?>px;'><?php echo $step->name;?></span><?php echo html_entity_decode(str_replace(' ', '&nbsp;', $step->step));?></td>
        <td style='padding: 5px 5px 5px 10px; border: 1px solid #e5e5e5; vertical-align: top; white-space: pre-wrap;'><?php echo html_entity_decode(str_replace(' ', '&nbsp;', $step->expect));?></td>
      </tr>
      <?php endforeach;?>
    </table>
  </td>
</tr>
<?php include $this->app->getModuleRoot() . 'common/view/mail.footer.html.php';?>
