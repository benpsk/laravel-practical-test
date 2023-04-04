# Laravel Practical Test

To enable email notifications, it is necessary to configure a queue for the associated events to function properly.

- In this sample project, instead of using the Laravel eloquent resources, I've built the custom API format.


## Api Documentation

<b>Base API Endpoint</b> - http://localhost:8000

<b>Request Header</b>
```
    Content-Type    : application/json
    Accept          : application/json
    Authorization   : Bearer tokenxxxxxxxxxxxxxxxxxxxxxxx 
```
<b>Base Success Response Format</b>
```json
{
    "success": 1,
    "status": 200,
    "meta": {
        "method": "get",
        "endpoint": "api/v1/user"
    },
    "data": {
        "id": 21,
        "name": "John Doe",
        "email": "bengunn.dev@gmail.com",
        "created_at": "2023-03-19T04:46:37.000000Z",
        "updated_at": "2023-03-19T04:46:37.000000Z"
    }
}
```

<b>Base Failure Response Format</b>
```json
{
    "success": 0,
    "status": 401,
    "meta": {
        "method": "post",
        "endpoint": "api/v1/login"
    },
    "errors": {
        "message": "Invalid credentials"
    }
}
```
```json
{
    "success": 0,
    "status": 422,
    "meta": {
        "method": "post",
        "endpoint": "api/v1/register"
    },
    "errors": {
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```

### 1. Register
```
Endpoint            /api/v1/register
Method              POST
Authentication      Not Required

```
<b>Parameters</b>
| Parameter | Type | Required | Description |
| --- | --- | --- | --- |
| name | String | Yes |  |
| email | String | Yes |  |
| password | String | Yes |  |
| password_confirmation | String | Yes |  |


<b>Response</b>
```json
{
    "success": 1,
    "status": 200,
    "meta": {
        "method": "post",
        "endpoint": "api/v1/register"
    },
    "data": {
        "id": 21,
        "name": "John Doe",
        "email": "bengunn.dev@gmail.com",
        "created_at": "2023-03-19T07:31:12.000000Z",
        "updated_at": "2023-03-19T07:31:12.000000Z"
    }
}
```


### 2. Login
```
Endpoint            /api/v1/login
Method              POST
Authentication      Not Required
```

<b>Parameters</b>
| Parameter | Type | Required | Description |
| --- | --- | --- | --- |
| email | String | Yes |  |
| password | String | Yes |  |


<b>Response</b>
```json
{
    "success": 1,
    "status": 200,
    "meta": {
        "method": "post",
        "endpoint": "api/v1/login"
    },
    "token": {
        "type": "Bearer",
        "access_token": "9|uWyRjX5z3QPEZR0hkTnJOyacG4ze8qJj6QXOaRHk",
        "expired_at": 86400
    },
    "data": {
        "id": 21,
        "name": "John Doe",
        "email": "bengunn.dev@gmail.com",
        "created_at": "2023-03-19T04:46:37.000000Z",
        "updated_at": "2023-03-19T04:46:37.000000Z"
    }
}
```

### 3. Logout
```
Endpoint            /api/v1/logout
Method              POST
Authentication      Required
```
<b>Parameters</b> - 


<b>Response</b>
```json
{
    "success": 1,
    "status": 200,
    "meta": {
        "method": "post",
        "endpoint": "api/v1/logout"
    },
    "data": {
        "message": "logout successful."
    }
}
```

### 4. Get User
```
Endpoint            /api/v1/user
Method              GET
Authentication      Required
```
Parameters - 

<b>Response</b>
```json
{
    "success": 1,
    "status": 200,
    "meta": {
        "method": "get",
        "endpoint": "api/v1/user"
    },
    "data": {
        "id": 21,
        "name": "John Doe",
        "email": "bengunn.dev@gmail.com",
        "created_at": "2023-03-19T04:46:37.000000Z",
        "updated_at": "2023-03-19T04:46:37.000000Z"
    }
}
```

### 5. Create Survey Form
```
Endpoint            /api/v1/survey
Method              POST
Authentication      Required
```

<b>Parameters</b>
| Parameter | Type | Required | Description |
| --- | --- | --- | --- |
| name | String | Yes |  |
| phone_no | String | Yes |  |
| gender | String | No |  |
| dob | Date | No |  |


<b>Response</b>
```json
{
    "success": 1,
    "status": 200,
    "meta": {
        "method": "post",
        "endpoint": "api/v1/survey"
    },
    "data": {
        "id": 103,
        "name": "Jane Doe",
        "phone_no": "+959453340064",
        "gender": "Male",
        "dob": "2023-03-02",
        "created_at": "2023-03-19T07:37:59.000000Z"
    }
}
```


### 6. Get Survey List
```
Endpoint            /api/v1/survey
Method              GET
Authentication      Required
```
Parameters - 

<b>Response</b>
```json
{
    "success": 1,
    "status": 200,
    "meta": {
        "method": "get",
        "endpoint": "api/v1/survey"
    },
    "data": {
        "id": 21,
        "name": "John Doe",
        "email": "bengunn.dev@gmail.com",
        "created_at": "2023-03-19T04:46:37.000000Z",
        "survey_form": [
            {
                "id": 101,
                "name": "John Doe",
                "phone_no": "+959453340064",
                "gender": null,
                "dob": "2023-03-02",
                "created_at": "2023-03-19T07:10:10.000000Z"
            }
        ]
    }
}
```
