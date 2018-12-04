# Order Microservice Lumen

## Endpoints

API JSON Responses.
```

   # All Cart
   GET /api/v1/cart

   # Show Cart
   GET /api/v1/cart/{id}

   # Add item to Cart (status field by default is 'incomplete')
   POST /api/v1/cart/
   formData : {
      "total":1200000,
      "status":"incomplete",
      "product_id":1,
      "user_id":1,
      "stock":1,
      "invoice_id":NULL
   }
   

   # All Invoice
   GET /api/v1/invoice

   # Show Invoice
   GET /api/v1/invoice/{id}

   # Create new Invoice (status field by default is 'open')
   POST /api/v1/invoice/checkout
   formData : {
      "total":1200000,
      "user_id":1,
      "address":"ship address",
      "method":"method name"
   }

   # Set status Invoice to 'order'
   PUT /api/v1/invoice/order/{id}

   
```

**JSON Output** Sample
```

   # All Cart
   GET /api/v1/cart

   # Show Cart
   GET /api/v1/cart/{id}
   {
      "data": {
         "cart": {
            "id": 3,
            "created_at": "2018-12-03 13:11:12",
            "total": 1200000,
            "status": "pending",
            "product_id": 1,
            "user_id": 2,
            "stock": 1,
            "invoice_id": null
         }
      }
   }

   # Add item to Cart
   POST /api/v1/cart/
   {
      "success": "Cart created"
   }
   

   # All Invoice
   GET /api/v1/invoice
   {
      "data": {
        "invoice": [
            {
                "id": 2,
                "total": 1200000,
                "user_id": 2,
                "address": "Jl. Pasteur",
                "status": "order",
                "method": "transfer",
                "created_at": "2018-12-04 01:26:06",
                "cart": [
                    {
                        "id": 2,
                        "created_at": "2018-12-04 02:41:51",
                        "total": 2000,
                        "status": "incomplete",
                        "product_id": 1,
                        "user_id": 2,
                        "stock": 1,
                        "invoice_id": 2
                    },
                    {
                        "id": 3,
                        "created_at": "2018-12-04 02:43:31",
                        "total": 1000000,
                        "status": "incomplete",
                        "product_id": 2,
                        "user_id": 2,
                        "stock": 1,
                        "invoice_id": 2
                    }
                ]
            },
            {
                "id": 4,
                "total": 0,
                "user_id": 2,
                "address": "jl. gunung batu",
                "status": "open",
                "method": "transfer atm",
                "created_at": "2018-12-04 02:46:19",
                "cart": []
            }
        ],
        "meta": {
            "pagination": {
                "total": 2,
                "count": 2,
                "per_page": 15,
                "current_page": 1,
                "total_pages": 1,
                "links": []
            }
        }
      },
      "links": [
        {
            "rel": "cart",
            "href": "http://order.lm.local/api/v1/cart",
            "method": "GET"
        },
        {
            "rel": "self",
            "href": "http://order.lm.local/api/v1/invoice",
            "method": "GET"
        }
      ]
   }

   # Show Invoice
   GET /api/v1/invoice/{id}
   {
      "data": {
        "invoice": {
            "id": 2,
            "total": 4000,
            "user_id": 2,
            "address": "Jl. Pasteur",
            "status": "order",
            "method": "transfer",
            "created_at": "2018-12-04 01:26:06",
            "cart": [
                {
                    "id": 2,
                    "created_at": "2018-12-04 02:41:51",
                    "total": 2000,
                    "status": "incomplete",
                    "product_id": 1,
                    "user_id": 2,
                    "stock": 1,
                    "invoice_id": 2
                },
                {
                    "id": 3,
                    "created_at": "2018-12-04 02:43:31",
                    "total": 2000,
                    "status": "incomplete",
                    "product_id": 2,
                    "user_id": 2,
                    "stock": 1,
                    "invoice_id": 2
                }
            ]
        }
      }
   }

   # Create new Invoice
   POST /api/v1/invoice/checkout
   {
      "success": "Invoice created"
   }

   # Set status Invoice to 'order'
   PUT /api/v1/invoice/order/{id}
   {
      "success": "Invoice status set to order"
   }

