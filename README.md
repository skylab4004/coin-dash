# Installation

1. `git clone https://github.com/skylab4004/coin-dash`
2. `cd coin-dash`
3. `cp .env.example .env`
4. you may need to set `DB_HOST=127.0.0.1`in `.env` if you're deploying the project to Raspberry Pi
5. `docker-compose build`
6. `docker-compose up -d`
7. login to app container 
8. docker exec -it coin-dash_mysql_1 mysql -h 0.0.0.0 -u root
   GRANT USAGE ON *.* TO 'root'@'localhost' IDENTIFIED BY '';
   GRANT ALL privileges ON *.* TO 'root'@'localhost';
   flush privileges;
9. chown -R sail:www-data *
   