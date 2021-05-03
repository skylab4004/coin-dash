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


```
create view portfolio_values as 
(
select 
	snapshot_time, 
	asset, 
	sum(sum_pln) as value_in_pln, 
	sum(sum_usd) as value_in_usd, 
	sum(sum_btc) as value_in_btc, 
	sum(sum_eth) as value_in_eth
from
( 
select 
	snapshot_time, asset, 
	sum(value_in_pln) as sum_pln, 
	sum(value_in_usd) as sum_usd, 
	sum(value_in_btc) as sum_btc, 
	sum(value_in_eth) as sum_eth 
from portfolio_snapshots  group by 1, 2
union
select snapshot_time, asset, 0, 0, 0, 0 from 
(select distinct snapshot_time from `portfolio_snapshots`) as snapshot_times,
(select distinct asset from `portfolio_snapshots`) as assets
group by 1, 2 
) as foo
group by 1, 2
order by 1, 2 asc
)
```
### add generated column
```
alter table `portfolio_snapshots` add column snapshot_timestamp timestamp as (from_unixtime(snapshot_time/1000)) 
after snapshot_time; 
```

# TODO

* top gainers 5 min, 30 min, 1 d
* top loosers 5 min, 30 min, 1 d
* ~~bieżąca wartość portfolio w pln/usd~~ 
* ~~PNL od północy~~
* ~~stacked chart~~
* ~~pie chart z bieżącym portfolio~~
* zrefaktorować wyciąganie danych do widoków z routes/web.php do kontrolera
* ~~dodać czas ładowania strony~~
* ~~dodać czas ostatniego snapshota (wyświetlane dane)~~
* ~~dodać narzędzia do debugowania laravela~~
* poprawić timezone-y dla kontenerów
* responsive design
* pwa
* autoryzacja http
* automatyczne synchronizowanie posiadanych tokenów ERC20, by zasysać ich ceny

# Struktura aplikacji

TODOS:
* przeniesc wykresy na osobny ekran
* dodac automatyczne zasysanie koinow do slownikow
* dodać procenty do piecharta
* ukrywanie małych sald tokenów
* dodać udział procentowy tokena w tabelce z portfolio
* otwarcie coingecko na clicku nazwy tokena


1. Home
- ~~bieżące saldo w pln/usd~~
- ~~dzisiejsze PNL w  PLN/USD i % względem zamknięcia dnia wczorajszego~~
- ~~PNL w PLN i % z ostatnich 30 minut lub godziny~~
- ~~stacked chart z rozbiciem portfolio od początku dnia (interwał = 5 minut)~~
- ~~stacked chart z rozbiciem portfolio z ostatniego tygodnia lub miesiąca (interwał = 1 dzień lub 12 h)~~
- ~~pie chart z rozbiciem portfolio + równowartość w pln + ilość w walucie natywnej~~
- przełącznik PLN/USD
- przełącznik hide values (procenty zostają)
- przełącznik dark/light mode

2. Binance
- bieżące saldo w pln/usd
- dzisiejsze PNL w  PLN/USD i % względem zamknięcia dnia wczorajszego
- PNL w PLN i % z ostatnich 30 minut lub godziny
- stacked chart z rozbiciem portfolio od początku dnia (interwał = 5 minut)
- stacked chart z rozbiciem portfolio z ostatniego tygodnia lub miesiąca (interwał = 1 dzień lub 12 h)
- pie chart z procentowym rozbiciem portfolio + równowartość w pln + ilość w walucie natywnej

2. Metamask
- bieżące saldo w pln/usd
- dzisiejsze PNL w  PLN/USD i % względem zamknięcia dnia wczorajszego
- PNL w PLN i % z ostatnich 30 minut lub godziny
- stacked chart z rozbiciem portfolio od początku dnia (interwał = 5 minut)
- stacked chart z rozbiciem portfolio z ostatniego tygodnia lub miesiąca (interwał = 1 dzień lub 12 h)
- pie chart z procentowym rozbiciem portfolio + równowartość w pln + ilość w walucie natywnej