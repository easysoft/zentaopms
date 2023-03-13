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
                            script: 'echo $BUILD_ID'
    ).trim()}"""
  }


  stages {
     stage('SonarQube and Build Image') {
       parallel {
        /*
         stage('SonarQube') {
           steps {
             container('sonar') {
                 withSonarQubeEnv('sonarqube') {
                     catchError(stageResult: 'FAILURE') {
                         sh 'git config --global --add safe.directory $(pwd)'
                         sh 'sonar-scanner -Dsonar.inclusions=$(git diff --name-only HEAD~1|tr "\\n" ",") -Dsonar.analysis.user=$(git show -s --format=%an)'
                    }
               }
             }
           }
           post {
             success {
               echo "stage sonarqube success"
             }
             failure {
               echo "stage sonarqube failure"
            }
          }
        }
        */
         stage('Build Image') {
           steps {
             container('docker') {
                 sh 'docker build --pull . -f Dockerfile.test --build-arg VERSION=${ZENTAO_VERSION} --build-arg MIRROR=true --build-arg MYSQL_HOST=${MYSQL_SERVER_HOST} --build-arg MYSQL_PASSWORD=${MYSQL_ROOT_PASSWORD} -t ${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}'
                 sh 'docker push ${MIDDLE_IMAGE_REPO}:${MIDDLE_IMAGE_TAG}'
             }
           }
        }
      }
    }

  } // End Root Stages
} // End pipeline
