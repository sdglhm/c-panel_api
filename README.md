# cPanel_api
cPanel/WHM api that uses cURL

This library was written by our company to implement it to a small software. But as our lord t-rex forces us to make this open, here it is.

## how to use

##### Update your composer.json file as follows.
```
{
    "require": {
        "sdglhm/c-panel_api": "dev-master"
    }
}
```

Run composer and download package to your monster. Did I just said it out loud ?

```php
require 'vendor/autoload.php';
```

This will load library to your PHP thingy you're writing.

##### Then fire at will

```php
$cpanel = new \eezpal\cPanel_api\cPanel([
			'host' => $host,
			'user' => $user,
			'hash' => $hash
		]);

$accounts = $cpanel->accountsummary(
	 	['user'=>'eezpal']
	 	); 
	 	
var_dump($accounts);
```

Yes this will give you a warning message. So define all the variables at respective places.

Made with ♥ http://eezpal.com
