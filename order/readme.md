# Simple order web service

A simple order web service build on Laravel Lumen.

Data
-----------
It holds 3 orders hardcoded in the `OrderController`:
```php
    [
        "1" => ["user" => "1", "products" => ["1", "2"]],
        "2" => ["user" => "1", "products" => ["3"] ],
        "3" => ["user" => "2", "products" => ["1", "3"]],
    ]
```

Endpoints
-----------
There are 3 endpoints which are returning JSON Responses.

```
   # Returns all orders
   GET /order 
   
   # Returns spesific order by id
   # or returns 404 'Order not found'
   # if order does not exist
   GET /order/{id}
   
   # Returns all orders of a spesific user
   # or returns 404 'No orders found for this user'
   GET /order/user/{user}
```