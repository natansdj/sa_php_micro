# Microservice Lumen

## Endpoints

returning JSON Responses.

```
   # login 
   POST /login
   
   #register
   POST /register

   # Returns all users
   GET /users 
   
   # Returns spesific user by id
   # or returns 404 'User not found'
   # if user does not exist
   GET /users/{id}
   
   # Update user
   PUT /users/{id}
   
   # Update user password
   PUT /users/{id}/password
```

## Features 

**JWT** for the authentication of routes usable with the implemented service;

**Services** implemented to facilitate work:

    -Api helpers function
    -Response method to manage error/success/exceptions responses
    -Auth for manage user and jwt token
    -ACL method for manipulate user roles and permissions
    -Log method to manage file log
    -Cache implements methods to manage File and Redis cache with serialization
    
**Roles and Permissions** to assign them to users and manage routes with greater security;

**Repository pattern** implemented to manage the models in an abstract way and to allow the scalability of the business logic (used to guarantee also the code cleaning)

**Transformer** classes to manipulate data and better manage the recovery of related information (are transformed through functions implemented in ApiService)
  
**Artisan commands** to create Repository, ApiController, Provider and Transoformers (Other commands to create example file view documentation)

## License

This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
