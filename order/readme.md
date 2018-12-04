# Order Microservice Lumen

## Endpoints

API JSON Responses.
```
   # All Cart
   GET /api/v1/cart
   RESPONSES (200, body={"id":1,"total":1500000,"status":"status name","product_id":1,"user_id":1,"stock":1,"invoice_id":1})

   # Show Cart
   GET /api/v1/cart/{id}
   RESPONSE (200, body={"id":1,"total":1500000,"status":"status name","product_id":1,"user_id":1,"stock":1,"invoice_id":1})
   
   # Add item to Cart
   POST /api/v1/cart/
   formData : {"total":1200000,"status":"status name","product_id":1,"user_id":1,"stock":1,"invoice_id":1}



   # All Invoice
   GET /api/v1/invoice
   RESPONSES (200, body={"id":1,"total":1500000,"user_id":1,"address":"ship address","status":"status name","method":"method name"})

   # Show Invoice
   GET /api/v1/invoice/{id}
   RESPONSE (200, body={"id":1,"total":1500000,"user_id":1,"address":"ship address","status":"status name","method":"method name"}
   
   # Create new Invoice
   POST /api/v1/invoice/
   formData : {"total":1200000,"user_id":1,"address":"ship address","status":"status name","method":"method name"}
```
