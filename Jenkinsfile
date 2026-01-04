pipeline {
    agent any

    options {
        timestamps()
    }

    environment {
        APP_ENV = "development"
    }

    stages {

        stage('Checkout SCM') {
            steps {
                echo 'Checking out source code from GitHub'
                checkout scm
            }
        }

        stage('Verify Environment') {
            steps {
                echo 'Verifying environment'
                bat 'echo OS: %OS%'
                bat 'php -v'
                bat 'composer --version'
            }
        }

        stage('Install Dependencies') {
            steps {
                echo 'Installing PHP dependencies using Composer'
                bat 'composer install --no-interaction --prefer-dist'
            }
        }

        stage('Code Validation') {
            steps {
                echo 'Running basic code validation'
                bat 'php -l index.php'
            }
        }

        stage('Build Summary') {
            steps {
                echo 'Build completed successfully'
            }
        }
    }

    post {
        success {
            echo 'PIPELINE SUCCESS'
        }
        failure {
            echo 'PIPELINE FAILED'
        }
        always {
            echo 'Pipeline execution finished'
        }
    }
}
