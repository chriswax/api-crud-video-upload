
# API CRUD Video Upload

This Project was done in Native PHP/MYSQL for Video, image and other details to be uploaded to a server from chrome screen recorder extension.


## API Reference

#### Upload Video 

```http
  POST http://www.waxworks.name.ng/api
```

| Parameter   | Type     | Description                |
| :--------   | :------- | :------------------------- |
| `video`     | `file`   | *Must- Video in formdata*  |
| `username`  | `string` | *Optional - Username*  |
| `title`     | `string` | *Optional - Video Title*  |
| `thumbnail` | `file`   | *Optional - image*  |
| `description`| `string` | *Optional - in formdata* |

#### Sample Response
```
{
    "message": "Video recording uploaded Successfully",
    "statusCode": 201,
    "data": {
        "id": 2,
        "username": "default",
        "title": "Laravel Testing 15_24_ Delete Product_ test if it\\'s actually removed",
        "url": "www.waxworks.name.ng/uploads/651747e1f0a60_laravel_testing_15_24_delete_product_test_if_it_s_actually_removed.mp4",
        "description": null,
        "fileName": "Laravel Testing 15_24_ Delete Product_ test if it\\'s actually removed",
        "fileSize": 7649175,
        "thumbnail": "www.waxworks.name.ng/thumbnail/651747e1f086fWIN_20230526_12_44_03_Pro.jpg",
        "slug": "laravel-testing-15-24-delete-product-test-if-it-s-actually-removed",
        "transcription": null,
        "createdAt": "2023-09-29 22:55:45"
    }
}
```

#### Get Video

```http
  GET http://www.waxworks.name.ng/api/${id} or ${slug} or ${username}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id of item to fetch |
| `username`| `string` | **Required**. username to fetch videos |
| `slug`    | `string` | **Required**. uses slug to fetch video |
| ``        | `string` | **optional**. Fetches all video |

#### Sample Response
```
  {
    "message": "Video(s) to upload is required!",
    "status_code": 200,
    "data": [
        {
            "id": "2",
            "username": "default",
            "title": "Laravel Testing 15_24_ Delete Product_ test if it's actually removed",
            "url": "www.waxworks.name.ng/uploads/651747e1f0a60_laravel_testing_15_24_delete_product_test_if_it_s_actually_removed.mp4",
            "description": "",
            "fileName": "Laravel Testing 15_24_ Delete Product_ test if it's actually removed",
            "fileSize": "7649175",
            "thumbnail": "www.waxworks.name.ng/thumbnail/651747e1f086fWIN_20230526_12_44_03_Pro.jpg",
            "slug": "laravel-testing-15-24-delete-product-test-if-it-s-actually-removed",
            "transcription": "",
            "createdAt": "2023-09-29 22:55:45"
        }
    ]
}
```


#### DELETE Video

```http
  DELETE http://www.waxworks.name.ng/api/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id of item to Delete |

#### Sample Response
```
  {
    "message": "Video Deleted Successfully",
    "status_code": 200,
    "data": null
}
```



## Usage/Examples

```javascript
import Component from 'my-project'

function App() {
  return <Component />
}
```

