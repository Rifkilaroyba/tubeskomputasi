pipeline {
    agent any

    stages {

        stage('Environment Info') {
            steps {
                echo "Node: ${env.NODE_NAME}"
                echo "Workspace: ${env.WORKSPACE}"
                echo "OS: ${env.OS}"
            }pipeline {
    agent any

    stages {

        stage('Environment Info') {
            steps {
                echo "Node: ${env.NODE_NAME}"
                echo "Workspace: ${env.WORKSPACE}"
                echo "OS: ${env.OS}"
            }
        }

        stage('Simple Command Test') {
            steps {
                bat 'echo HELLO FROM JENKINS'
            }
        }

        stage('List Files') {
            steps {
                bat 'dir || exit /b 0'
            }
        }

        stage('Basic PHP Check') {
            steps {
                bat '''
                echo === PHP CHECK ===
                where php >nul 2>&1
                if %ERRORLEVEL% NEQ 0 (
                    echo PHP not found in PATH
                    exit /b 0
                ) else (
                    php -v
                )
                '''
            }
        }

        stage('Finish') {
            steps {
                echo 'Pipeline finished safely'
            }
        }
    }

    post {
        always {
            echo 'DONE'
        }
    }
}

        }

        stage('Simple Command Test') {
            steps {
                bat 'echo HELLO FROM JENKINS'
            }
        }

        stage('List Files') {
            steps {
                bat 'dir || exit /b 0'
            }
        }

        stage('Finish') {
            steps {
                echo 'Pipeline finished safely'
            }
        }
    }

    post {
        always {
            echo 'DONE'
        }
    }
}
