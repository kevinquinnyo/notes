## Notes API Demo

This API provides basic CRUD operations for notes. It is a demo/test application using [laravel](https://laravel.com/).

### Notes
The fields allowed to be assigned/updated are:
- `title` (max:50)
- `note` (max:1000)

### Users
Users are handled via laravel [sanctum](https://laravel.com/docs/8.x/sanctum). See setup section for creating a user.

### API endpoints
- `GET` `/api/notes`: get all of your notes
- `GET` `/api/notes{id}`: get a specific note by ID
- `POST` `/api/notes`: create a new note (allowed: `title`, `note`)
- `PATCH` `/api/notes{id}`: update a note (allowed: `title`, `note`)
- `DELETE` `/api/notes/{id}`: delete a note

### Requirements:
- `php >= 8.0`
- `npm`
- `composer`

### Setup
#### Without Docker
**Note**: I haven't tested without docker yet (ymmv)

```bash
composer install --dev
artisan migrate
npm install
npm run dev
artisan serve
```
#### With Docker
```bash
sail up
sail composer install --dev
sail artisan migrate
sail npm install
sail npm run dev
```

### Next Steps
- Go to http://localhost/register in a browser, and sign up.
- Create an API token ("your@email-here.com" is the email you signed up with in the previous step)
    - (without docker): `artisan command:create_api_token your@email-here.com`
    - (with docker/sail): `sail artisan command:create_api_token your@email-here.com`

This will generate an API token, and provide it to you on the command line. Save that somewhere.

### API examples:
Replace `$YOUR_TOKEN` in these commands with your token from the command above that generated the API token.

```bash
# get all of your notes
curl localhost/api/notes -H 'content-type: application/json' -H 'Authorization: Bearer $YOUR_TOKEN'

# get a specific note (replace 1 in the URL with the note ID you want to view)
curl localhost/api/note/1 -H 'content-type: application/json' -H 'Authorization: Bearer $YOUR_TOKEN'

# create a note
curl localhost/api/notes -H 'content-type: application/json' -H 'Authorization: Bearer $YOUR_TOKEN' -X POST

# update a note (replace 1 in the URL with the note ID you want to update)
curl localhost/api/note/1 -H 'content-type: application/json' -H 'Authorization: Bearer $YOUR_TOKEN' -X PATCH

# delete a note (replace 1 in the URL with the note ID you want to delete)
curl localhost/api/note/1 -H 'content-type: application/json' -H 'Authorization: Bearer $YOUR_TOKEN' -X DELETE
```

### Tests

To run only the tests that were added for the Notes API:
```
# with docker
sail test tests/Feature/Api

# without docker
sail test tests/Feature/Api
```

### Additional Notes:
- Authentication is handled via the sanctum laravel library, and uses tokens. This is not Oauth2, but it is similar. This was used because it was a way to get this off the ground quickly.  If HTTP Basic authentication is desired, you can uncomment one line in `app/Http/Kernel.php` in the `api` section of the `$middlewareGroups` array. This will effectively disable the sanctum auth and enable basic auth.  The curl commands above would not need the `Authorization` header, and could instead pass `-u your@email-here.com:your-actual-password`.
- Aside from registering your account, there is no frontend in this application
- I haven't deployed this without docker/laravel sail yet

### Troubleshooting
If you are using docker + laravel sail, it is recommended that you are running rootless docker.  Not doing so will likely result in permissions issues.  See https://docs.docker.com/engine/security/rootless/
