# Simple user web service

A simple user web service build on Laravel Lumen.

Data
-----------
It holds 3 users hardcoded in the `UserController`:
```php
    [
        "1" => "User 1",
        "2" => "User 2",
        "3" => "User 3",
    ]
```

Endpoints
-----------
There are 2 endpoints which are returning JSON Responses.

```
   # Returns all users
   GET /user 
   
   # Returns spesific user by id
   # or returns 404 'User not found'
   # if user does not exist
   GET /user/{id}
```