#!/usr/bin/env php
<?php
/**
title=测试 pivotModel->getByID();
cid=1
pid=1

当前语言默认为中文，当字段的对象fieldObject和自定义的词条lang为空的时候，返回field本身。    >> id
当前语言默认为中文，当字段的对象fieldObject和自定义的词条lang为空的时候，返回field本身。    >> id
当前语言默认为中文，当自定义词条lang为空的时候，加载系统语言，并返回对应的词条。            >> Bug编号
当前语言默认为中文，当自定义词条lang为空，当前系统语言为中文，参数的默认语言为英语的时候，仍然会加载中文语言，并返回对应的词条。 >> Bug编号
测试不同的语言模块是否能正常返回。 >> 编号
如果存在自定义的词条lang以及默认语言为中文，优先返回自定义词条的内容。 >> BugID
如果存在自定义的词条lang以及默认语言为英文，优先返回自定义词条的内容。 >> BugID-en
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

$pivot = new pivotTest();

$lang = array('id' => array( 'zh-ch' => 'BugID', 'en' => 'BugID-en'));

$fieldList = array('id');
$fieldOjbectList = array('bug', 'product');
$relatedFieldList = array('', 'id', 'common');
$clientLang = array('zh-ch','en');
$langList = array(array(), $lang);

r($pivot->getColLabelValue($fieldList[0], $fieldOjbectList[0], $relatedFieldList[0], $clientLang[0], $langList[0])) && p('') && e('id');        //当前语言默认为中文，当字段的对象fieldObject和自定义的词条lang为空的时候，返回field本身。
r($pivot->getColLabelValue($fieldList[0], $fieldOjbectList[0], $relatedFieldList[0], $clientLang[1], $langList[0])) && p('') && e('id');        //当前语言默认为中文，当字段的对象fieldObject和自定义的词条lang为空的时候，返回field本身。
r($pivot->getColLabelValue($fieldList[0], $fieldOjbectList[0], $relatedFieldList[1], $clientLang[0], $langList[0])) && p('') && e('Bug编号');   //当前语言默认为中文，当自定义词条lang为空的时候，加载系统语言，并返回对应的词条
r($pivot->getColLabelValue($fieldList[0], $fieldOjbectList[0], $relatedFieldList[1], $clientLang[1], $langList[0])) && p('') && e('Bug编号');   //当前语言默认为中文，当自定义词条lang为空，当前系统语言为中文，参数的默认语言为英语的时候，仍然会加载中文语言，并返回对应的词条
r($pivot->getColLabelValue($fieldList[0], $fieldOjbectList[1], $relatedFieldList[1], $clientLang[0], $langList[0])) && p('') && e('编号');      //测试不同的语言模块是否能正常返回
r($pivot->getColLabelValue($fieldList[0], $fieldOjbectList[0], $relatedFieldList[0], $clientLang[0], $langList[1])) && p('') && e('BugID');     //如果存在自定义的词条lang以及默认语言为中文，优先返回自定义词条的内容
r($pivot->getColLabelValue($fieldList[0], $fieldOjbectList[0], $relatedFieldList[0], $clientLang[1], $langList[1])) && p('') && e('BugID-en');  //如果存在自定义的词条lang以及默认语言为英文，优先返回自定义词条的内容
