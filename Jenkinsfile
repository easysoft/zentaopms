pipeline {
  agent {
    kubernetes {
      inheritFrom "xuanim"
    }
  }

  options {
    skipDefaultCheckout()
  }

  environment {
    ZENTAO_RELEASE_PATH = "${WORKSPACE}/release"

    XUANXUAN_SRC_PATH = "${WORKSPACE}/xuansrc"
    
    SRC_ZDOO_PATH = "${WORKSPACE}/zdoo"
    SRC_ZDOOEXT_PATH = "${WORKSPACE}/zdooext"

    SRC_ZENTAOEXT_PATH = "${WORKSPACE}/zentaoext"

    MIRROR = "true"
  }

  stages {

    stage("Package") {
      when {
        allOf {
          buildingTag()
        }
        beforeAgent true
      }

      agent {
        kubernetes {
          inheritFrom "zentao-package build-docker xuanim"
        }
      }

      environment {
        MIDDLE_IMAGE_REPO = "hub.qc.oop.cc/zentao-package-ext"
        MIDDLE_IMAGE_TAG = """${sh(
                            returnStdout: true,
                            script: 'date +%y%m%d%H%M-${BUILD_ID}'
        ).trim()}"""
      }

      stages {
        stage("Pull") {
          steps {
            checkout scm
            sh 'env'
          }
        }

        stage("PullExt") {
          environment {
            XUANVERSION = """${sh(
                      returnStdout: true,
                      script: 'jq -r .pkg.xuanxuan.gitVersion < dependency.json'
            ).trim()}"""
            ZENTAOEXT_VERSION = """${sh(
                      returnStdout: true,
                      script: 'jq -r .pkg.zentaoext.gitVersion < dependency.json'
            ).trim()}"""
            ZDOO_VERSION = """${sh(
                      returnStdout: true,
                      script: 'jq -r .pkg.zdoo.gitVersion < dependency.json'
            ).trim()}"""
            ZDOOEXT_VERSION = """${sh(
                      returnStdout: true,
                      script: 'jq -r .pkg.zdooext.gitVersion < dependency.json'
            ).trim()}"""
          }

          steps {
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
                userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: 'https://git.zcorp.cc/easycorp/zentaoext.git']]
              )
            }
          }
        }

        stage("Build") {
          stages {
            stage("make ciCommon") {
              steps {
                container('xuanimbot') {
                  sh 'git config --global --add safe.directory $(pwd)'
                  sh '/usr/local/bin/xuanimbot --groups 84be4c6e-02e3-4fdc-b081-318c0c1eca02 --groups 31a0008b-6e3e-4b7f-9b7b-396a46b1f8f4 --title "Start build zentaopms" --url "${BUILD_URL}" --content "Build by Tag ${TAG_NAME}" --debug --custom'
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

            stage("encrypt ext code") {
              steps {
                container('package') {
                  sh 'cd $SRC_ZENTAOEXT_PATH && make'
                  sh 'cp ${ZENTAO_BUILD_PATH}/docker/Dockerfile.release.ext ./Dockerfile.release.ext'
                }
                container('docker') {
                  sh 'docker build --pull -t ${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG} -f Dockerfile.release.ext ${ZENTAO_RELEASE_PATH}'
                  sh 'docker push ${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}'
                }
              }
            }

          }
        }

        stage("Publish") {
          environment {
            PMS_VERSION = """${sh(
                                returnStdout: true,
                                script: 'cat ${SRC_ZENTAOEXT_PATH}/VERSION'
            ).trim()}"""
            BIZ_VERSION = """${sh(
                                returnStdout: true,
                                script: 'cat ${SRC_ZENTAOEXT_PATH}/BIZVERSION'
            ).trim()}"""
            MAX_VERSION = """${sh(
                                returnStdout: true,
                                script: 'cat ${SRC_ZENTAOEXT_PATH}/MAXVERSION'
            ).trim()}"""

            GIT_COMMIT = """${sh(
                              returnStdout: true,
                              script: 'git rev-parse HEAD'
            ).trim()}"""

            GIT_TAG_BUILD_TYPE = """${sh(
                              returnStdout: true,
                              script: 'misc/parse_tag.sh $TAG_NAME type'
            ).trim()}"""

            GIT_TAG_BUILD_GROUP = """${sh(
                              returnStdout: true,
                              script: 'misc/parse_tag.sh $TAG_NAME group'
            ).trim()}"""

            GIT_TAGGER_NAME = """${sh(
                            returnStdout: true,
                            script: 'git for-each-ref --format="%(taggername)" refs/tags/$(git tag --points-at HEAD)'
            ).trim()}"""

            OUTPUT_PKG_PATH = "${ZENTAO_RELEASE_PATH}/output"

            ARTIFACT_REPOSITORY = """${sh(
                                returnStdout: true,
                                script: 'misc/parse_tag.sh $TAG_NAME type | grep release >/dev/null && echo easycorp || echo easycorp-snapshot'
                ).trim()}"""
            ARTIFACT_HOST = "nexus.qc.oop.cc"
            ARTIFACT_PROTOCOL = "https"
            ARTIFACT_CRED_ID = "nexus-jenkins"
          }

          stages {
            stage("Merge and Upload") {

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
                    values "php5.4_5.6", "php7.0", "php7.1",  "php7.2_7.4", "php8.1", "k8s.php7.2_7.4", "k8s.php8.1"
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
                          nexusArtifactUploader(
                            nexusVersion: 'nexus3',
                            protocol: env.ARTIFACT_PROTOCOL,
                            nexusUrl: env.ARTIFACT_HOST,
                            groupId: 'zentao.bizPack' + '.' + env.GIT_TAG_BUILD_GROUP,
                            version: env.BIZ_VERSION,
                            repository: env.ARTIFACT_REPOSITORY,
                            credentialsId: env.ARTIFACT_CRED_ID,
                            artifacts: [
                              [artifactId: env.ARTIFACT_NAME,
                               classifier: env.PHPVERSION,
                               file: env.ZENTAO_RELEASE_PATH + '/biz.zip',
                               type: 'zip'],
                              [artifactId: env.ARTIFACT_NAME + '.update',
                               classifier: env.PHPVERSION,
                               file: env.ZENTAO_RELEASE_PATH + '/biz.update.zip',
                               type: 'zip'] 
                            ]
                          )
                          nexusArtifactUploader(
                            nexusVersion: 'nexus3',
                            protocol: env.ARTIFACT_PROTOCOL,
                            nexusUrl: env.ARTIFACT_HOST,
                            groupId: 'zentao.maxPack' + '.' + env.GIT_TAG_BUILD_GROUP,
                            version: env.MAX_VERSION,
                            repository: env.ARTIFACT_REPOSITORY,
                            credentialsId: env.ARTIFACT_CRED_ID,
                            artifacts: [
                              [artifactId: env.ARTIFACT_NAME,
                               classifier: env.PHPVERSION,
                               file: env.ZENTAO_RELEASE_PATH + '/max.zip',
                               type: 'zip'],
                              [artifactId: env.ARTIFACT_NAME + '.update',
                               classifier: env.PHPVERSION,
                               file: env.ZENTAO_RELEASE_PATH + '/max.update.zip',
                               type: 'zip'] 
                            ]
                          )

                          sh 'mkdir ${OUTPUT_PKG_PATH}/${PMS_VERSION} ${OUTPUT_PKG_PATH}/${BIZ_VERSION} ${OUTPUT_PKG_PATH}/${MAX_VERSION}'
                          sh 'cp ${ZENTAO_RELEASE_PATH}/base.zip ${OUTPUT_PKG_PATH}/${PMS_VERSION}/${ARTIFACT_NAME}.${PMS_VERSION}.${INT_FLAG}${PHPVERSION}.zip'
                          sh 'mv ${ZENTAO_RELEASE_PATH}/biz.zip ${OUTPUT_PKG_PATH}/${BIZ_VERSION}/${ARTIFACT_NAME}.${BIZ_VERSION}.${INT_FLAG}${PHPVERSION}.zip'
                          sh 'mv ${ZENTAO_RELEASE_PATH}/biz.update.zip ${OUTPUT_PKG_PATH}/${BIZ_VERSION}/${ARTIFACT_NAME}.${BIZ_VERSION}.${INT_FLAG}update.${PHPVERSION}.zip'
                          sh 'mv ${ZENTAO_RELEASE_PATH}/max.zip ${OUTPUT_PKG_PATH}/${MAX_VERSION}/${ARTIFACT_NAME}.${MAX_VERSION}.${INT_FLAG}${PHPVERSION}.zip'
                          sh 'mv ${ZENTAO_RELEASE_PATH}/max.update.zip ${OUTPUT_PKG_PATH}/${MAX_VERSION}/${ARTIFACT_NAME}.${MAX_VERSION}.${INT_FLAG}update.${PHPVERSION}.zip'
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
                          sh 'mv ${ZENTAO_RELEASE_PATH}/zentao.rpm ${OUTPUT_PKG_PATH}/${PMS_VERSION}/${ARTIFACT_NAME}.${PMS_VERSION}.${PHPVERSION}.1.noarch.rpm'
                          sh 'mv ${ZENTAO_RELEASE_PATH}/zentao.deb ${OUTPUT_PKG_PATH}/${PMS_VERSION}/${ARTIFACT_NAME}.${PMS_VERSION}.${PHPVERSION}.1.all.deb'
                        }
                      } // End upload syspack cn

                      stage("Upload Qiniu") {
                        when {
                          environment name: 'GIT_TAG_BUILD_TYPE', value: 'release'
                        }

                        environment {
                          QINIU_BUCKET = "download"
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
              steps {
                container('xuanimbot') {
                  sh 'env | grep GIT'
                  sh 'git config --global --add safe.directory $(pwd)'
                  sh './misc/gen_build_report.sh > /tmp/success.md'
                  sh '/usr/local/bin/xuanimbot --groups 84be4c6e-02e3-4fdc-b081-318c0c1eca02 --groups 31a0008b-6e3e-4b7f-9b7b-396a46b1f8f4 --title "zentaopms package success" --url "${RUN_DISPLAY_URL}" --content-file /tmp/success.md --debug --custom'
                }
              }
            }

            stage("Docker Image") {
              agent {
                kubernetes {
                  inheritFrom "dind xuanim"
                }
              }
              stages() {
                stage("prepare") {
                  steps {
                    checkout scmGit(branches: [[name: "master"]],
                      extensions: [cloneOption(depth: 2, noTags: false, reference: '', shallow: true)],
                      userRemoteConfigs: [[credentialsId: 'git-zcorp-cc-jenkins-bot-http', url: 'https://git.zcorp.cc/app/zentao.git']]
                    )
                    container('docker') {
                      sh "sed -i 's/dl-cdn.alpinelinux.org/mirrors.tuna.tsinghua.edu.cn/g' /etc/apk/repositories"
                      sh "apk --no-cache add make bash jq git"
                      sh "docker run --privileged --rm tonistiigi/binfmt --install all"
                      sh "docker buildx create --name mybuilder --driver docker-container --bootstrap"
                      sh "docker buildx use mybuilder"
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
                    container('docker') {
                      sh 'env | grep ZENTAO'
                      sh 'make build'
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
                      sh 'make build-biz'
                      sh 'make build-biz-k8s'
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
                      sh 'make build-max'
                      sh 'make build-max-k8s'
                    }
                  }
                }

                stage("Image Notice") {
                  steps {
                    container('docker') {
                      sh 'make markdown-render > ./report.md'
                    }
                    container('xuanimbot') {
                      sh 'git config --global --add safe.directory $(pwd)'
                      sh '/usr/local/bin/xuanimbot --groups 84be4c6e-02e3-4fdc-b081-318c0c1eca02  --groups 31a0008b-6e3e-4b7f-9b7b-396a46b1f8f4 --title "zentaopms build image success" --url "${RUN_DISPLAY_URL}" --content-file ./report.md --debug --custom'
                    }
                  }
                }

              }
            } // End Docker Image

            stage("Zbox") {
              agent {
                kubernetes {
                  containerTemplate {
                    name "docker"
                    image "docker:23.0.6-dind-alpine3.17"
                    command "sleep"
                    args "99d"
                  }
                  inheritFrom "dind xuanim"
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
                      sh "apk --no-cache add make bash jq git curl wget libarchive-tools p7zip mariadb-client"
                      sh "docker run --privileged --rm tonistiigi/binfmt --install all"
                      sh "docker buildx create --name mybuilder --driver docker-container --bootstrap"
                      sh "docker buildx use mybuilder"
                      sh 'curl https://pkg.qucheng.com/files/stacksmith/render-template-1.0.1-10-debian-11-amd64.tar.gz | tar zxf - -C /'
                    }
                  }
                }

                stage("Build Zbox") {
                  environment {
                    // printf "$PKG_URL_FORMATTER" pmsPack ZenTaoPMS 18.5 ZenTaoPMS-18.5-php8.1.zip
                    PKG_URL_FORMATTER = """${sh(
                                returnStdout: true,
                                script: "echo ${ARTIFACT_PROTOCOL}://${ARTIFACT_HOST}/repository/${ARTIFACT_REPOSITORY}/zentao/%s/`echo ${GIT_TAG_BUILD_GROUP} | tr . /`/%s/%s/%s"
                      ).trim()}"""
                  }
                  steps {
                    container('docker') {
                      sh 'bash build-zbox.sh zh-cn linux $PMS_VERSION $BIZ_VERSION $MAX_VERSION ipd1.0'
                      sh 'bash build-zbox.sh en linux $PMS_VERSION $BIZ_VERSION $MAX_VERSION ipd1.0'
                      sh 'ls -l release'
                    }

                    nexusArtifactUploader(
                      nexusVersion: 'nexus3',
                      protocol: env.ARTIFACT_PROTOCOL,
                      nexusUrl: env.ARTIFACT_HOST,
                      groupId: 'zentao.pmsPack' + '.' + env.GIT_TAG_BUILD_GROUP,
                      version: env.PMS_VERSION,
                      repository: env.ARTIFACT_REPOSITORY,
                      credentialsId: env.ARTIFACT_CRED_ID,
                      artifacts: [
                        [artifactId: 'ZenTaoPMS',
                         classifier: 'zbox_64',
                         file: './release/zh-cn/' + PMS_VERSION + '/ZenTaoPMS.' + PMS_VERSION + '.zbox_64.tar.gz',
                         type: 'tar.gz'],
                        [artifactId: 'ZenTaoALM',
                         classifier: 'zbox_64',
                         file: './release/en/' + PMS_VERSION + '/ZenTaoALM.' + PMS_VERSION + '.int.zbox_64.tar.gz',
                         type: 'tar.gz']
                      ]
                    )

                    nexusArtifactUploader(
                      nexusVersion: 'nexus3',
                      protocol: env.ARTIFACT_PROTOCOL,
                      nexusUrl: env.ARTIFACT_HOST,
                      groupId: 'zentao.bizPack' + '.' + env.GIT_TAG_BUILD_GROUP,
                      version: env.BIZ_VERSION,
                      repository: env.ARTIFACT_REPOSITORY,
                      credentialsId: env.ARTIFACT_CRED_ID,
                      artifacts: [
                        [artifactId: 'ZenTaoPMS',
                         classifier: 'zbox_64',
                         file: './release/zh-cn/' + BIZ_VERSION + '/ZenTaoPMS.' + BIZ_VERSION + '.zbox_64.tar.gz',
                         type: 'tar.gz'],
                        [artifactId: 'ZenTaoALM',
                         classifier: 'zbox_64',
                         file: './release/en/' + BIZ_VERSION + '/ZenTaoALM.' + BIZ_VERSION + '.int.zbox_64.tar.gz',
                         type: 'tar.gz']
                      ]
                    )

                    nexusArtifactUploader(
                      nexusVersion: 'nexus3',
                      protocol: env.ARTIFACT_PROTOCOL,
                      nexusUrl: env.ARTIFACT_HOST,
                      groupId: 'zentao.maxPack' + '.' + env.GIT_TAG_BUILD_GROUP,
                      version: env.MAX_VERSION,
                      repository: env.ARTIFACT_REPOSITORY,
                      credentialsId: env.ARTIFACT_CRED_ID,
                      artifacts: [
                        [artifactId: 'ZenTaoPMS',
                         classifier: 'zbox_64',
                         file: './release/zh-cn/' + MAX_VERSION + '/ZenTaoPMS.' + MAX_VERSION + '.zbox_64.tar.gz',
                         type: 'tar.gz'],
                        [artifactId: 'ZenTaoALM',
                         classifier: 'zbox_64',
                         file: './release/en/' + MAX_VERSION + '/ZenTaoALM.' + MAX_VERSION + '.int.zbox_64.tar.gz',
                         type: 'tar.gz']
                      ]
                    )

                    sh 'script/lib/gen_report.sh > zbox-success.md'
                    container('xuanimbot') {
                      sh 'git config --global --add safe.directory $(pwd)'
                      sh '/usr/local/bin/xuanimbot --groups 84be4c6e-02e3-4fdc-b081-318c0c1eca02 --groups 31a0008b-6e3e-4b7f-9b7b-396a46b1f8f4 --title "zentaopms build zbox success" --url "${RUN_DISPLAY_URL}" --content-file zbox-success.md --debug --custom'
                    }
                  }
                }

                stage("Upload Zbox to Qiniu") {
                  when {
                    environment name: 'GIT_TAG_BUILD_TYPE', value: 'release'
                  }

                  environment {
                    QINIU_BUCKET = "download"
                    OBJECT_KEY_PREFIX = "zentao/"
                    QINIU_ACCESS_KEY = credentials('qiniu-upload-ak')
                    QINIU_SECRET_KEY = credentials('qiniu-upload-sk')
                  }

                  steps {
                    container('jnlp') {
                      sh 'qshell account $QINIU_ACCESS_KEY $QINIU_SECRET_KEY uploader'
                      sh 'qshell qupload2 --bucket $QINIU_BUCKET --overwrite --src-dir ./release/zh-cn --key-prefix $OBJECT_KEY_PREFIX'
                      sh 'qshell qupload2 --bucket $QINIU_BUCKET --overwrite --src-dir ./release/en --key-prefix $OBJECT_KEY_PREFIX'
                    }
                  }
                } // End Upload Zbox to Qiniu
              }
            } // End Zbox

          }

          post {
            failure {
              container('xuanimbot') {
                sh 'git config --global --add safe.directory $(pwd)'
                sh '/usr/local/bin/xuanimbot --users "${GIT_TAGGER_NAME}" --groups 31a0008b-6e3e-4b7f-9b7b-396a46b1f8f4 --title "zentaopms build failed" --url "${BUILD_URL}" --content "" --debug --custom'
              }
            }
          }
        } // end publish
      }
    } // end package

  }

}


