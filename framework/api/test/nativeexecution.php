<?php
/* php framework/api/test/nativeexecution.php; no database, actual entry methods. */
define('TABLE_TESTTASK','testtask');define('TABLE_TESTRUN','testrun');define('TABLE_CASE','case');define('TABLE_TESTRESULT','testresult');
class EndResponseException extends RuntimeException { static function create($s) {return new self(json_encode($s));} }
class commonModel {static $allow=true; static function hasPriv($m,$a) {return self::$allow;} }
class fakeDAO {
 public $dbh,$runs=array(),$criteria=array(),$table,$lastField,$result=false,$commits=0,$rollbacks=0;
 function __construct() {$this->dbh=new class {function prepare($q){return new class{function execute($v){return true;}};}};}
 function begin(){} function commit(){$this->commits++;} function rollback(){$this->rollbacks++;}
 function select($s){$this->criteria=array();return $this;} function from($t){$this->table=$t;return $this;}
 function where($s){$this->lastField=trim($s,'`');return $this;}function andWhere($s){return $this->where($s);}function eq($v){$this->criteria[$this->lastField]=$v;return $this;}function orderBy($x){return $this;}
 function fetch($field=null){if($this->table==='testresult')return $this->result; foreach($this->runs as $r){$ok=true;foreach($this->criteria as $k=>$v)if($r->$k!=$v)$ok=false;if($ok)return $r;}return false;}
}
class entry {
 public $app,$dao,$requestBody,$models,$post=array(),$data;
 function __construct(){
  $this->dao=new fakeDAO();$this->requestBody=(object)array();
  $this->app=new class {public $allow=true,$action='post',$user,$session;function __construct(){$this->user=(object)array('account'=>'tester');$this->session=new class {function set($k,$v){}};}function checkObjectPriv($o,$t){return $this->allow;}};
  $this->models=array();$this->data=(object)array('result'=>'success');
 }
 function send($c,$d){return array('code'=>$c,'data'=>$d);}function sendError($c,$m){return $this->send($c,array('error'=>$m));}function send400($m){return $this->sendError(400,$m);}function send404(){return $this->sendError(404,'not found');}
 function param($k,$d=''){return $_GET[$k]??$d;}function request($k,$d=''){return $this->requestBody->$k??$d;}
 function setParam($k,$v){$_GET[$k]=$v;}function setPost($k,$v){$this->post[$k]=$v;}function loadModel($m){return $this->models[$m];}
 function getData(){return $this->data;}
 function loadController($m,$a){return new class($this){public $testtask;private $e;function __construct($e){$this->e=$e;$this->testtask=$e->models['testtask'];}function runCase($run,$case,$version){$this->testtask->lastCreatedResultID=700;$this->e->dao->result=(object)array('id'=>700,'run'=>$run,'case'=>$case,'version'=>$version);}};}
}
require dirname(__DIR__,3).'/api/v1/entries/testtaskcases.php';require dirname(__DIR__,3).'/api/v1/entries/testtaskresults.php';
function fixture($class){
 $_GET=array();commonModel::$allow=true;$e=new $class();
 $e->models['testtask']=new class($e->dao){public $task,$calls=0,$lastCreatedResultID=0;private $dao;function __construct($d){$this->dao=$d;$this->task=(object)array('id'=>1,'product'=>10,'project'=>2,'execution'=>3,'branch'=>'0','status'=>'doing','deleted'=>0);}function getByID($id){return $id===1?$this->task:false;}function linkCase($id,$type,$runs){$this->calls++;foreach($runs as $r){$r=clone $r;$r->id=100+count($this->dao->runs);$r->task=$id;$r->lastRunResult='';$this->dao->runs[]=$r;}return true;}};
 $e->models['testcase']=new class{public $cases;function __construct(){$this->cases=array(5=>(object)array('id'=>5,'product'=>10,'version'=>3,'deleted'=>0,'branch'=>0,'steps'=>array((object)array('id'=>51,'type'=>'group'),(object)array('id'=>52,'type'=>'step'))),6=>(object)array('id'=>6,'product'=>999,'version'=>1,'deleted'=>0,'steps'=>array((object)array('id'=>61,'type'=>'step'))));}function getByID($id,$v=0){$c=isset($this->cases[$id])?clone $this->cases[$id]:false;if($c&&$v>$c->version)$c->steps=array();return $c;}};
 return $e;
}
$n=0;function check($b,$s){global $n;if(!$b)throw new RuntimeException($s);$n++;echo "PASS $s\n";}
function errorCode($cb,$code){try{$r=$cb();return $r['code']===$code;}catch(EndResponseException $x){return json_decode($x->getMessage(),true)['code']===$code;}}
$e=fixture('testtaskcasesEntry');$e->requestBody=(object)array('cases'=>array((object)array('case'=>5,'version'=>2)));
$r=$e->post(1);check($r['code']===200&&$r['data']['runs'][0]->version===2,'explicit historical version, native run');
$e->dao->runs[0]->lastRunResult='fail';$id=$e->dao->runs[0]->id;$r=$e->post(1);check($r['data']['runs'][0]->id===$id&&$r['data']['runs'][0]->lastRunResult==='fail'&&$e->models['testtask']->calls===1,'same association preserves ID and result');
$e->requestBody->cases[0]->version=3;check(errorCode(fn()=>$e->post(1),409),'different linked version rejected');
$e=fixture('testtaskcasesEntry');$e->requestBody=(object)array('cases'=>array((object)array('case'=>5,'version'=>3),(object)array('case'=>6,'version'=>1)));check(errorCode(fn()=>$e->post(1),400)&&!$e->dao->runs,'whole batch validates before write');
foreach(array(0,-1,'1x',1.5,array(),true) as $id){check(errorCode(fn()=>$e->post($id),400),'invalid ID rejected');}
commonModel::$allow=false;check(errorCode(fn()=>$e->post(1),403),'native method privilege required');commonModel::$allow=true;
$e->app->allow=false;check(errorCode(fn()=>$e->post(1),403),'object permission required');
$e=fixture('testresultsEntry');$e->dao->runs=array((object)array('id'=>101,'task'=>1,'case'=>5,'version'=>2));$e->requestBody=(object)array('testtask'=>1,'version'=>2,'steps'=>array((object)array('result'=>'fail','real'=>'synthetic actual')));
$r=$e->post(5);check($r['code']===200&&$r['data']['runID']===101&&$r['data']['version']===2&&$r['data']['resultID']===700,'JSON task/version honored and ID returned');check($e->dao->criteria['id']===700,'select the exact inserted result rather than newest concurrent result');check(array_keys($e->post['result'])===array(52),'group step excluded from executable results');
$e->requestBody->version=3;check(errorCode(fn()=>$e->post(5),409),'mismatch does not execute latest version');
$e->requestBody->version=2;$_GET['testtask']=2;check(errorCode(fn()=>$e->post(5),400),'query/body conflict rejected');$_GET=array();
$e->requestBody->steps[]=clone $e->requestBody->steps[0];check(errorCode(fn()=>$e->post(5),400),'extra steps rejected');array_pop($e->requestBody->steps);
$e->requestBody->steps[0]->result='unknown';check(errorCode(fn()=>$e->post(5),400),'invalid result rejected');
$e->requestBody->steps[0]->result='pass';$e->dao->runs=array();check(errorCode(fn()=>$e->post(5),409),'unlinked task never silently run0');
$e->requestBody->testtask=0;$e->requestBody->version=3;$r=$e->post(5);check($r['code']===200&&$r['data']['runID']===0,'explicit standalone results remain supported');
$e=fixture('testtaskresultsEntry');$e->dao->runs=array((object)array('id'=>101,'task'=>1,'case'=>5,'version'=>2));$e->requestBody=(object)array('version'=>2,'steps'=>array((object)array('result'=>'pass','real'=>'synthetic')));$r=$e->post(1,5);check($r['code']===200&&$r['data']['runID']===101,'path-bound native result');
$e=fixture('testresultsEntry');$e->requestBody=(object)array('testtask'=>null);check(errorCode(fn()=>$e->post(5),400),'null task does not become standalone');
$e->requestBody=(object)array('version'=>null);check(errorCode(fn()=>$e->post(5),400),'null version rejected');
echo "$n assertions passed\n";
