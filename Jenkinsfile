pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                echo 'Checkout source code'
            }
        }

        stage('Install Dependencies') {
            steps {
                bat 'php -v'
                bat 'composer install --no-interaction --prefer-dist'
            }
        }

        stage('Test') {
            steps {
                echo 'No automated tests yet'
            }
        }
    }
}
