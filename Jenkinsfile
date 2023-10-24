library 'z-common@master'
library 'z-zentaopms@master'

pipeline {
  agent {
    kubernetes {
      inheritFrom "xuanim"
      yamlFile 'misc/ci/normal.yaml'
    }
  }

  options {
    skipDefaultCheckout()
  }

  environment {
    TZ="Asia/Shanghai"

    ZENTAO_RELEASE_PATH = "${WORKSPACE}/release"
    XUANXUAN_SRC_PATH = "${WORKSPACE}/xuansrc"
    SRC_ZDOO_PATH = "${WORKSPACE}/zdoo"
    SRC_ZDOOEXT_PATH = "${WORKSPACE}/zdooext"
    SRC_ZENTAOEXT_PATH = "${WORKSPACE}/zentaoext"

    MIDDLE_IMAGE_REPO = "hub.qc.oop.cc/zentao-package-ext"
    MIDDLE_IMAGE_TAG = """${sh(
                        returnStdout: true,
                        script: 'date +%Y%m%d%H%M-${BUILD_ID}'
    ).trim()}"""

    MIRROR = "true"

    PUBLISH_ZIP = "true"
    PUBLISH_IMAGE = "true"
    PUBLISH_ZBOX = "true"

    // set to blank for auto-detect from ci.json
    DOWNGRADE_ENABLED = ""
    DOWNGRADE_VERSIONS = ""
  }

  stages {

    stage("Basic Build") {
      when {
        allOf {
          buildingTag()
        }
        beforeAgent true
      }

      agent {
        kubernetes {
          inheritFrom "zentao-package build-docker xuanim"
          yamlFile 'misc/ci/basic-build.yaml'
        }
      }

      stages {
        stage("Pull") {
          steps {
            checkout scm
            script {
              env.XUANVERSION = sh(returnStdout: true,script: 'jq -r .pkg.xuanxuan.gitVersion < ci.json').trim()
              env.ZENTAOEXT_VERSION = sh(returnStdout: true,script: 'jq -r .pkg.zentaoext.gitVersion < ci.json').trim()
              env.ZENTAOEXT_GIT_REPO = sh(returnStdout: true,script: 'jq -r .pkg.zentaoext.gitRepo < ci.json').trim()
              env.ZDOO_VERSION = sh(returnStdout: true,script: 'jq -r .pkg.zdoo.gitVersion < ci.json').trim()
              env.ZDOOEXT_VERSION = sh(returnStdout: true,script: 'jq -r .pkg.zdooext.gitVersion < ci.json').trim()
              
            }

            dir('xuansrc') {
              checkout scmGit(branches: [[name: "${env.XUANVERSION}"]],
                userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: 'https://git.zcorp.cc/easycorp/xuanxuan.git']]
              )
            }

            dir('zdoo') {
              checkout scmGit(branches: [[name: "${env.ZDOO_VERSION}"]],
                extensions: [cloneOption(depth: 2, noTags: false, reference: '', shallow: true)],
                userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: 'https://git.zcorp.cc/easycorp/zdoo.git']]
              )
            }

            dir('zdooext') {
              checkout scmGit(branches: [[name: "${env.ZDOOEXT_VERSION}"]],
                extensions: [cloneOption(depth: 2, noTags: false, reference: '', shallow: true)],
                userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: 'https://git.zcorp.cc/easycorp/zdooext.git']]
              )
            }

            dir('zentaoext') {
              checkout scmGit(branches: [[name: "${env.ZENTAOEXT_VERSION}"]],
                extensions: [cloneOption(depth: 2, noTags: false, reference: '', shallow: true)],
                userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: "${env.ZENTAOEXT_GIT_REPO}"]]
              )
            }
          
          }
        }

        stage("Setup Global Env") {
          steps {
            script {
              env.GIT_URL = sh(returnStdout: true, script: 'git config --get remote.origin.url').trim()
              env.GIT_COMMIT = sh(returnStdout: true, script: 'git rev-parse HEAD').trim()
              env.GIT_TAG_BUILD_TYPE = sh(returnStdout: true, script: 'misc/parse_tag.sh $TAG_NAME type').trim()
              env.GIT_TAG_BUILD_GROUP = sh(returnStdout: true, script: 'misc/parse_tag.sh $TAG_NAME group').trim()
              env.GIT_TAGGER_NAME = sh(returnStdout: true, script: 'git for-each-ref --format="%(taggername)" refs/tags/$(git tag --points-at HEAD)').trim()

              env.CI_PUBLIC_IMAGE_NAMESPACE = sh(returnStdout: true,script: 'jq -r .image.public.namespace.' + env.GIT_TAG_BUILD_TYPE + ' < ci.json').trim()
              env.CI_INTERNAL_IMAGE_NAMESPACE = sh(returnStdout: true,script: 'jq -r .image.internal.namespace.' + env.GIT_TAG_BUILD_TYPE + ' < ci.json').trim()

              def ximUsers = sh(returnStdout: true,script: 'jq -r .notice.users < ci.json').trim()
              env.XIM_USERS = ximUsers + ',' + env.GIT_TAGGER_NAME
              if (env.GIT_TAG_BUILD_TYPE=='release') {
                env.XIM_GROUPS = sh(returnStdout: true,script: 'jq -r .notice.groups2 < ci.json').trim()
              } else {
                env.XIM_GROUPS = sh(returnStdout: true,script: 'jq -r .notice.groups < ci.json').trim()
              }

              env.PMS_VERSION = sh(returnStdout: true, script: 'cat ${SRC_ZENTAOEXT_PATH}/VERSION').trim()
              env.BIZ_VERSION = sh(returnStdout: true, script: 'cat ${SRC_ZENTAOEXT_PATH}/BIZVERSION').trim()
              env.MAX_VERSION = sh(returnStdout: true, script: 'cat ${SRC_ZENTAOEXT_PATH}/MAXVERSION').trim()
              env.IPD_VERSION = sh(returnStdout: true, script: 'cat ${SRC_ZENTAOEXT_PATH}/IPDVERSION').trim()

              env.DOCKER_CREDENTIALS_ID = 'hub-qucheng-push'
              
              env.CI_DOWNGRADE_ENABLED = sh(returnStdout: true, script: 'test -n "${DOWNGRADE_ENABLED}" && echo ${DOWNGRADE_ENABLED} || (jq -r .downgrade.enabled < ci.json)').trim()
              env.QINIU_BUCKET = sh(returnStdout: true, script: 'jq -r .upload.bucket < ci.json').trim()
              env.ARTIFACT_REPOSITORY = sh(returnStdout: true, script: 'misc/parse_tag.sh $TAG_NAME type | grep release >/dev/null && echo easycorp || echo easycorp-snapshot').trim()
              env.ARTIFACT_HOST = "nexus.qc.oop.cc"
              env.ARTIFACT_PROTOCOL = "https"
              env.ARTIFACT_CRED_ID = "nexus-jenkins"
            }

          }
        }

        stage("Build") {
          when {
            environment name:'PUBLISH_ZIP', value:'true'
          }
          stages {
            stage("make ciCommon") {
              steps {
                ximNotify(title: "开始构建禅道", content: "Build by Tag ${env.TAG_NAME}")
                withCredentials([gitUsernamePassword(credentialsId: 'git-zcorp-cc-jenkins-bot-http',gitToolName: 'git-tool')]) {
                  container('package') {
                    sh 'mkdir ${ZENTAO_RELEASE_PATH} && chown 1000:1000 ${ZENTAO_RELEASE_PATH}'
                    sh 'git config --global pull.ff only'
                    sh 'cp -av ${ZENTAO_BUILD_PATH}/adminer www/'
                    sh 'pwd && ls -l && make ciCommon'
                    sh 'ls -l ${ZENTAO_RELEASE_PATH}'
                  }
                }
              }
            }

            stage("zentaoext") {
              steps {
                container('package') {
                  sh 'cd $SRC_ZENTAOEXT_PATH && make'
                  sh 'cp ${ZENTAO_BUILD_PATH}/zentao*.zip ./'
                  sh 'cp ${ZENTAO_BUILD_PATH}/docker/Dockerfile.release.ext ./Dockerfile.release.ext'
                }
                container('docker') {
                  sh 'docker build --pull -t ${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG} -f Dockerfile.release.ext ${ZENTAO_RELEASE_PATH}'
                  sh 'docker push ${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}'
                }
                script {
                  buildPkg.uploadSource()
                }

              }
            }

          }
        } // End Build
      }
    }

    stage("Publish") {
      when {
        allOf {
          buildingTag()
        }
        beforeAgent true
      }

      environment {
        OUTPUT_PKG_PATH = "${ZENTAO_RELEASE_PATH}/output"
      }

      stages {
        stage("Merge and Upload") {
          when {
            environment name:'PUBLISH_ZIP', value:'true'
          }
          matrix {
            agent {
              kubernetes {
                containerTemplate {
                    name "package"
                    image "${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}"
                    command "sleep"
                    args "99d"
                }
                yamlFile 'misc/ci/publish-zip.yaml'
              }
            }
            options {
              skipDefaultCheckout()
            }

            axes {
              axis {
                name "ZLANG"
                values "cn", "en"
              }
              axis {
                name "PHPVERSION"
                values "php5.4_5.6", "php7.0", "php7.1",  "php7.2_7.4", "k8s.php7.2_7.4", "php8.1", "k8s.php8.1"
              }
            }
            excludes {
              exclude {
                axis {
                  name 'ZLANG'
                  values 'en'
                }
                axis {
                  name "PHPVERSION"
                  values "k8s.php7.2_7.4", "k8s.php8.1"
                }
              }
            }

            stages {
              
              stage("ZIP") {
                environment {
                  ARTIFACT_NAME = """${sh(
                            returnStdout: true,
                            script: 'test ${ZLANG} = cn && echo -n ZenTaoPMS || echo -n ZenTaoALM'
                  ).trim()}"""
                  INT_FLAG = """${sh(
                            returnStdout: true,
                            script: 'test ${ZLANG} = cn && echo -n "int." || echo -n ""'
                  ).trim()}"""
                }

                stages {
                  stage("package zip") {
                    steps{
                        echo "${env.ZLANG} <=> ${env.PHPVERSION}"
                        container('package') {
                          sh 'mkdir $ZENTAO_RELEASE_PATH'
                          sh '${ZENTAO_BUILD_PATH}/package.sh zip'
                          sh 'mkdir $OUTPUT_PKG_PATH'
                        }
                    }
                  }

                  stage("upload zip") {
                    steps {
                      sh 'mkdir ${OUTPUT_PKG_PATH}/${PMS_VERSION}'
                      sh 'cp ${ZENTAO_RELEASE_PATH}/base.zip ${OUTPUT_PKG_PATH}/${PMS_VERSION}/${ARTIFACT_NAME}-${PMS_VERSION}-${PHPVERSION}.zip'

                      script {
                        buildPkg.uploadPMS(env.PHPVERSION)
                        // copy php7.2 as php8.0
                        if (env.PHPVERSION=="php7.2_7.4") {
                          buildPkg.uploadPMS('php8.0')
                          sh 'cp ${ZENTAO_RELEASE_PATH}/base.zip ${OUTPUT_PKG_PATH}/${PMS_VERSION}/${ARTIFACT_NAME}-${PMS_VERSION}-php8.0.zip'
                        }
                        buildPkg.uploadExt()
                      }

                      
                    }
                  } // End upload zip

                  stage("syspack") {
                    when {
                      environment name: 'GIT_TAG_BUILD_TYPE', value: 'release'
                    }
                    steps{
                      script {
                        buildPkg.buildRpmAndDeb()
                      }
                    }
                  }

                  stage("Upload Qiniu") {
                    when {
                      environment name: 'GIT_TAG_BUILD_TYPE', value: 'release'
                    }

                    environment {
                      OBJECT_KEY_PREFIX = "zentao/"
                      QINIU_ACCESS_KEY = credentials('qiniu-upload-ak')
                      QINIU_SECRET_KEY = credentials('qiniu-upload-sk')
                    }

                    steps {
                      sh 'ls -l ${OUTPUT_PKG_PATH}'
                      container('jnlp') {
                        sh 'qshell account $QINIU_ACCESS_KEY $QINIU_SECRET_KEY uploader'
                        sh 'qshell qupload2 --bucket $QINIU_BUCKET --overwrite --src-dir $OUTPUT_PKG_PATH --key-prefix $OBJECT_KEY_PREFIX'
                      }    
                    }
                  }

                } // end stages
              } // end stage frame

            } // End matrix stages
          } // End matrix

        } // End Merge and Upload Max

        stage("Notice ZIP") {
          when { environment name:'PUBLISH_ZIP', value:'true' }
          steps {
            checkout scmGit(branches: [[name: "master"]],
              extensions: [cloneOption(depth: 2, noTags: false, reference: '', shallow: true)],
              userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: 'https://git.zcorp.cc/devops/zentao-package.git']]
            )
            container('xuanimbot') {
              sh './gen_build_report.sh > success.md'
            }
            ximNotify(title: "禅道源码包构建成功", contentFile: "success.md")
          }
        }

        stage("Zbox") {
          when { environment name:'PUBLISH_ZBOX', value:'true' }

          environment {
            // printf "$PKG_URL_FORMATTER" pmsPack ZenTaoPMS 18.5 ZenTaoPMS-18.5-php8.1.zip
            PKG_URL_FORMATTER = """${sh(
                        returnStdout: true,
                        script: "echo ${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/repository/${ARTIFACT_REPOSITORY}/zentao/%s/`echo ${GIT_TAG_BUILD_GROUP} | tr . /`/%s/%s/%s"
              ).trim()}"""
            OBJECT_KEY_PREFIX = "zentao/"
            QINIU_ACCESS_KEY = credentials('qiniu-upload-ak')
            QINIU_SECRET_KEY = credentials('qiniu-upload-sk')
          }

          stages {
            stage("Package") {
              parallel {
                stage("Zbox win") {
                  agent {
                    kubernetes {
                      yamlFile 'misc/ci/publish-zbox.yaml'
                    }
                  }

                  stages() {
                    stage("Prepare") {
                      steps {
                        checkout scmGit(branches: [[name: "main"]],
                          extensions: [cloneOption(depth: 2, noTags: false, reference: '', shallow: true)],
                          userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: 'https://git.zcorp.cc/devops/zbox-builder.git']]
                        )
                      }
                    }

                    stage("Build") {
                      steps {
                        script {
                          buildZboxWin.doBuild()
                          buildZboxWin.uploadInternal()
                        }
                      }
                    }

                    stage("Upload Qiniu") {
                      when {
                        environment name: 'GIT_TAG_BUILD_TYPE', value: 'release'
                      }

                      steps {
                        container('docker') {
                          sh 'mkdir -pv ./release/upload && cd ./release/upload && mkdir $PMS_VERSION $BIZ_VERSION $MAX_VERSION $IPD_VERSION'
                          sh "mv `find release/zh-cn/$PMS_VERSION release/en/$PMS_VERSION -type f -name 'ZenTao*.exe'` release/upload/$PMS_VERSION"
                          sh "mv `find release/zh-cn/$BIZ_VERSION release/en/$BIZ_VERSION -type f -name 'ZenTao*.exe'` release/upload/$BIZ_VERSION"
                          sh "mv `find release/zh-cn/$MAX_VERSION release/en/$MAX_VERSION -type f -name 'ZenTao*.exe'` release/upload/$MAX_VERSION"
                          sh "mv `find release/zh-cn/$IPD_VERSION release/en/$IPD_VERSION -type f -name 'ZenTao*.exe'` release/upload/$IPD_VERSION"
                        }
                        container('jnlp') {
                          sh 'qshell account $QINIU_ACCESS_KEY $QINIU_SECRET_KEY uploader'
                          sh 'qshell qupload2 --bucket $QINIU_BUCKET --overwrite --src-dir ./release/upload --key-prefix $OBJECT_KEY_PREFIX'
                        }
                      }
                    } // End Upload Qiniu
                  }
                } // End Zbox win

                stage("Zbox linux") {
                  agent {
                    kubernetes {
                      yamlFile 'misc/ci/publish-zbox.yaml'
                    }
                  }

                  stages() {
                    stage("Prepare") {
                      steps {
                        checkout scmGit(branches: [[name: "main"]],
                          extensions: [cloneOption(depth: 2, noTags: false, reference: '', shallow: true)],
                          userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: 'https://git.zcorp.cc/devops/zbox-builder.git']]
                        )
                      }
                    }

                    stage("Build") {
                      steps {
                        script {
                          buildZboxLinux.doBuild()
                          buildZboxLinux.uploadInternal()
                        }
                      }
                    }

                    stage("Upload Qiniu") {
                      when {
                        environment name: 'GIT_TAG_BUILD_TYPE', value: 'release'
                      }

                      steps {
                        container('docker') {
                          sh 'mkdir -pv ./release/upload && cd ./release/upload && mkdir $PMS_VERSION $BIZ_VERSION $MAX_VERSION $IPD_VERSION'
                          sh "mv `find release/zh-cn/$PMS_VERSION release/en/$PMS_VERSION -type f -name 'ZenTao*'` release/upload/$PMS_VERSION"
                          sh "mv `find release/zh-cn/$BIZ_VERSION release/en/$BIZ_VERSION -type f -name 'ZenTao*'` release/upload/$BIZ_VERSION"
                          sh "mv `find release/zh-cn/$MAX_VERSION release/en/$MAX_VERSION -type f -name 'ZenTao*'` release/upload/$MAX_VERSION"
                          sh "mv `find release/zh-cn/$IPD_VERSION release/en/$IPD_VERSION -type f -name 'ZenTao*'` release/upload/$IPD_VERSION"
                        }
                        container('jnlp') {
                          sh 'qshell account $QINIU_ACCESS_KEY $QINIU_SECRET_KEY uploader'
                          sh 'qshell qupload2 --bucket $QINIU_BUCKET --overwrite --src-dir ./release/upload --key-prefix $OBJECT_KEY_PREFIX'
                        }
                      }
                    } // End Upload Qiniu
                  }
                } // End Zbox linux

              } // End parallel
            }

            stage("Notice zbox") {
              steps {
                checkout scmGit(branches: [[name: "main"]],
                  extensions: [cloneOption(depth: 2, noTags: false, reference: '', shallow: true)],
                  userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: 'https://git.zcorp.cc/devops/zbox-builder.git']]
                )
                sh 'script/lib/gen_report.sh > zbox-success.md'
                ximNotify(title: "禅道一键安装包构建成功", contentFile: "zbox-success.md")
              }
            }
          }

        } // End Zbox

        stage("Docker Image") {
          when {
            environment name:'PUBLISH_IMAGE', value:'true'
          }

          agent {
            kubernetes {
              inheritFrom "dind xuanim"
              yamlFile 'misc/ci/publish-image.yaml'
            }
          }

          environment {
            REGISTRY_HOST="hub.zentao.net"
            CI_BUILD_PUBLIC_IMAGE="true"
            PKG_URL_FORMATTER = """${sh(
                        returnStdout: true,
                        script: "echo ${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/repository/${ARTIFACT_REPOSITORY}/zentao/%s/`echo ${GIT_TAG_BUILD_GROUP} | tr . /`/%s/%s/%s"
              ).trim()}"""
          }

          steps {
            checkout scmGit(branches: [[name: "feature-230927"]],
                  extensions: [cloneOption(depth: 2, noTags: false, reference: '', shallow: true)],
                  userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: 'https://git.zcorp.cc/app/zentao.git']]
                )
            script {
              dockerBuildx(host=env.REGISTRY_HOST, credentialsId=env.DOCKER_CREDENTIALS_ID) {
                stage("prepare") {
                  sh "apk --no-cache add make bash jq git tzdata"
                  sh "make markdown-init"
                }
                stage("docker pms") {
                  sh 'make build'
                }
                stage("docker biz") {
                  sh 'make build-biz'
                  sh 'make build-biz-k8s'
                }
                stage("docker max") {
                  sh 'make build-max'
                  sh 'make build-max-k8s'
                }
                stage("docker ipd") {
                  sh 'make build-ipd'
                  sh 'make build-ipd-k8s'
                }
                sh 'make markdown-render > ./report.md'
              }
            }
            ximNotify(title: "禅道镜像构建成功", contentFile: "report.md")
          }

        } // End Docker Image

        stage("Upload rongpm") {
          agent {
            kubernetes {
              inheritFrom "zentao-package xuanim"
              yamlFile 'misc/ci/normal.yaml'
            }
          }
          when {
            environment name:'PUBLISH_ZIP', value:'true'
            beforeAgent true
          }

          environment {
            OBJECT_KEY_PREFIX = "zentao/"
            QINIU_ACCESS_KEY = credentials('qiniu-upload-ak')
            QINIU_SECRET_KEY = credentials('qiniu-upload-sk')
          }

          steps {
            script {
              if (env.GIT_TAG_BUILD_TYPE=='release') {
                checkout scmGit(branches: [[name: "master"]],
                  extensions: [cloneOption(depth: 2, noTags: false, reference: '', shallow: true)],
                  userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: 'https://git.zcorp.cc/web/rongpm.git']]
                )

                container('package') {
                  sh 'mkdir -p output/rongpm/${PMS_VERSION}'
                  sh 'cd output/rongpm/${PMS_VERSION} && php7.2 $WORKSPACE/system/bin/buildpractice.php && rm -rf rongpm'
                }

                container('jnlp') {
                  sh 'qshell account $QINIU_ACCESS_KEY $QINIU_SECRET_KEY uploader'
                  sh 'qshell qupload2 --bucket $QINIU_BUCKET --overwrite --src-dir ./output --key-prefix $OBJECT_KEY_PREFIX'
                }
              }
            }
          }
        } // End Upload rongpm

      }

      
    } // end publish

  }

  post {
    failure {
      ximNotify(title: "禅道构建失败", content: "请点击查看详情")
    }
  }

}


