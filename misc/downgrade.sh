#!/bin/bash
set -e

phpVer="7.4"
installRector="false"
statisticSyntax="false"
codeRootDir=$PWD
composerDir="$(dirname "$(realpath "$0")")"
outputDir="$PWD/tmp"
reportHtmlFile="$PWD/misc/downgrade-report-tpl.html"

helper() {
  cat << EOF
Usage: $0 -p version dirs...
Arguments:
  -p php版本号，如 7.4
     可以指定多个版本，用逗号分隔，如 7.4,7.0,5.4
  -i 在 misc 目录执行 composer install, 安装 rector
  -r 指定要降级的代码根目录, 默认为当前目录
  -s 校验语法时不中断, 生成统计报告
  -o 降级补丁包输出目录
EOF
}

# 处理命令行参数, 获取要降级的目录
[ $# -eq 0 ] && helper
while getopts "isr:p:o:" opt; do
  case $opt in
    "p")
      phpVer=$OPTARG
      ;;
    "r")
      codeRootDir=$(realpath "$OPTARG")
      ;;
    "i")
      installRector="true"
      ;;
    "s")
      statisticSyntax="true"
      ;;
    "o")
      outputDir=$OPTARG
      ;;
    *)
      helper
      exit 1
      ;;
  esac
done

shift $((OPTIND - 1))
statisticDataFile="$outputDir/statistic.data.json"

if [ "$#" -eq 0 ];then
    echo "need at least one file or directory"
    exit 2
fi

# 处理降级目录路径
downGradeDirs=()
for d in "$@"
do
    targetDir="$codeRootDir/$d"
    test -d "$targetDir" || (echo "$d ($targetDir) is not a directory, abort."; exit 2)
    downGradeDirs+=("${targetDir}")
done
# 结束命令行参数处理

excludeRegexList=(
lib/purifier/HTMLPurifier.autoload-legacy.php
lib/sqlparser/vendor/
lib/phpaes/phpseclib/
)

##################################
# 在非容器环境，返回 cpu 核数减一
# 在容器环境，如未对 cpu 做限制，返回宿主机核数减一，否则按实际限制的 cpu 核数
##################################
quotaFile="/sys/fs/cgroup/cpu.max"
getCpuQuota() {
  if [ -f $quotaFile ];then
    quota=$(cut -d ' ' -f 1 $quotaFile)
    period=$(cut -d ' ' -f 2 $quotaFile)
    if [ "$quota" = "max" ];then
      echo $(($(nproc)-1))
      return
    else
      echo $((quota/period))
      return
    fi
  else
    echo $(($(nproc)-1))
    return
  fi
}

# 检查要降级版本的 php 命令行是否存在，将用于校验语法
existsPHPCli() {
    phpVersion="$1"
    which php"${phpVersion}" >/dev/null
}

#########################################
# 执行降级命令
# 参数:
#   1. 降级版本, 如 7.4, 7.0
#   2. 关闭缓存 (可选项), nocache
#   3. 并行执行 (可选项), parallel
#   * 降级目录列表
# 说明
#   1. 由于降级命令有可能会发生异常，故设置了重试，
#      在函数运行时，缓存目录不变，故重试时会减少部分时间
#   2. 并行执行的个数，根据 cpu 最大核数减一处理，如果是容器环境，按分配的核数
#      并行执行需要安装 parallel 包
##########################################
maxRectorRetries=3
downGradeDir() {
    rectorCmd="rector"

    export PHP_VERSION=${1/./}
    shift

    if [ "$1" = "nocache" ];then
      rectorCmd="rector-nocache"
      shift
    fi

    withParallel="false"
    if [ "$1" = "parallel" ];then
      withParallel="true"
      shift
    fi

    downDirs=("$@")
    echo "set PHP_VERSION to $PHP_VERSION"
    echo "do downgrade on dir ${downDirs[*]}"

    lastDir=$PWD
    cd "$composerDir" || exit
    rectorResult=0

    set +e
    for ((i=0;i<maxRectorRetries;i++)) {
        if [ "$withParallel" = "true" ];then
          quotaCpu=$(getCpuQuota)
          parallel -j "$quotaCpu" -k composer "${rectorCmd}" ::: "${downDirs[@]}"
        else
          composer ${rectorCmd} -- "${downDirs[@]}"
        fi
        
        rectorResult="$?"
        if [ "$rectorResult" -eq 0 ];then
            break
        fi
    }
    set -e

    if [ "$rectorResult" -ne 0 ];then
        echo "Rector failed"
        return $rectorResult
    fi

    cd "$lastDir" || exit
}

############################
# 校验单个 php 文件语法       
# 返回值
#   0 没有语法错误
#   1 语法异常，或是执行时异常
############################
syntaxCheck() {
    phpVersion="$1"
    phpFilePath="$2"

    phpCli=$(which php"${phpVersion}")

    $phpCli -l "$phpFilePath" | grep -v 'No syntax errors detected' && return 1 || return 0
}

########################################
# 确保php文件语法正确
# 第一次校验失败后，对文件所在目录执行一次降级
# 再做第二次校验，如继续失败，返回错误
########################################
ensureSyntaxPassed() {
    phpVersion="$1"
    phpFilePath="$2"
    beforeMD5=$(md5sum "$phpFilePath" | awk '{print $1}')

    if syntaxCheck "$phpVersion" "$phpFilePath";then
        echo "Syntax OK, $phpFilePath"
    else
        downGradeDir "$phpVersion" "nocache" "$(dirname "$phpFilePath")"
        afterMD5=$(md5sum "$phpFilePath" | awk '{print $1}')
        if ! syntaxCheck "$phpVersion" "$phpFilePath";then
            echo "downgrade failed"
            echo "md5 compare (before:$beforeMD5) (after:$afterMD5)"
            if [[ "$statisticSyntax" == "true" ]];then
              syntaxAddErrRecord "$phpVersion" "$phpFilePath"
              return 0
            else
              return 1
            fi
        else
            echo "Syntax OK, $phpFilePath"
        fi
    fi
}

#########################################
# 将变更的文件打包
#  判断条件为文件修改时间晚于脚本开始执行的时间 
#########################################
archiveChangedFiles() {
    phpVersion="$1"
    pointTime=$2
    archiveDir=$3

    find "$archiveDir" -type f -name '*.php' -newermt "$pointTime" -printf "%P\n" > /tmp/changedFiles

    lastDir=$PWD
    cd "$archiveDir" || exit 1
    mkdir -p build/
    test -d "$outputDir" || mkdir -pv "$outputDir"

    downPatchFile="${outputDir}/downgrade-php$phpVersion.tar.gz"
    tar zcf "$downPatchFile" --files-from=/tmp/changedFiles
    echo "Release downgrade patch file: $downPatchFile"

    cd "$lastDir"
}

# 正则匹配要排除的文件，跳过校验
matchExclude() {
  filePath="$1"
  for reg in "${excludeRegexList[@]}"
  do
    if [[ "$filePath" =~ $reg ]];then
      echo "true"
      return
    fi 
  done
  echo "false"
}

#############################
# 对单个 php 版本降级的主函数
# 参数:
#  1. php 版本
#  2. 起始时间, 用于比较降级后变更的文件
#  * 降级目录列表
# 步骤:
#  设置临时缓存目录，执行降级
#  对降级目录里所有 php 文件校验语法，确保通过
#  打包变更文件
########################################
DownGrade() {
    phpVersion="$1"
    pointTime="$2"
    shift 2

    downDirs=("$@")
    RECTOR_CACHE_DIR=$(mktemp -d)
    export RECTOR_CACHE_DIR

    startTime="$(date +%s)"

    if which parallel >/dev/null ;then
      downGradeDir "$phpVersion" parallel "${downDirs[@]}"
    else
      for d in "${downDirs[@]}"
      do
        downGradeDir "$phpVersion" "$d"
      done
    fi

    for f in $(find "${downGradeDirs[@]}" -type f -name '*.php')
    do
        shouldIgnore=$(matchExclude "$f")
        if [ "$shouldIgnore" = "true" ];then
          echo "$f is excluded"
          continue
        fi
        ensureSyntaxPassed "$phpVersion" "$f"
    done

    archiveChangedFiles "$phpVersion" "$pointTime" "$codeRootDir"
    test -d "$RECTOR_CACHE_DIR" && rm -rf "$RECTOR_CACHE_DIR"

    endTime="$(date +%s)"
    echo  "downgrade to ${phpVersion} cost $((endTime-startTime)) seconds"
    echo
}

syntaxInitReport() {
  echo '{"versions": {}}' > "$statisticDataFile"
}

syntaxAddErrRecord() {
  phpVersion="$1"
  phpFilePath="$2"

  phpCli=$(which php"${phpVersion}")
  listKey=".versions[\"$phpVersion\"]"

  fileName=$(echo "$phpFilePath"| sed "s|${codeRootDir}/||")
  
  lastDir=$PWD
  cd "$codeRootDir" || exit 1

  set +e
  errMsg=$($phpCli -l "$fileName" | jq -Rs '.')
  set -e

  cd "$lastDir"

  jq "$listKey += [\"$fileName\", $errMsg]" "$statisticDataFile" > "$statisticDataFile.tmp"
  mv "$statisticDataFile.tmp" "$statisticDataFile"
}

preProcess() {
  # 初始化语法错误记录
  if [[ "$statisticSyntax" == "true" ]];then
    syntaxInitReport
  fi

  # 执行 composer 组件安装
  if [ "$installRector" = "true" ];then
    lastDir=$PWD
    cd "$composerDir" || exit 1
    
    composer config -g repo.packagist composer https://mirrors.cloud.tencent.com/composer/
    composer install

    cd "$lastDir" || exit 1
  fi
}

process() {
  # 设置脚本启动时间
  startPointTime=$(date +'%Y-%m-%d %H:%M:%S')

  # 检查 -p 参数, 执行多版本降级或是单版本降级
  if [[ "$phpVer" =~ ^.*, ]];then
      IFS=',' read -r -a verList <<< "$phpVer"
      for v in "${verList[@]}"
      do
          existsPHPCli "$v" || (echo "php${v} is not found, abort."; exit 3)
      done

      for v in "${verList[@]}"
      do
          DownGrade "$v" "$startPointTime" "${downGradeDirs[@]}"
      done
  else
      existsPHPCli "$phpVer" || (echo "php${phpVer} is not found, abort."; exit 3)
      DownGrade "$phpVer" "$startPointTime" "${downGradeDirs[@]}"
  fi
}

postProcess() {
  if [[ "$statisticSyntax" == "true" ]];then
    foundSyntaxErr="false"
    for ver in $(jq -r '.versions | keys[]' "$statisticDataFile")
    do
      count=$(jq -r ".versions[\"$ver\"] | length" < "$statisticDataFile")
      if [[ "$count" -gt 0 && "$foundSyntaxErr" != "true" ]];then
        foundSyntaxErr=true
      fi
    done

    if [[ "$foundSyntaxErr" == "true" ]];then
      reportInsertLine=$(grep -n 'const jsonData' "$reportHtmlFile" | cut -d: -f1)
      jq -c . "$statisticDataFile" | sed "${reportInsertLine}r /dev/stdin" "$reportHtmlFile" > "$outputDir/downgradeReport.html"
    fi
  fi
}

preProcess
process
postProcess

# syntaxCheck "$phpVer" /code/module/pipeline/model.php
# downGradeDir "$phpVer" "${downGradeDirs[@]}"
# archiveChangedFiles "$t" "$codeRootDir"
