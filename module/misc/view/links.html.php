<style>
#zentaoLinks .col-sm-2{width:25%;}
#zentaoLinks .col-sm-2 .others ul{padding-left: 0px;}
#zentaoLinks .col-sm-2 .others li{list-style: none; text-align:left;}
#zentaoLinks .col-sm-2 .others img{height:25px; padding-right:5px;}
</style>
<div class='container mw-900px' id="zentaoLinks">
  <div class='row'>
    <?php foreach($lang->misc->zentao as $label => $groupItems):?>
    <?php if(strpos(',labels,icons,version,others,', ",$label,") !== false) continue; ?>
    <div class='col-sm-2'>
        <div class='panel <?php echo $label;?>'>
        <div class='panel-heading'>
          <div class="panel-title"><?php echo $lang->misc->zentao->labels[$label];?></div>
        </div>
        <div class='panel-body'>
          <ul>
            <?php $api = ($this->app->getClientLang() == 'en') ? $config->misc->enApi : $config->misc->api;?>
            <?php foreach($groupItems as $item => $label):?>
            <?php $bizLink = ($this->app->getClientLang() == 'en') ? 'https://www.zentao.pm/' : 'https://www.zentao.net/page/enterprise.html';?>
            <?php $link    = $item == 'bizversion' ? $bizLink : $api . "/goto.php?item=$item&from=about";?>
            <li><?php echo html::a($link, $label, '_blank', "id='$item'");;?></li>
            <?php endforeach;?>
          </ul>
        </div>
      </div>
    </div>
    <?php endforeach;?>
  </div>
</div>
<style>
#bizversion, #zentaotrain {color: #ff5d5d}
#zentaoLinks .panel {box-shadow: 0 2px 4px 0 rgba(240,242,246,0.50); border: 1px solid #eee; min-height: 230px;}
#zentaoLinks .panel-heading {background: #eeeeee; color: #666;}
#zentaoLinks .panel-body {padding: 10px;}
#zentaoLinks ul {}
#zentaoLinks ul > li {line-height: 24px;}
</style>
