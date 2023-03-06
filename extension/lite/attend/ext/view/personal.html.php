<?php
/**
 * The personal view file of attend module of Ranzhi.
 *
 * @copyright   Copyright 2009-2018 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      liyuchun <liyuchun@cnezsoft.com>
 * @package     attend
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<style>
#menuActions{float:right !important; margin-top: -60px !important;}
.input-group-required > .required::after, .required-wrapper.required::after {top:12px !important;}
.modal-body .table {margin-bottom:0px !important;}
</style>
<div id='featurebar'>
  <ul class='nav'>
  <?php
  $methodName = strtolower($this->app->getMethodName());
  foreach($lang->attend->featureBar['personal'] as $type => $name)
  {
      $class = strtolower($type) == $methodName ? "class='active'" : '';
      if(common::hasPriv('attend', $type)) echo "<li id='$type' $class>" . html::a($this->createLink('attend', $type), $name) . '</li>';
  }
  ?>
  </ul>
</div>

<div class='with-side'>
  <div class='side'>
    <div class='panel panel-sm'>
      <div class='panel-body'>
        <ul class='tree' data-ride='tree' data-collapsed='true'>
          <?php foreach($yearList as $year):?>
          <li class='<?php echo $year == $currentYear ? 'active' : ''?>'>
            <?php extCommonModel::printLink('attend', 'personal', "date=$year", $year);?>
            <ul>
              <?php foreach($monthList[$year] as $month):?>
              <li class='<?php echo ($year == $currentYear and $month == $currentMonth) ? 'active' : ''?>'>
                <?php extCommonModel::printLink('attend', 'personal', "date=$year$month", $year . $month);?>
              </li>
              <?php endforeach;?>
            </ul>
          </li>
          <?php endforeach;?>
        </ul>
      </div>
    </div>
  </div>
  <div class='main'>
    <div class='row'>
      <?php
      $weekIndex = 0;
      if($this->config->attend->workingDays > 7)
      {
          $startDate     = strtotime("$currentYear-$currentMonth-01");
          $startDate     = date('w', $startDate) == 0 ? $startDate : strtotime("last Sunday", $startDate);
          $endDate       = strtotime("next month -1 day $currentYear-$currentMonth-01");
          $endDate       = date('w', $endDate) == 6 ? $endDate : strtotime("next Saturday", $endDate);
          $firstDayIndex = 0;
          $lastDayIndex  = 6;
      }
      else
      {
          $startDate     = strtotime("$currentYear-$currentMonth-01");
          $startDate     = date('w', $startDate) == 1 ? $startDate : strtotime("last Monday", $startDate);
          $endDate       = strtotime("next month -1 day $currentYear-$currentMonth-01");
          $endDate       = date('w', $endDate) == 0 ? $endDate : strtotime("next Sunday", $endDate);
          $firstDayIndex = 1;
          $lastDayIndex  = 0;
      }
      ?>
      <?php while($startDate <= $endDate):?>
      <?php $dayIndex = date('w', $startDate);?>
      <?php if($dayIndex == $firstDayIndex):?>
      <div class='col-xs-4'>
        <div class='panel'>
          <div class='panel-body no-padding'>
            <table class="table table-data text-center table-fixed">
              <thead>
                <tr class='text-center'>
                  <th class='w-80px'><?php echo $lang->attend->weeks[$weekIndex];?></th>
                  <th class='w-40px'><?php echo $lang->attend->dayName;?></th>
                  <th title='<?php echo $lang->attend->signIn;?>'><?php echo $lang->attend->signIn;?></th>
                  <th title='<?php echo $lang->attend->signOut;?>'><?php echo $lang->attend->signOut;?></th>
                  <th class='w-100px'><?php echo $lang->actions . '/' . $lang->attend->status;?></th>
                </tr>
              </thead>
      <?php endif;?>
              <?php $currentDate = date('Y-m-d', $startDate);?>
              <?php if(isset($attends[$currentDate])):?>
              <?php $attend = $attends[$currentDate];?>
              <?php $status = $attend->status;?>
              <?php $reason = $attend->reason;?>
              <?php $date   = date('Ymd', $startDate);?>
              <?php $reviewStatus = isset($attend->reviewStatus) ? $attend->reviewStatus : '';?>
              <tr class="text-middle attend-<?php echo $status?> <?php echo (date('m', $startDate) == $currentMonth) ? '' : 'otherMonth'?>" title='<?php echo $lang->attend->statusList[$status]?>'>
                <td><?php echo formatTime($currentDate, DT_DATE1);?></td>
                <td><?php echo $lang->datepicker->abbrDayNames[$dayIndex]?></td>
                <td class='attend-signin'>
                  <?php $signIn = substr($attend->signIn, 0, 5);?>
                  <?php if(strpos(',late,absent,rest,leave,lieu,', ",$status,") !== false) $signIn = $lang->attend->statusList[$status];?>
                  <?php if($status == 'both') $signIn = $lang->attend->statusList['late'];?>
                  <?php echo $signIn;?>
                </td>
                <td class='attend-signout'>
                  <?php $signOut = substr($attend->signOut, 0, 5);?>
                  <?php if(strpos(',early,absent,rest,leave,lieu,', ",$status,") !== false) $signOut = $lang->attend->statusList[$status];?>
                  <?php if($status == 'both') $signOut = $lang->attend->statusList['early'];?>
                  <?php echo $signOut;?>
                </td>
                <td class='attend-actions'>
                  <?php
                  $edit     = $reviewStatus == 'wait' ? $lang->attend->edited    : $lang->attend->edit;
                  $leave    = $reason == 'leave'      ? $lang->attend->leaved    : $lang->attend->leave;
                  $makeup   = $reason == 'makeup'     ? $lang->attend->makeuped  : $lang->attend->makeup;
                  $overtime = $reason == 'overtime'   ? $lang->attend->overtimed : $lang->attend->overtime;
                  $lieu     = $reason == 'lieu'       ? $lang->attend->lieud     : $lang->attend->lieu;
                  $trip     = $reason == 'trip'       ? $lang->attend->triped    : $lang->attend->trip;
                  $egress   = $reason == 'egress'     ? $lang->attend->egress    : $lang->attend->egress;
                  ?>
                  <?php if($attend->hoursList):?>
                  <?php
                  $index       = 1;
                  $statusLabel = '';
                  foreach($attend->hoursList as $status => $hours)
                  {
                      if($index > 1) $statusLabel .= '<br/>';
                      $statusLabel .= $lang->attend->statusList[$status] . $hours . 'h';
                      $index++;
                  }
                  ?>
                  <div class='dropdown text-left'>
                    <a href='javascript:;' data-toggle='dropdown'>
                      <span class='attend-<?php echo $status;?>'><?php echo $statusLabel;?></span>
                      <span class='caret'></span>
                    </a>
                    <ul role='menu' class='dropdown-menu'>
                      <li><?php echo baseHTML::a($this->createLink('attend', 'edit', "date=" . $date), $edit, "data-toggle='modal' data-width='500px'") . "</li>";?>
                    </ul>
                  </div>
                  <?php elseif($status == 'leave'):?>
                  <span class='attend-<?php echo $status;?>'>
                    <?php extCommonModel::printLink('leave', 'create', "date=" . $date, $leave, "data-toggle='modal' data-width='700px'");?>
                  </span>
                  <?php elseif($status == 'overtime'):?>
                  <span class='attend-<?php echo $status;?>'>
                    <?php extCommonModel::printLink('overtime', 'create', "date=" . $date, $overtime, "data-toggle='modal' data-width='700px'");?>
                  </span>
                  <?php elseif($status == 'lieu'):?>
                  <span class='attend-<?php echo $status;?>'>
                  <?php extCommonModel::printLink('lieu', 'create', "date=" . $date, $lieu, "data-toggle='modal' data-width='700px'");?>
                  </span>
                  <?php elseif(strpos(',rest,normal,', ",$status,") === false):?>
                  <?php if($reviewStatus == 'wait' or strpos(',late,early,both,', ",$status,") !== false):?>
                  <?php echo baseHTML::a($this->createLink('attend', 'edit', "date=" . $date), $edit, "data-toggle='modal' data-width='500px'");?>
                  <?php else:?>
                  <div class='dropdown'>
                    <a href='javascript:;' data-toggle='dropdown'><?php echo $lang->actions;?><span class='caret'></span></a>
                    <ul role='menu' class='dropdown-menu'>
                      <?php if($reason == '' or $reason == 'normal')   extCommonModel::printLink('attend',   'edit',   "date=" . $date, $edit,     "data-toggle='modal' data-width='500px'", '', '', 'li');?>
                      <?php if($reason == '' or $reason == 'leave')    extCommonModel::printLink('leave',    'create', "date=" . $date, $leave,    "data-toggle='modal' data-width='700px'", '', '', 'li');?>
                      <?php if($reason == '' or $reason == 'makeup')   extCommonModel::printLink('makeup',   'create', "date=" . $date, $makeup,   "data-toggle='modal' data-width='700px'", '', '', 'li');?>
                      <?php if($reason == '' or $reason == 'overtime') extCommonModel::printLink('overtime', 'create', "date=" . $date, $overtime, "data-toggle='modal' data-width='700px'", '', '', 'li');?>
                      <?php if($reason == '' or $reason == 'lieu')     extCommonModel::printLink('lieu',     'create', "date=" . $date, $lieu,     "data-toggle='modal' data-width='700px'", '', '', 'li');?>
                      <?php if($reason == '' or $reason == 'trip')     extCommonModel::printLink('trip',     'create', "date=" . $date, $trip,     "data-toggle='modal' data-width='700px'", '', '', 'li');?>
                      <?php if($reason == '' or $reason == 'egress')   extCommonModel::printLink('egress',   'create', "date=" . $date, $egress,   "data-toggle='modal' data-width='700px'", '', '', 'li');?>
                    </ul>
                  </div>
                  <?php endif;?>
                  <?php elseif($status == 'rest'):?>
                  <span class='attend-<?php echo $status;?>'>
                    <?php extCommonModel::printLink('overtime', 'create', "date=" . $date, $lang->attend->overtime, "data-toggle='modal' data-width='700px'");?>
                  </span>
                  <?php elseif($status == 'normal'):?>
                  <span class='attend-<?php echo $status;?>'><?php echo $lang->attend->statusList[$status];?></span>
                  <?php endif;?>
                </td>
              </tr>
              <?php else:?>
              <tr class="<?php echo (date('m', $startDate) == $currentMonth) ? '' : 'otherMonth'?>">
                <td><?php echo formatTime($currentDate, DT_DATE1);?></td>
                <td><?php echo $lang->datepicker->abbrDayNames[$dayIndex]?></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <?php endif;?>
      <?php if($dayIndex == $lastDayIndex):?>
            </table>
          </div>
        </div>
        <?php $weekIndex += 1;?>
      </div>
      <?php endif;?>
      <?php $startDate = strtotime('+1 day', $startDate);?>
      <?php endwhile;?>
    </div>
  </div>
</div>
<script>
$(function()
{
    $('.side .has-active-item').addClass('open');
})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
