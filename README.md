# auto relection property
Provide a simple property initialization.
API resources can be easily represented.

## Usage

Extends from an abstract class.
Define the required property names.
This must be the same name as the key you accept.

```
class GithubUser extends PropertyReflector
{
    public $id;
}

$user = new GithubUser(['id' => 'xxxx']);
$user->id;
```

If the property is not defined, it will not be set.

```
class GithubUser extends PropertyReflector
{
    public $id;
}

$user = new GithubUser(['name' => 'xxxx']);
$user->name; // access undefined property
```

## Example

```
class GithubUser extends PropertyReflector
{
    public $name;
}

// cURL,Guzzle ...etc
$client = new GuzzleHttp\Client();
$res = $client->request('GET', 'https://api.github.com/users/octocat');
$user = new GithubUser($res->getBody()->getContents());
$user->name; // octocat
```