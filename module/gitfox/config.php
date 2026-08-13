<?php
$config->gitfox->createbranch = new stdclass;
$config->gitfox->createbranch->requiredFields = 'branch,ref';

$config->gitfox->createbranchpriv = new stdclass;
$config->gitfox->createbranchpriv->requiredFields = 'name';

$config->gitfox->createtag = new stdclass;
$config->gitfox->createtag->requiredFields = 'tag_name,ref';

$config->gitfox->labelPattern = new stdclass;
$config->gitfox->labelPattern->task  = '/^zentao_task\/\d+$/';
$config->gitfox->labelPattern->bug   = '/^zentao_bug\/\d+$/';
$config->gitfox->labelPattern->story = '/^zentao_story\/\d+$/';

$config->gitfox->actions = array();
$config->gitfox->actions['issue'] = array();

$config->gitfox->zentaoObjectLabel = new stdclass;
$config->gitfox->zentaoObjectLabel->name         = "zentao_%s/%s";
$config->gitfox->zentaoObjectLabel->description  = "%s";

$config->gitfox->zentaoObjectLabel->color = new stdclass;
$config->gitfox->zentaoObjectLabel->color->task  = '#0033CC';
$config->gitfox->zentaoObjectLabel->color->story = '#69D100';
$config->gitfox->zentaoObjectLabel->color->bug   = '#D10069';
$config->gitfox->zentaoObjectLabel->priority     = "0";

$config->gitfox->webhookURL = "%s/api.php?m=gitfox&f=webhook&product=%s&gitfox=%s";

$config->gitfox->skippedFields = new stdclass;
$config->gitfox->skippedFields->issueCreate = array();
$config->gitfox->skippedFields->issueCreate['story'] = array();
$config->gitfox->skippedFields->issueCreate['task']  = array();
$config->gitfox->skippedFields->issueCreate['bug']   = array();

$config->gitfox->maps = new stdclass;
$config->gitfox->maps->task = array();
$config->gitfox->maps->task['name']           = 'title|field|';
$config->gitfox->maps->task['desc']           = 'description|field|';
$config->gitfox->maps->task['openedDate']     = 'created_at|field|datetime';
$config->gitfox->maps->task['assignedTo']     = 'assignee_id|userPairs|';
$config->gitfox->maps->task['lastEditedDate'] = 'updated_at|field|datetime';
$config->gitfox->maps->task['deadline']       = 'due_date|field|date';
$config->gitfox->maps->task['status']         = 'state|configItems|taskStateMap';
$config->gitfox->maps->task['pri']            = 'weight|configItems|taskWeightMap';
$config->gitfox->maps->task['lastEditedBy']   = 'updated_by_id|userPairs|';

$config->gitfox->maps->story = array();
$config->gitfox->maps->story['title']      = 'title|field|';
$config->gitfox->maps->story['spec']       = 'description|fields|verify';
$config->gitfox->maps->story['openedDate'] = 'created_at|field|datetime';
$config->gitfox->maps->story['assignedTo'] = 'assignee_id|userPairs|';
$config->gitfox->maps->story['status']     = 'state|configItems|storyStateMap';
$config->gitfox->maps->story['pri']        = 'weight|configItems|storyWeightMap';

$config->gitfox->maps->bug = array();
$config->gitfox->maps->bug['title']      = 'title|field|';
$config->gitfox->maps->bug['steps']      = 'description|field|';
$config->gitfox->maps->bug['openedDate'] = 'created_at|field|datetime';
$config->gitfox->maps->bug['deadline']   = 'due_date|field|date';
$config->gitfox->maps->bug['assignedTo'] = 'assignee_id|userPairs|';
$config->gitfox->maps->bug['status']     = 'state|configItems|bugStateMap';
$config->gitfox->maps->bug['pri']        = 'weight|configItems|bugWeightMap';

$config->gitfox->taskWeightMap = array();
$config->gitfox->taskWeightMap['1'] = '1';
$config->gitfox->taskWeightMap['2'] = '2';
$config->gitfox->taskWeightMap['3'] = '3';

$config->gitfox->taskStateMap = array();
$config->gitfox->taskStateMap['doing']  = 'opened';
$config->gitfox->taskStateMap['wait']   = 'opened';
$config->gitfox->taskStateMap['closed'] = 'closed';
$config->gitfox->taskStateMap['done']   = 'closed';
$config->gitfox->taskStateMap['cancel'] = 'closed';

$config->gitfox->taskTypesToSync = 'design,devel,request,discuss,ui,affair,misc';

$config->gitfox->storyWeightMap = array();
$config->gitfox->storyWeightMap['1'] = '1';
$config->gitfox->storyWeightMap['2'] = '2';
$config->gitfox->storyWeightMap['3'] = '3';

$config->gitfox->storyStateMap = array();
$config->gitfox->storyStateMap['active']   = 'opened';
$config->gitfox->storyStateMap['resolved'] = 'closed';
$config->gitfox->storyStateMap['closed']   = 'closed';

$config->gitfox->bugWeightMap = array();
$config->gitfox->bugWeightMap['1'] = '1';
$config->gitfox->bugWeightMap['2'] = '2';
$config->gitfox->bugWeightMap['3'] = '3';
$config->gitfox->bugWeightMap['4'] = '4';

$config->gitfox->bugStateMap = array();
$config->gitfox->bugStateMap['active']   = 'opened';
$config->gitfox->bugStateMap['resolved'] = 'closed';
$config->gitfox->bugStateMap['closed']   = 'closed';

$config->gitfox->objectTables = new stdclass;
$config->gitfox->objectTables->story = TABLE_STORY;
$config->gitfox->objectTables->task  = TABLE_TASK;
$config->gitfox->objectTables->bug   = TABLE_BUG;

$config->gitfox->objectTypes = array();
$config->gitfox->objectTypes['']      = '';
$config->gitfox->objectTypes['task']  = '任务';
$config->gitfox->objectTypes['bug']   = 'Bug';
$config->gitfox->objectTypes['story'] = '需求';

$config->gitfox->accessLevel = array();
$config->gitfox->accessLevel['guest']      = 10;
$config->gitfox->accessLevel['reporter']   = 20;
$config->gitfox->accessLevel['developer']  = 30;
$config->gitfox->accessLevel['maintainer'] = 40;
$config->gitfox->accessLevel['owner']      = 50;

$config->gitfox->menus['project'] = 'browseProject';
$config->gitfox->menus['group']   = 'browseGroup';
$config->gitfox->menus['user']    = 'browseUser';

$config->gitfox->escapeUserList = array();

$setLang = common::checkNotCN() ? 'en-US' : 'zh-CN';
$time = time();
$config->gitfox->downloadGitfoxURL = array();
$config->gitfox->downloadGitfoxURL['linux']['amd'] = 'https://pkg.zentao.net/gitfox/'. $config->devops->gitfoxVersion . '/linux-amd64.zip?t=' . $time;
$config->gitfox->downloadGitfoxURL['linux']['arm'] = 'https://pkg.zentao.net/gitfox/'. $config->devops->gitfoxVersion . '/linux-arm64.zip?t=' . $time;
$config->gitfox->downloadGitfoxURL['win']['amd']   = 'https://pkg.zentao.net/gitfox/'. $config->devops->gitfoxVersion . '/windows-amd64.zip?t=' . $time;
$config->gitfox->downloadGitfoxURL['win']['arm']   = 'https://pkg.zentao.net/gitfox/'. $config->devops->gitfoxVersion . '/windows-amd64.zip?t=' . $time;

$config->gitfox->installGitfox = array();
$config->gitfox->installGitfox['linux'] = <<<EOT
#!/bin/bash
INSTALL_DIR="%s"
if [ ! -d "\${INSTALL_DIR}" ]; then
    mkdir -p "\${INSTALL_DIR}"
fi
cd "\${INSTALL_DIR}" || { echo "Error: cd \${INSTALL_DIR} failed"; exit 1; }

GITFOX_ZIP="gitfox_latest.zip"
GITFOX_URL="%s"

if command -v wget >/dev/null 2>&1; then
    wget --no-check-certificate -O "\${GITFOX_ZIP}" "\${GITFOX_URL}"
elif command -v curl >/dev/null 2>&1; then
    curl -k -L -o "\${GITFOX_ZIP}" "\${GITFOX_URL}"
else
    echo "Error: wget or curl is required"
    exit 1
fi

if command -v unzip >/dev/null 2>&1; then
    unzip -j -o "\${GITFOX_ZIP}" -d "\${INSTALL_DIR}"
else
    if command -v apt >/dev/null 2>&1; then
        apt update && apt install -y unzip
    elif command -v yum >/dev/null 2>&1; then
        yum install -y unzip
    elif command -v brew >/dev/null 2>&1; then
        brew install unzip
    else
        echo "Error: unzip is required"
        exit 1
    fi
    unzip -j -o "\${GITFOX_ZIP}" -d "\${INSTALL_DIR}"
fi

rm -f "\${GITFOX_ZIP}"

chmod +x "\${INSTALL_DIR}/gitfox"
GITFOX_LANG={$setLang} "\${INSTALL_DIR}/gitfox" install && echo "GitFox has been installed to \${INSTALL_DIR}"
EOT;

$config->gitfox->installGitfox['win'] = <<<EOT
@echo off
chcp 65001 >nul
setlocal enabledelayedexpansion

set "INSTALL_DIR=%s"
if not exist "%INSTALL_DIR%" (
    mkdir "%INSTALL_DIR%"
)
cd /d "%INSTALL_DIR%" || (
    echo Error: cd "%INSTALL_DIR%" failed
    pause
    exit /b 1
)

set "GITFOX_ZIP=gitfox_latest.zip"
set "GITFOX_URL=%s"

where certutil >nul 2>&1
if %errorlevel% equ 0 (
    certutil -urlcache -split -f "%GITFOX_URL%" "%GITFOX_ZIP%"
) else (
    bitsadmin /transfer "GitFoxInstall" "%GITFOX_URL%" "%cd%\%GITFOX_ZIP%"
)

if not exist "%GITFOX_ZIP%" (
    echo Error: wget or curl is required
    pause
    exit /b 1
)

echo Set objShell = CreateObject("Shell.Application") > "%temp%\unzip.vbs"
echo Set objSource = objShell.NameSpace("%cd%\%GITFOX_ZIP%") >> "%temp%\unzip.vbs"
echo Set objTarget = objShell.NameSpace("%cd%") >> "%temp%\unzip.vbs"
echo objTarget.CopyHere objSource.Items, 20 >> "%temp%\unzip.vbs"
cscript //nologo "%temp%\unzip.vbs"

del /f /q "%GITFOX_ZIP%"

"%INSTALL_DIR%\gitfox.exe" install
EOT;
