pipeline {
  agent {
    kubernetes {
      inheritFrom "build-docker code-scan xuanim mysql-server"
    }
  }

  environment {
    ZENTAO_VERSION = """${sh(
                            returnStdout: true,
                            script: 'cat VERSION'
    ).trim()}"""
    MYSQL_SERVER_HOST = """${sh(
                            returnStdout: true,
                            script: 'hostname -I'
    ).trim()}"""
    MYSQL_ROOT_PASSWORD = 'pass4ci'

    MIDDLE_IMAGE_REPO = "hub.qc.oop.cc/zentao-ztf"
    MIDDLE_IMAGE_TAG = """${sh(
                            returnStdout: true,
                            script: 'echo $BUILD_ID-${GIT_COMMIT}'
    ).trim()}"""
  }

  stages {
     stage("checkout code") {
       steps {
           echo "checkout code success"
       }
     }

     stage('Sonar Scanner') {
       parallel {
         stage('SonarQube') {
           steps {
             container('sonar') {
                 withSonarQubeEnv('sonarqube') {
                     sh 'git config --global --add safe.directory $(pwd)'
                     sh 'sonar-scanner -Dsonar.inclusions=$(git diff --name-only HEAD~1|tr "\\n" ",") -Dsonar.analysis.user=$(git show -s --format=%an)'
               }
             }
           }
           post {
             success {
                 container('xuanimbot') {
                     sh 'git config --global --add safe.directory $(pwd)'
                     sh '/usr/local/bin/xuanimbot  --users "$(git show -s --format=%an)" --title "sonar scanner" --url "https://sonar.qc.oop.cc/dashboard?id=zentaopms&branch=${GIT_BRANCH}" --content "sonar scanner success" --debug --custom'
                 }
             }
             failure {
                 container('xuanimbot') {
                     sh 'git config --global --add safe.directory $(pwd)'
                     sh '/usr/local/bin/xuanimbot  --users "$(git show -s --format=%an)" --title "sonar scanner" --url "https://sonar.qc.oop.cc/dashboard?id=zentaopms&branch=${GIT_BRANCH}" --content "sonar scanner failure" --debug --custom'
                 }
            }
          }
        }

         stage('Build Image') {
           steps {
             container('docker') {
                 sh 'docker build --pull . -f Dockerfile.test --build-arg VERSION=${ZENTAO_VERSION} --build-arg MIRROR=true --build-arg MYSQL_HOST=${MYSQL_SERVER_HOST} --build-arg MYSQL_PASSWORD=${MYSQL_ROOT_PASSWORD} -t ${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}'
                 sh 'docker push ${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}'
             }
           }
           post {
             success {
                 echo 'build image success'
             }
             failure {
                 container('xuanimbot') {
                     sh 'git config --global --add safe.directory $(pwd)'
                     sh '/usr/local/bin/xuanimbot  --users "$(git show -s --format=%an)" --title "build image" --url "${RUN_DISPLAY_URL}" --content "Build unit test image failure" --debug --custom'
                 }
             }
           }
        }
      }
    }

     stage('Unit Test'){
      stages{
        stage('Init') {
              agent {
                  kubernetes {
                      inheritFrom "xuanim"
                          containerTemplate {
                              name "zentao"
                              image "${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}"
                              command "sleep"
                              args "99d"
                          }
                  }
              }
              options { skipDefaultCheckout() }

              steps {
                  container('zentao') {
                      sh 'initdb.php ; /apps/zentao/test/ztest init'
                  }
              }
              post {
                success {
                    sh 'echo "stage unit init success"'
                }
                failure {
                 container('xuanimbot') {
                     sh 'git config --global --add safe.directory $(pwd)'
                     sh '/usr/local/bin/xuanimbot  --users "$(git show -s --format=%an)" --title "unittest init" --url "${RUN_DISPLAY_URL}" --content "Unit test database initialization failed" --debug --custom'
                 }
                }
              }
          }

        stage('Run') {
            matrix {
                agent {
                    kubernetes {
                       inheritFrom "xuanim"
                       containerTemplate {
                           name "zentao"
                           image "${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}"
                           command "sleep"
                           args "99d"
                       }
                    }
                }

                options { skipDefaultCheckout() }

                axes {
                    axis {
                        name "SEQUENCE"
                        values "P1", "P2", "P3", "P4", "P5", "P6", "P7"
                    }
                }
                stages {
                    stage("Run") {
                        steps {
                            container('zentao') {
                                sh 'initdb.php config'
                                sh '/apps/zentao/test/ztest extract ; /apps/zentao/test/ztest ${SEQUENCE} | tee /apps/zentao/test/${SEQUENCE}.log'
                                sh 'pipeline-unittest.sh /apps/zentao/test/${SEQUENCE}.log'
                            }
                        }
                    }
                }
            }
      post{
          success{
              container('xuanimbot') {
                  sh 'git config --global --add safe.directory /home/jenkins/agent/workspace/pangu_pangu_xuanimbot_master'
                  sh '/usr/local/bin/xuanimbot  --users "$(git show -s --format=%an)" --title "unittest" --url "${RUN_DISPLAY_URL}" --content "Unit test passed" --debug --custom'
              }
          }
          failure{
              container('xuanimbot') {
                  sh 'git config --global --add safe.directory $(pwd)'
                  sh '/usr/local/bin/xuanimbot  --users "$(git show -s --format=%an)" --title "unittest" --url "${RUN_DISPLAY_URL}" --content "Unit test failed" --debug --custom'
              }
          }
        }
      }//End unittest
    }
    }
  } // End Root Stages
} // End pipeline
