# Working with Laravel Models
## To access database fields
- Database fields on an eloquent model can be accessed with the following syntax:
```php
$object_name->database_field;
```
OR
```php
$object_name['datbase_field'];
```

Example in a blade file, enclosed in a ddouble curly braces {{ }}
```html
<p><strong>Vehicle Name: </strong>{{$vehicle->vehicle_name}}</p>
```