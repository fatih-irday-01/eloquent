## [Additional Features to Eloquent](http://www.fatihirday.com.tr/)

- ifNull
- ifCount
- ifSum
- sumColumn
- countColumn
- concat
- caseWhen
- whereLike
- orWhereLike

- getSql
- dumpSql
- ddSql
<br />

---

### Installation

bash command

```shell
composer require fatihirday/eloquent
```
<br />

add provider to `config/app.php`

```php
'providers' => [
    // ...
    Fatihirday\Eloquent\EloquentServiceProvedir::class,
],
```
<br />

---

### Documentation

Prepared for <b>Mysql</b> and <b>PostgreSql</b>

####  - ifNull

Return the specified value IF the expression is NULL, otherwise return the expression

```php
Model::ifNull('column', 'value', 'responseName');
// or
Model::query()->ifNull('column', 'value', 'responseName');
```

####  - ifCount

Count of values satisfying the if condition

```php
Model::ifCount('column', 12, 'responseName');
// or
Model::ifCount('column', '!=', 'value', 'responseName');
```

####  - ifSum

Sum of values satisfying the if condition

```php
Model::ifSum('column', '!=', 'value', 'responseName');
// or
Model::ifSum('column', '=', 'value', 'sumColumn', 'responseName');
```

####  - sumColumn
```php
Model::sumColumn('column'); // sum(column) as column
// or
Model::sumColumn('column', 'responseName'); // sum(column) as responseName
```

####  - countColumn
```php
Model::countColumn('column'); // count(column) as column
// or
Model::countColumn('column', 'responseName'); // count(column) as responseName
```

####  - concat
```php
Model::concat(['name', 'id'], 'responseName') // nameid
// or
Model::concat(['name', 'id'], 'responseName', '-') // name-id
```

####  - caseWhen
```php
Model::caseWhen([
    'updated_at > created_at' => 'updated_at', // When Then
    'deleted_at > created_at' => 'deleted_at', // When Then
    'created_at', // Else
], 'responseName')
```

### whereLike

```php
use Fatihirday\Eloquent\Libraries\Enums\Like;

Model::whereLike('columnName', 'value', Like::FIRST); 
// WHERE columnName like '%value'

Model::whereLike('columnName', 'value', Like::MIDDLE); 
// WHERE columnName like '%value%'

Model::whereLike('columnName', 'value', Like::LAST); 
// WHERE columnName like 'value%'
```

### orWhereLike

```php
use Fatihirday\Eloquent\Libraries\Enums\Like;

Model::where('id', '>', 1)->orWhereLike('columnName', 'value', Like::FIRST); 
// WHERE id > 1 or columnName like '%value'

Model::where('id', '>', 1)->orWhereLike('columnName', 'value', Like::MIDDLE); 
// WHERE id > 1 or columnName like '%value%'

Model::where('id', '>', 1)->orWhereLike('columnName', 'value', Like::LAST); 
// WHERE id > 1 or columnName like 'value%'
```




### getSql

toSql and getBindings merge

```php
echo Model::where('name', 'value')->getSql();
// select * from `table_name` where `name` = 'fatih'
```

### dumpSql

dump for getSql

```php
Model::where('name', 'value')->dumpSql();
// select * from `table_name` where `name` = 'fatih'
```

### ddSql

dd for getSql

```php
Model::where('name', 'value')->ddSql();
// select * from `table_name` where `name` = 'fatih'
```




