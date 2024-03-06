<?php
$config->custom->browseStoryConcept->dtable = new stdclass();
$config->custom->browseStoryConcept->dtable->fieldList['default']['type'] = 'id';
$config->custom->browseStoryConcept->dtable->fieldList['default']['sortType'] = false;

if($config->enableER)
{
    $config->custom->browseStoryConcept->dtable->fieldList['ERName']['title']    = $lang->custom->ERConcept;
    $config->custom->browseStoryConcept->dtable->fieldList['ERName']['type']     = 'title';
    $config->custom->browseStoryConcept->dtable->fieldList['ERName']['sortType'] = false;
}

if($config->URAndSR)
{
    $config->custom->browseStoryConcept->dtable->fieldList['URName']['title']    = $lang->custom->URConcept;
    $config->custom->browseStoryConcept->dtable->fieldList['URName']['type']     = 'title';
    $config->custom->browseStoryConcept->dtable->fieldList['URName']['sortType'] = false;
}

$config->custom->browseStoryConcept->dtable->fieldList['SRName']['title']    = $lang->custom->SRConcept;
$config->custom->browseStoryConcept->dtable->fieldList['SRName']['type']     = 'title';
$config->custom->browseStoryConcept->dtable->fieldList['SRName']['sortType'] = false;

$config->custom->browseStoryConcept->dtable->fieldList['actions']['type'] = 'actions';
$config->custom->browseStoryConcept->dtable->fieldList['actions']['menu'] = array('edit', 'delete');
$config->custom->browseStoryConcept->dtable->fieldList['actions']['list'] = $config->custom->browseStoryConcept->actionList;
