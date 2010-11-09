<?php
/**
 * The install module Korean file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install
 * @version     $Id: en.php 993 2010-08-02 10:20:01Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->install->common  = '설치';
$lang->install->next    = '다음';
$lang->install->pre     = '뒤로';
$lang->install->reload  = '새로고침';
$lang->install->error   = '오류';

$lang->install->start            = '설치를 시작합니다';
$lang->install->keepInstalling   = '이 버전을 설치 계속';
$lang->install->seeLatestRelease = '최신 릴리스를 참조하십시오.';
$lang->install->welcome          = '환영합니다 ZenTaoPMS을 사용합니다.';
$lang->install->desc             = <<<EOT
ZenTaoPMS is an opensource project management software licensed under LGPL. It has product manage, project mange, testing mange features, also with organization manage and affair manage.

ZenTaoPMS is developped by PHH and mysql under the zentaophp framework developped by the same team. Through the framework, ZenTaoPMS can be customed and extended very easily.

ZenTaoPMS is developped by <strong class='red'><a href='http://www.cnezsoft.com' target='_blank'>Nature EasySoft Network Tecnology Co.ltd, QingDao, China</a></strong>。
The official website of ZenTaoPMS is <a href='http://www.zentao.net' target='_blank'>http://www.zentao.net</a>

The version of current release is <strong class='red'>%s</strong>。
EOT;


$lang->install->newReleased= "<strong class='red'>Notice</strong>：There is a new version <strong class='red'>%s</strong>, released on %s。";
$lang->install->choice     = '당신은 할 수';
$lang->install->checking   = '시스템 점검';
$lang->install->ok         = '확인 (√)';
$lang->install->fail       = '실패 (×)';
$lang->install->loaded     = '로드됨';
$lang->install->unloaded   = '장전되지 않은';
$lang->install->exists     = '존재';
$lang->install->notExists  = '존재하지';
$lang->install->writable   = '쓰기';
$lang->install->notWritable= '쓰기 권한이 없습니다';
$lang->install->phpINI     = 'PHP는 ini 파일';
$lang->install->checkItem  = '항목';
$lang->install->current    = '현재';
$lang->install->result     = '결과';
$lang->install->action     = '어떻게 고쳐';

$lang->install->phpVersion = 'PHP 버전';
$lang->install->phpFail    = '4.0.2을&gt;해야합니다';

$lang->install->pdo          = 'PDO 확장';
$lang->install->pdoFail      = '편집 php.ini 파일은 PDO의 extsion를로드 파일입니다.';
$lang->install->pdoMySQL     = 'PDO_MySQL 확장';
$lang->install->pdoMySQLFail = '편집 php.ini 파일이 PDO_MySQL extsion를로드 파일입니다.';
$lang->install->tmpRoot      = 'Temp 디렉터리';
$lang->install->dataRoot     = '업로드 디렉토리의.';
$lang->install->mkdir        = '<p>Should creat the directory %s。<br /> Under linux, can try<br /> mkdir -p %s</p>';
$lang->install->chmod        = 'Should change the permission of "%s".<br />Under linux, can try<br />chmod o=rwx -R %s';

$lang->install->settingDB    = '설정 데이터베이스';
$lang->install->webRoot      = 'ZenTaoPMS 경로';
$lang->install->requestType  = 'URL 형식';
$lang->install->requestTypes['GET']       = 'GET';
$lang->install->requestTypes['PATH_INFO'] = '가 PATH_INFO';
$lang->install->dbHost     = '데이터베이스 호스트';
$lang->install->dbHostNote = '로컬 호스트에 연결할 수있다면, 127.0.0.1을 시도';
$lang->install->dbPort     = '호스트 포트';
$lang->install->dbUser     = '데이터베이스 사용자';
$lang->install->dbPassword = '데이터베이스 암호';
$lang->install->dbName     = '데이터베이스 이름';
$lang->install->dbPrefix   = '테이블 접두사';
$lang->install->createDB   = '자동 데이터베이스를 만들';
$lang->install->clearDB    = '지우기 데이터베이스가 존재하는 경우.';

$lang->install->errorConnectDB     = '데이터베이스 연결에 실패했습니다.';
$lang->install->errorCreateDB      = '데이터베이스 생성에 실패했습니다.';
$lang->install->errorCreateTable   = '표 실패를 만듭니다.';

$lang->install->setConfig  = 'config 파일 만들기';
$lang->install->key        = '항목';
$lang->install->value      = '가치';
$lang->install->saveConfig = '저장 설정';
$lang->install->save2File  = '<div class="a-center"><span class="fail">Try to save the config auto, but failed.</span></div>Copy the text of the textareaand save to "<strong> %s </strong>".';
$lang->install->saved2File = 'The config file has saved to "<strong>%s</strong> ".';
$lang->install->errorNotSaveConfig = '还 没有 保存 配置 文件';

$lang->install->getPriv  = '설정 관리자';
$lang->install->company  = '회사 이름';
$lang->install->pms      = 'ZenTaoPMS 도메인';
$lang->install->pmsNote  = '도메인 이름이나 IP 주소 ZenTaoPMS의, 아니 http://를';
$lang->install->account  = '관리자';
$lang->install->password = '관리자 비밀 번호';
$lang->install->errorEmptyPassword = "비워둘 수 없습니다";

$lang->install->success = "성공, ZenTaoPMS으로 로그인하시기 바랍니다 그룹과 권한을 부여 권한을 만들 설치되어 있어야합니다.";

