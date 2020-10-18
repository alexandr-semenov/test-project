Test project
===========

### Installation:
- **To clone project**
    ```
    git clone https://github.com/alexandr-semenov/test-project.git
    ```
- **Go to the folder**
    ```
    cd test-project
    ```
- **Execute command** 
   ```
   composer install
   ```
  
### Configure application:
- **Create database and configure following parameters with valid credentials in .env.local**
   ```
   DATABASE_URL=mysql://user:password@127.0.0.1:3306/db_name?serverVersion=5.7
   ```
- **Execute migrations**
   ```
   ./bin/console doctrine:migration:migrate
   ```
  
### Run the application:
- **For run application you can use Symfony Cli, install it (https://symfony.com/download) and run following command in root folder:**
    ```
    symfony server:start --port=8000 --no-tls -d
    ```
### Test application:
- **For test application you can use documentation page**
    ```
    http://127.0.0.1:8000/docs
    ```
    ![alt text](https://github.com/[username]/[reponame]/blob/[branch]/demo_image.png?raw=true)