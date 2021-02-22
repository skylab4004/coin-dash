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
   

# Snippets

```
$snapshot = new App\Models\PortfolioSnapshot();
$snapshot -> snapshot_time = round(microtime(true) * 1000);
$snapshot -> source = 1;
$snapshot -> asset = "TEST";
$snapshot -> quantity = "123.456";
$snapshot -> value_in_btc = "123.456";
$snapshot -> value_in_eth = "123.456";
$snapshot -> value_in_usd = "123.456";
$snapshot -> value_in_pln = "123.456";
$snapshot -> save();
App\Models\PortfolioSnapshot::all();
```

# Dashboard 

Binance and Metamask current portfolio value in PLN
```
select snapshot_time, source, sum(value_in_pln) from portfolio_snapshots where snapshot_time = ( select max(snapshot_time) from portfolio_snapshots) group by 2

select FROM_UNIXTIME(snapshot_time/1000, '%Y-%d-%m %h:%i') as snapshot_time, sum(value_in_pln) from portfolio_snapshots group by 1;
```

TODO 
* automatyczne synchronizowanie posiadanych tokenów ERC20, by zasysać ich ceny