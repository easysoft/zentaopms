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

    BUILD_BASIC = "true"
    PUBLISH_ZIP = "true"
    PUBLISH_IMAGE = "true"
    PUBLISH_ZBOX = "true"

    // set to blank for auto-detect from ci.json
    DOWNGRADE_ENABLED = ""
    DOWNGRADE_VERSIONS = "7.2,7.1,7.0"
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
              env.XIM_GROUPS = sh(returnStdout: true,script: 'jq -r .notice.groups < ci.json').trim()

              env.PMS_VERSION = sh(returnStdout: true, script: 'cat ${SRC_ZENTAOEXT_PATH}/VERSION').trim()
              env.BIZ_VERSION = sh(returnStdout: true, script: 'cat ${SRC_ZENTAOEXT_PATH}/BIZVERSION').trim()
              env.MAX_VERSION = sh(returnStdout: true, script: 'cat ${SRC_ZENTAOEXT_PATH}/MAXVERSION').trim()
              env.IPD_VERSION = sh(returnStdout: true, script: 'cat ${SRC_ZENTAOEXT_PATH}/IPDVERSION').trim()
              
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
            environment name:'BUILD_BASIC', value:'true'
          }
          stages {
            stage("make ciCommon") {
              steps {
                container('xuanimbot') {
                  sh 'git config --global --add safe.directory $(pwd)'
                  sh '/usr/local/bin/xuanimbot --title "`echo -n 5byA5aeL5p6E5bu656aF6YGT | base64 --decode`" --url "${BUILD_URL}" --content "Build by Tag ${TAG_NAME}" --debug --custom'
                }
                withCredentials([gitUsernamePassword(credentialsId: 'git-zcorp-cc-jenkins-bot-http',gitToolName: 'git-tool')]) {
                  container('package') {
                    sh 'mkdir ${ZENTAO_RELEASE_PATH} && chown 1000:1000 ${ZENTAO_RELEASE_PATH}'
                    sh 'git config --global pull.ff only'
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
                  def pkgVersionMap = [
                    "pms": ["pmsPack", env.PMS_VERSION, "zentaopms" + env.PMS_VERSION + ".zip"],
                    "biz": ["bizPack", env.BIZ_VERSION, "zentaobiz.zip"],
                    "max": ["maxPack", env.MAX_VERSION, "zentaomax.zip"],
                    "ipd": ["ipdPack", env.IPD_VERSION, "zentaoipd.zip"]
                  ]

                  for (entry in pkgVersionMap.entrySet()) {
                    def SubGroup = entry.value[0]
                    def Zversion = entry.value[1]
                    def FileName = entry.value[2]

                    nexusArtifactUploader(
                      nexusVersion: 'nexus3',
                      protocol: env.ARTIFACT_PROTOCOL,
                      nexusUrl: env.ARTIFACT_HOST,
                      groupId: 'zentao.' + SubGroup + '.' + env.GIT_TAG_BUILD_GROUP + '.source',
                      version: Zversion,
                      repository: env.ARTIFACT_REPOSITORY,
                      credentialsId: env.ARTIFACT_CRED_ID,
                      artifacts: [
                        [artifactId: "zentao",
                          classifier: "source",
                          file: FileName,
                          type: 'zip']
                      ]
                    )
                  }
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
                values "php7.0", "php7.1",  "php7.2_7.4", "k8s.php7.2_7.4", "php8.1", "k8s.php8.1"
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
                      nexusArtifactUploader(
                        nexusVersion: 'nexus3',
                        protocol: env.ARTIFACT_PROTOCOL,
                        nexusUrl: env.ARTIFACT_HOST,
                        groupId: 'zentao.pmsPack' + '.' + env.GIT_TAG_BUILD_GROUP,
                        version: env.PMS_VERSION,
                        repository: env.ARTIFACT_REPOSITORY,
                        credentialsId: env.ARTIFACT_CRED_ID,
                        artifacts: [
                          [artifactId: env.ARTIFACT_NAME,
                            classifier: env.PHPVERSION,
                            file: env.ZENTAO_RELEASE_PATH + '/base.zip',
                            type: 'zip']
                        ]
                      )

                      sh 'mkdir ${OUTPUT_PKG_PATH}/${PMS_VERSION}'
                      sh 'cp ${ZENTAO_RELEASE_PATH}/base.zip ${OUTPUT_PKG_PATH}/${PMS_VERSION}/${ARTIFACT_NAME}-${PMS_VERSION}-${PHPVERSION}.zip'

                      script {
                        // copy php7.2 as php8.0
                        if (env.PHPVERSION=="php7.2_7.4") {
                          sh 'cp ${ZENTAO_RELEASE_PATH}/base.zip ${OUTPUT_PKG_PATH}/${PMS_VERSION}/${ARTIFACT_NAME}-${PMS_VERSION}-php8.0.zip'
                          nexusArtifactUploader(
                            nexusVersion: 'nexus3',
                            protocol: env.ARTIFACT_PROTOCOL,
                            nexusUrl: env.ARTIFACT_HOST,
                            groupId: 'zentao.pmsPack' + '.' + env.GIT_TAG_BUILD_GROUP,
                            version: env.PMS_VERSION,
                            repository: env.ARTIFACT_REPOSITORY,
                            credentialsId: env.ARTIFACT_CRED_ID,
                            artifacts: [
                              [artifactId: env.ARTIFACT_NAME,
                              classifier: 'php8.0',
                              file: env.ZENTAO_RELEASE_PATH + '/base.zip',
                              type: 'zip']
                            ]
                          )
                        }

                        def pkgVersionMap = [
                          "biz": ["bizPack", env.BIZ_VERSION],
                          "max": ["maxPack", env.MAX_VERSION],
                          "ipd": ["ipdPack", env.IPD_VERSION]
                        ]

                        for (entry in pkgVersionMap.entrySet()) {
                          echo "set ExtName to ${entry.key}"
                          def ExtName = entry.key
                          echo "set SubGroup to ${entry.value[0]}"
                          def SubGroup = entry.value[0]
                          echo "set Zversion to ${entry.value[1]}"
                          def Zversion = entry.value[1]

                          nexusArtifactUploader(
                            nexusVersion: 'nexus3',
                            protocol: env.ARTIFACT_PROTOCOL,
                            nexusUrl: env.ARTIFACT_HOST,
                            groupId: 'zentao.' + SubGroup + '.' + env.GIT_TAG_BUILD_GROUP,
                            version: Zversion,
                            repository: env.ARTIFACT_REPOSITORY,
                            credentialsId: env.ARTIFACT_CRED_ID,
                            artifacts: [
                              [artifactId: env.ARTIFACT_NAME,
                                classifier: env.PHPVERSION,
                                file: env.ZENTAO_RELEASE_PATH + '/' + ExtName + '.zip',
                                type: 'zip'],
                              [artifactId: env.ARTIFACT_NAME,
                                classifier: 'update.' + env.PHPVERSION,
                                file: env.ZENTAO_RELEASE_PATH + '/' + ExtName + '.update.zip',
                                type: 'zip'] 
                            ]
                          )
                          sh 'mkdir ${OUTPUT_PKG_PATH}/' + Zversion
                          def moveCmd1 = String.format('mv ${ZENTAO_RELEASE_PATH}/%s.zip ${OUTPUT_PKG_PATH}/%s/${ARTIFACT_NAME}-%s-${PHPVERSION}.zip', ExtName, Zversion, Zversion) 
                          sh moveCmd1

                          def moveCmd2 = String.format('mv ${ZENTAO_RELEASE_PATH}/%s.update.zip ${OUTPUT_PKG_PATH}/%s/${ARTIFACT_NAME}-%s-update.${PHPVERSION}.zip', ExtName, Zversion, Zversion)
                          sh moveCmd2

                        } // End for loop
                      }

                      
                    }
                  } // End upload zip

                  stage("syspack") {
                    when {
                      // buildingTag()
                      environment name: 'GIT_TAG_BUILD_TYPE', value: 'release'
                    }
                    steps{
                      sh 'env | grep GIT_TAG'
                        container('package') {
                          sh '${ZENTAO_BUILD_PATH}/package.sh deb'
                          sh '${ZENTAO_BUILD_PATH}/package.sh rpm'
                        }
                    }
                  }

                  stage("upload syspack") {
                    when {
                      environment name: 'GIT_TAG_BUILD_TYPE', value: 'release'
                    }

                    steps {
                      sh 'mv ${ZENTAO_RELEASE_PATH}/zentao.rpm ${OUTPUT_PKG_PATH}/${PMS_VERSION}/${ARTIFACT_NAME}-${PMS_VERSION}-${PHPVERSION}-1.noarch.rpm'
                      sh 'mv ${ZENTAO_RELEASE_PATH}/zentao.deb ${OUTPUT_PKG_PATH}/${PMS_VERSION}/${ARTIFACT_NAME}_${PMS_VERSION}-${PHPVERSION}-1_all.deb'
                    }
                  } // End upload syspack cn

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
          when {
            environment name:'PUBLISH_ZIP', value:'true'
          }
          steps {
            checkout scmGit(branches: [[name: "master"]],
              extensions: [cloneOption(depth: 2, noTags: false, reference: '', shallow: true)],
              userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: 'https://git.zcorp.cc/devops/zentao-package.git']]
            )
            container('xuanimbot') {
              sh 'env | grep GIT'
              sh 'git config --global --add safe.directory $(pwd)'
              sh './gen_build_report.sh > success.md'
              sh '/usr/local/bin/xuanimbot --title "`echo -n 56aF6YGT5rqQ56CB5YyF5p6E5bu65oiQ5Yqf | base64 --decode`" --url "${RUN_DISPLAY_URL}" --content-file success.md --debug --custom'
            }
          }
        }

        stage("Zbox") {
          when {
            environment name:'PUBLISH_ZBOX', value:'true'
          }

          environment {
            // printf "$PKG_URL_FORMATTER" pmsPack ZenTaoPMS 18.5 ZenTaoPMS-18.5-php8.1.zip
            PKG_URL_FORMATTER = """${sh(
                        returnStdout: true,
                        script: "echo ${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/repository/${ARTIFACT_REPOSITORY}/zentao/%s/`echo ${GIT_TAG_BUILD_GROUP} | tr . /`/%s/%s/%s"
              ).trim()}"""
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
                        container('docker') {
                          sh "sed -i 's/dl-cdn.alpinelinux.org/mirrors.tuna.tsinghua.edu.cn/g' /etc/apk/repositories"
                          sh "apk --no-cache add make bash jq git curl wget libarchive-tools p7zip mariadb-client tzdata"
                          sh "docker run --privileged --rm tonistiigi/binfmt --install all"
                          sh "docker buildx create --name mybuilder --driver docker-container --bootstrap"
                          sh "docker buildx use mybuilder"
                          sh 'curl https://pkg.qucheng.com/files/stacksmith/render-template-1.0.1-10-debian-11-amd64.tar.gz | tar zxf - -C /'
                        }
                      }
                    }

                    stage("Build") {
                      steps {
                        container('docker') {
                          sh 'bash build-zbox.sh en win $PMS_VERSION $BIZ_VERSION $MAX_VERSION'
                          sh 'bash build-zbox.sh en win $BIZ_VERSION $MAX_VERSION'
                          sh 'bash build-zbox.sh en win $MAX_VERSION'
                          sh 'bash build-zbox.sh en win $IPD_VERSION'
                          sh 'bash build-zbox.sh zh-cn win $PMS_VERSION $BIZ_VERSION $MAX_VERSION'
                          sh 'bash build-zbox.sh zh-cn win $BIZ_VERSION $MAX_VERSION'
                          sh 'bash build-zbox.sh zh-cn win $MAX_VERSION'
                          sh 'bash build-zbox.sh zh-cn win $IPD_VERSION'
                          sh 'find release/ -name "*.exe" | xargs chmod +r'
                        }

                        script {
                          def pkgVersionMap = [
                            "pms": ["pmsPack", "${env.PMS_VERSION}"],
                            "biz": ["bizPack", "${env.BIZ_VERSION}"],
                            "max": ["maxPack", "${env.MAX_VERSION}"],
                            "ipd": ["ipdPack", "${env.IPD_VERSION}"]
                          ]

                          for (entry in pkgVersionMap.entrySet()) {
                            echo "key is ${entry.key}"
                            def ExtName = entry.key
                            def SubGroup = entry.value[0]
                            def Zversion = entry.value[1]
                            
                            nexusArtifactUploader(
                              nexusVersion: 'nexus3',
                              protocol: env.ARTIFACT_PROTOCOL,
                              nexusUrl: env.ARTIFACT_HOST,
                              groupId: 'zentao.' + SubGroup + '.' + env.GIT_TAG_BUILD_GROUP,
                              version: Zversion,
                              repository: env.ARTIFACT_REPOSITORY,
                              credentialsId: env.ARTIFACT_CRED_ID,
                              artifacts: [
                                [artifactId: 'ZenTaoPMS',
                                  classifier: 'zbox.win64',
                                  file: './release/zh-cn/' + Zversion + '/ZenTaoPMS-' + Zversion + '-zbox.win64.exe',
                                  type: 'exe'],
                                [artifactId: 'ZenTaoALM',
                                  classifier: 'zbox.win64',
                                  file: './release/en/' + Zversion + '/ZenTaoALM-' + Zversion + '-zbox.win64.exe',
                                  type: 'exe']
                              ]
                            )
                          } // End for loop
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
                        container('docker') {
                          sh "sed -i 's/dl-cdn.alpinelinux.org/mirrors.tuna.tsinghua.edu.cn/g' /etc/apk/repositories"
                          sh "apk --no-cache add make bash jq git curl wget libarchive-tools p7zip mariadb-client tzdata"
                          sh "docker run --privileged --rm tonistiigi/binfmt --install all"
                          sh "docker buildx create --name mybuilder --driver docker-container --bootstrap"
                          sh "docker buildx use mybuilder"
                          sh 'curl https://pkg.qucheng.com/files/stacksmith/render-template-1.0.1-10-debian-11-amd64.tar.gz | tar zxf - -C /'
                        }
                      }
                    }

                    stage("Build") {
                      steps {
                        container('docker') {
                          sh 'bash build-zbox.sh zh-cn linux $PMS_VERSION $BIZ_VERSION $MAX_VERSION $IPD_VERSION'
                          sh 'bash build-zbox.sh en linux $PMS_VERSION $BIZ_VERSION $MAX_VERSION $IPD_VERSION'
                        }

                        script {
                          def pkgVersionMap = [
                            "pms": ["pmsPack", "${env.PMS_VERSION}"],
                            "biz": ["bizPack", "${env.BIZ_VERSION}"],
                            "max": ["maxPack", "${env.MAX_VERSION}"],
                            "ipd": ["ipdPack", "${env.IPD_VERSION}"]
                          ]

                          for (entry in pkgVersionMap.entrySet()) {
                            echo "key is ${entry.key}"
                            def ExtName = entry.key
                            def SubGroup = entry.value[0]
                            def Zversion = entry.value[1]

                            nexusArtifactUploader(
                              nexusVersion: 'nexus3',
                              protocol: env.ARTIFACT_PROTOCOL,
                              nexusUrl: env.ARTIFACT_HOST,
                              groupId: 'zentao.' + SubGroup + '.' + env.GIT_TAG_BUILD_GROUP,
                              version: Zversion,
                              repository: env.ARTIFACT_REPOSITORY,
                              credentialsId: env.ARTIFACT_CRED_ID,
                              artifacts: [
                                [artifactId: 'ZenTaoPMS',
                                  classifier: 'zbox_amd64',
                                  file: './release/zh-cn/' + Zversion + '/amd64/ZenTaoPMS-' + Zversion + '-zbox_amd64.tar.gz',
                                  type: 'tar.gz'],
                                [artifactId: 'ZenTaoPMS',
                                  classifier: 'zbox_arm64',
                                  file: './release/zh-cn/' + Zversion + '/arm64/ZenTaoPMS-' + Zversion + '-zbox_arm64.tar.gz',
                                  type: 'tar.gz'],
                                [artifactId: 'ZenTaoALM',
                                  classifier: 'zbox_amd64',
                                  file: './release/en/' + Zversion + '/amd64/ZenTaoALM-' + Zversion + '-zbox_amd64.tar.gz',
                                  type: 'tar.gz'],
                                [artifactId: 'ZenTaoALM',
                                  classifier: 'zbox_arm64',
                                  file: './release/en/' + Zversion + '/arm64/ZenTaoALM-' + Zversion + '-zbox_arm64.tar.gz',
                                  type: 'tar.gz']
                              ]
                            )
                          } // End for loop
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
                container('xuanimbot') {
                  sh 'git config --global --add safe.directory $(pwd)'
                  sh '/usr/local/bin/xuanimbot --title "`echo -n 56aF6YGT5LiA6ZSu5a6J6KOF5YyF5p6E5bu65oiQ5Yqf | base64 --decode`" --url "${RUN_DISPLAY_URL}" --content-file zbox-success.md --debug --custom'
                }
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
          }

          stages() {
            stage("prepare") {
              steps {
                sh 'env'
                checkout scmGit(branches: [[name: "master"]],
                  extensions: [cloneOption(depth: 2, noTags: false, reference: '', shallow: true)],
                  userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: 'https://git.zcorp.cc/app/zentao.git']]
                )
                container('docker') {
                  sh "sed -i 's/dl-cdn.alpinelinux.org/mirrors.tuna.tsinghua.edu.cn/g' /etc/apk/repositories"
                  sh "apk --no-cache add make bash jq git tzdata"
                  sh "make markdown-init"
                }
              }
            }
            stage("docker pms") {
              environment {
                ZENTAO_URL = """${sh(
                            returnStdout: true,
                            script: "echo ${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/repository/${ARTIFACT_REPOSITORY}/zentao/pmsPack/`echo ${GIT_TAG_BUILD_GROUP} | tr . /`/ZenTaoPMS"
                  ).trim()}"""
              }
              steps {
                sh 'echo ${ZENTAO_URL}'
                container('docker') {
                  withDockerRegistry(credentialsId: 'hub-qucheng-push', url: 'https://' + env.REGISTRY_HOST) {
                    sh "docker buildx create --name mybuilder --driver docker-container --bootstrap"
                    sh "docker buildx use mybuilder"
                    sh 'make build'
                  }
                }
              }
            }

            stage("docker biz") {
              environment {
                ZENTAO_URL = """${sh(
                            returnStdout: true,
                            script: "echo ${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/repository/${ARTIFACT_REPOSITORY}/zentao/bizPack/`echo ${GIT_TAG_BUILD_GROUP} | tr . /`/ZenTaoPMS"
                  ).trim()}"""
              }
              steps {
                container('docker') {
                  withDockerRegistry(credentialsId: 'hub-qucheng-push', url: 'https://' + env.REGISTRY_HOST) {
                    sh "docker buildx create --name mybuilder --driver docker-container --bootstrap"
                    sh "docker buildx use mybuilder"
                    sh 'make build-biz'
                    sh 'make build-biz-k8s'
                  }
                }
                  
              }
            }

            stage("docker max") {
              environment {
                ZENTAO_URL = """${sh(
                            returnStdout: true,
                            script: "echo ${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/repository/${ARTIFACT_REPOSITORY}/zentao/maxPack/`echo ${GIT_TAG_BUILD_GROUP} | tr . /`/ZenTaoPMS"
                  ).trim()}"""
              }
              steps {
                container('docker') {
                  withDockerRegistry(credentialsId: 'hub-qucheng-push', url: 'https://' + env.REGISTRY_HOST) {
                    sh "docker buildx create --name mybuilder --driver docker-container --bootstrap"
                    sh "docker buildx use mybuilder"
                    sh 'make build-max'
                    sh 'make build-max-k8s'
                  }
                }
              }
            }

            stage("docker ipd") {
              environment {
                ZENTAO_URL = """${sh(
                            returnStdout: true,
                            script: "echo ${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/repository/${ARTIFACT_REPOSITORY}/zentao/ipdPack/`echo ${GIT_TAG_BUILD_GROUP} | tr . /`/ZenTaoPMS"
                  ).trim()}"""
              }
              steps {
                container('docker') {
                  withDockerRegistry(credentialsId: 'hub-qucheng-push', url: 'https://' + env.REGISTRY_HOST) {
                    sh "docker buildx create --name mybuilder --driver docker-container --bootstrap"
                    sh "docker buildx use mybuilder"
                    sh 'make build-ipd'
                    sh 'make build-ipd-k8s'
                  }
                }
              }
            }

            stage("Notice Image") {
              steps {
                container('docker') {
                  sh 'make markdown-render > ./report.md'
                }
                container('xuanimbot') {
                  sh 'git config --global --add safe.directory $(pwd)'
                  sh '/usr/local/bin/xuanimbot --title "`echo -n 56aF6YGT6ZWc5YOP5p6E5bu65oiQ5Yqf | base64 --decode`" --url "${RUN_DISPLAY_URL}" --content-file ./report.md --debug --custom'
                }
              }
            }

          }
        } // End Docker Image

      }

      post {
        failure {
          container('xuanimbot') {
            sh 'git config --global --add safe.directory $(pwd)'
            sh '/usr/local/bin/xuanimbot --title "zentaopms build failed" --url "${BUILD_URL}" --content "" --debug --custom'
          }
        }
      }
    } // end publish

  }

}


