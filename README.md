<h1 align="center"> snownight/easyai </h1>

<p align="center"> snownight/easyai 是一个基于百度开放ai能力的sdk开发组件，
目前主要提供了百度ai的文本审核能力、文字识别能力、人脸识别能力、图像审核能力.</p>


## Installing

```shell
$ composer require snownight/easyai -vvv
```

## Usage
```php
$config = [
            'app_id' => 'your baidu appid',
            'app_key' => 'your baidu appid appkey',
            'secret' => 'your baidu appid appkey',
            'response_type'=>'array|collection|json' //default is array
        ];
$app = Factory::vision($config);
$app->ocr->general('file or file url');
```

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/snownight/easyai/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/snownight/easyai/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT