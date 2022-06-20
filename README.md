# Laravel-kudaopenapi
 fast payment gateway in Nigeria


<p align="center">
<a href="https://packagist.org/packages/diadal/laravel-kudaopenapi">
<img src="https://poser.pugx.org/diadal/laravel-kudaopenapi/d/total.svg" alt="Total Downloads">
</a>

<a href="https://packagist.org/packages/diadal/laravel-kudaopenapi">
<img src="https://poser.pugx.org/diadal/laravel-kudaopenapi/license.svg" alt="License">
</a>
</p>




This package provides a simple way to work with Kuda Open Api. To learn all about it, head over to [Kuda Open Api documentation](https://kudabank.gitbook.io/).

## Installation

### With Composer

```
$ composer require diadal/laravel-kudaopenapi
```

```
php artisan vendor:publish --provider="Diadal\Kuda\KudaServiceProvider"

```
## Useage
 `.evn`
```php

KUDA_PRIVATE_KEY= <RSAKeyValue>******************
KUDA_PUBLIC_KEY= <RSAKeyValue>-******************
KUDA_CLIENT_KEY= ******************
KUDA_BASE_URL= "https://kuda-openapi.kuda.com/v1​"

```

`Controller`
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Diadal\Kuda\KudaOpenApi;


class InvoiceController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        ...
        $this->KudaOpenApi = new KudaOpenApi();
    }




```
Examples of what you can do:

```php

public function GetBankList()
    {

        $data = 'NG';
        $data = $data;
        return $this->KudaOpenApi->GetBankList($data);
    }


```


```php
// this work with any motheds Api called  mainData is default KudaOpenApi data or payload
public function OtherMethods()
    {

        $data = [];
        return $this->KudaOpenApi->OtherMethods($data);
    }
```



## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

