<div id='featurebar'>
  <ul class='nav'>
    <?php
    echo "<li id='allTab'>"         . html::a($this->createLink('testcase', 'browse', "productid=$productID&browseType=all"), $lang->testcase->allCases) . "</li>";
    echo "<li id='needconfirmTab'>" . html::a($this->createLink('testcase', 'browse', "productid=$productID&browseType=needconfirm"), $lang->testcase->needConfirm) . "</li>";

    if(common::hasPriv('testcase', 'groupcase'))
    {
        echo "<li id='groupTab' class='dropdown'>";
        $groupBy  = isset($groupBy) ? $groupBy : '';
        $current  = zget($lang->testcase->groups, isset($groupBy) ? $groupBy : '', '');
        if(empty($current)) $current = $lang->testcase->groups[''];
        echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown'");
        echo "<ul class='dropdown-menu'>";
        foreach ($lang->testcase->groups as $key => $value)
        {
            if($key == '') continue;
            echo '<li' . ($key == $groupBy ? " class='active'" : '') . '>';
            echo html::a($this->createLink('testcase', 'groupCase', "productID=$productID&groupBy=$key"), $value);
        }
        echo '</ul></li>';
    }

    if(common::hasPriv('story', 'zeroCase')) echo "<li id='zerocaseTab'>" . html::a($this->createLink('story', 'zeroCase', "productID=$productID"), $lang->story->zeroCase) . '</li>';
    if($this->methodName == 'browse') echo "<li id='bysearchTab'><a href='#'><i class='icon-search icon'></i>&nbsp;{$lang->testcase->bySearch}</a></li> ";
    ?>
  </ul>
  <div class='actions'>
    <div class='btn-group'>
      <div class='btn-group'>
        <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>
          <i class='icon-download-alt'></i> <?php echo $lang->export ?>
          <span class='caret'></span>
        </button>
        <ul class='dropdown-menu' id='exportActionMenu'>
        <?php 
        $misc = common::hasPriv('testcase', 'export') ? "class='export'" : "class=disabled";
        $link = common::hasPriv('testcase', 'export') ?  $this->createLink('testcase', 'export', "productID=$productID&orderBy=$orderBy") : '#';
        echo "<li>" . html::a($link, $lang->testcase->export, '', $misc) . "</li>";

        $misc = common::hasPriv('testcase', 'exportTemplet') ? "class='export'" : "class=disabled";
        $link = common::hasPriv('testcase', 'exportTemplet') ?  $this->createLink('testcase', 'exportTemplet', "productID=$productID") : '#';
        echo "<li>" . html::a($link, $lang->testcase->exportTemplet, '', $misc) . "</li>";
        ?>
        </ul>
      </div>
      <?php 
      common::printIcon('testcase', 'import', "productID=$productID", '', 'button', '', '', 'export cboxElement iframe');

      $initModule = isset($moduleID) ? (int)$moduleID : 0;
      common::printIcon('testcase', 'batchCreate', "productID=$productID&moduleID=$initModule");
      common::printIcon('testcase', 'create', "productID=$productID&moduleID=$initModule");
      ?>
    </div>
  </div>
  <div id='querybox' class='<?php if($browseType =='bysearch') echo 'show';?>'></div>
</div>

<?php foreach(glob(dirname(dirname(__FILE__)) . "/ext/view/featurebar.*.html.hook.php") as $fileName) include_once $fileName; ?>
