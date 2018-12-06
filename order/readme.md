# Order Microservice Lumen

## Endpoints

API JSON Responses.
```

  # Add item to cart (status field by default is 'incomplete')
  POST /api/v1/cart/
  formData : {
    "total":1200000,
    "product_id":1,
    "user_id":1,
    "stock":1
  }

  # Update item in cart
  POST /api/v1/cart/update/{id}
  formData : {
    "stock":12
  }

  # Delete item in cart
  GET /api/v1/cart/delete/{id}

  # Get all item in cart
  GET /api/v1/trolley/{user_id}

  # Show detail item in cart
  GET /api/v1/cart/{id}


  # All Invoice
  GET /api/v1/invoice/history/{user_id}

  # Show Invoice
  GET /api/v1/invoice/{id}


  # Checkout (create or update invoice)
  POST /api/v1/book/checkout
  formData : {
    "user_id":2,
    "total":1500000,
    "address":"Jl. Gunung Batu",
    "method":"Transfer ATM",
  }

  # Confirm by invoice id
  POST /api/v1/book/confirm/{id}
  formData : {
    "address":"Jl. Surya Sumantri",
    "method":"internet banking",
  }

  # Commit by invoice id
  PUT /api/v1/book/commit/{id}

   
```

**JSON Output** Sample
```

  # Add item to cart (status field by default is 'incomplete')
  POST /api/v1/cart/
  {
    "success": "Cart created"
  }

  # Update item in cart
  POST /api/v1/cart/update/{id}
  {
    "success": "Cart updated"
  }

  # Delete item in cart
  GET /api/v1/cart/delete/{id}
  {
    "success": "Cart deleted"
  }

  # Get all item in cart
  GET /api/v1/trolley/{user_id}
  {
    "data": {
      "cart": [
        {
          "id": 4,
          "created_at": "2018-12-04 06:35:27",
          "total": 1200000,
          "status": "incomplete",
          "product_id": 2,
          "user_id": 2,
          "stock": 1,
          "invoice_id": null
        },
        {
          "id": 5,
          "created_at": "2018-12-04 06:39:01",
          "total": 1200000,
          "status": "incomplete",
          "product_id": 3,
          "user_id": 2,
          "stock": 1,
          "invoice_id": null
        }
      ]
    },
    "links": [
      {
        "rel": "self",
        "href": "http://order.lm.local/api/v1/cart",
        "method": "GET"
      }
    ]
  }

  # Show detail item in cart
  GET /api/v1/cart/{id}
  {
    "data": {
      "cart": {
        "id": 4,
        "created_at": "2018-12-04 06:35:27",
        "total": 1200000,
        "status": "incomplete",
        "product_id": 2,
        "user_id": 2,
        "stock": 1,
        "invoice_id": null
      }
    }
  }


  # All Invoice
  GET /api/v1/invoice/history/{user_id}
  {
    "data": {
      "invoice": [
        {
          "id": 2,
          "total": 1200000,
          "user_id": 2,
          "address": "Jl. Pasteur",
          "status": "lock",
          "method": "transfer",
          "created_at": "2018-12-04 01:26:06",
          "cart": [
            {
              "id": 2,
              "created_at": "2018-12-04 02:41:51",
              "total": 2000,
              "status": "pending",
              "product_id": 1,
              "user_id": 2,
              "stock": 1,
              "invoice_id": 2
            },
            {
              "id": 3,
              "created_at": "2018-12-04 02:43:31",
              "total": 1000000,
              "status": "pending",
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
        "status": "lock",
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


  # Checkout (create or update invoice)
  POST /api/v1/book/checkout
  {
    "data": {
        "invoice": {
            "id": 4,
            "total": 12345678,
            "user_id": 3,
            "address": "Jl. Pasteur",
            "status": "open",
            "method": "internet banking",
            "created_at": "2018-12-06 09:38:36",
            "cart": [
                {
                    "id": 5,
                    "created_at": "2018-12-06 04:58:06",
                    "total": 1200000,
                    "status": "incomplete",
                    "product_id": 1,
                    "user_id": 3,
                    "stock": 1,
                    "invoice_id": 4
                },
                {
                    "id": 8,
                    "created_at": "2018-12-06 09:38:08",
                    "total": 1200000,
                    "status": "incomplete",
                    "product_id": 2,
                    "user_id": 3,
                    "stock": 1,
                    "invoice_id": 4
                }
            ]
        }
    }
  }

  # Confirm by invoice id
  POST /api/v1/book/confirm/{id}
  {
    "data": {
        "invoice": {
            "id": 4,
            "total": 12345678,
            "user_id": 3,
            "address": "Jl. Surya Sumantri",
            "status": "open",
            "method": "transfer atm",
            "created_at": "2018-12-06 09:38:36",
            "cart": [
                {
                    "id": 5,
                    "created_at": "2018-12-06 04:58:06",
                    "total": 1200000,
                    "status": "incomplete",
                    "product_id": 1,
                    "user_id": 3,
                    "stock": 1,
                    "invoice_id": 4
                },
                {
                    "id": 8,
                    "created_at": "2018-12-06 09:38:08",
                    "total": 1200000,
                    "status": "incomplete",
                    "product_id": 2,
                    "user_id": 3,
                    "stock": 1,
                    "invoice_id": 4
                }
            ]
        }
    }
  }

  # Commit by invoice id
  PUT /api/v1/book/commit/{id}
  {
    "success": "OK"
  }

