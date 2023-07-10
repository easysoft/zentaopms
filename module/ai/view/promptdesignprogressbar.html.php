<?php
/**
 * The design step bar view file of AI module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianyu Chen <chenjianyu@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<header id="mainMenu" class='design-steps'>
  <?php echo html::backButton("<i class='icon icon-back icon-sm'></i> $lang->goback", '', 'btn btn-info');?>
  <div class='steps'>
    <?php
    $step = preg_replace('/^prompt/', '', $app->methodName);
    $stepSequence = array('assignrole', 'selectdatasource', 'setpurpose', 'settargetform', 'finalize');

    $currentStepIndex = array_search($step, $stepSequence);
    $lastActiveStepIndex = array_search($lastActiveStep, $stepSequence);

    $stepstatus = array();

    foreach($stepSequence as $index => $stepName)
    {
      if($index < $currentStepIndex)
      {
        $stepstatus[$stepName] = 'active';
      }
      elseif($index > $currentStepIndex && $index <= $lastActiveStepIndex + 1)
      {
        $stepstatus[$stepName] = 'clickable';
      }
      else
      {
        $stepstatus[$stepName] = 'disabled';
      }

      if($index == $currentStepIndex)
      {
        $stepstatus[$stepName] = 'current';
      }
    }

    foreach($lang->ai->designStepNav as $stepKey => $stepLang)
    {
      $arrow = '';
      $currentStepStatus = current($stepstatus);
      $nextStepStatus = next($stepstatus);
      if($stepKey != end($stepSequence))
      {
        $arrowClass = $currentStepStatus == $nextStepStatus ||($currentStepStatus == 'clickable' && $nextStepStatus == 'disabled') ? 'outline-arrow' : 'solid-arrow';
        $arrow = "<div class='$arrowClass'></div>";
      }
      $aTag = html::a(inlink("prompt$stepKey", "prompt=$prompt->id"), $stepLang);
      echo "<div class='step $currentStepStatus'>$aTag$arrow</div>";
    }
    ?>
  </div>
  <?php echo html::submitButton("<i class='icon icon-save icon-sm'></i> $lang->save", 'form="mainForm"', 'btn btn-primary');?>
  <script>
    (function()
    {
      const steps = document.getElementsByClassName('steps')[0];
      if(steps)
      {
        for(let step of steps.children)
        {
          step.addEventListener('mouseenter', function (e)
          {
            if(step.lastElementChild?.classList.contains('outline-arrow'))
            {
              step.lastElementChild.classList.remove('outline-arrow');
              step.lastElementChild.classList.add('hover-arrow');
            }

            if(step.previousElementSibling?.lastElementChild?.classList.contains('outline-arrow'))
            {
              step.previousElementSibling.lastElementChild.classList.add('solid-arrow');
            }
          });
          step.addEventListener('mouseleave', function (e)
          {
            if(step.lastElementChild?.classList.contains('hover-arrow'))
            {
              step.lastElementChild.classList.remove('hover-arrow');
              step.lastElementChild.classList.add('outline-arrow');
            }

            if(step.previousElementSibling?.lastElementChild?.classList.contains('outline-arrow') && step.previousElementSibling?.lastElementChild?.classList.contains('solid-arrow'))
            {
              step.previousElementSibling.lastElementChild.classList.remove('solid-arrow');
            }
          });
        }
      }
    })();
  </script>
</header>
