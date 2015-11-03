# yii2-sms

Yii2 SMS extension (基于yii2的短信扩展)

支持接口：

* [创蓝](http://www.cl2009.com/)
* [短信宝](http://api.smsbao.com/)
* [云通讯](http://www.yuntongxun.com/)
* [中国云信](http://www.sms.cn/)
* [中国网建](http://www.smschinese.cn/)
* [商信通](http://www.sxtsms.com/)
* [云片网络](http://www.yunpian.com/)

## 安装


执行

```
$ php composer.phar require lonelythinker/yii2-sms "*"
```

或者添加

```
"lonelythinker/yii2-sms": "*"
```

到  `composer.json` 文件的```require``` 块。

## Usage

```php
return [
    'components' => [
    '' => [
        // 创蓝
        'class' => 'lonelythiker\\yii2\\sms\\Chuanglan',
        'username' => 'username',
        'password' => 'password',
        'useFileTransport' => false
        ]
    ],
];
```

OR

```php
return [
    'components' => [
    '' => [
        // 短信宝
        'class' => 'lonelythiker\\yii2\\sms\\Smsbao',
        'username' => 'username',
        'password' => 'password',
        'useFileTransport' => false
        ]
    ],
];
```

OR
    
```php
return [
    'components' => [
        '' => [
            // 中国云信
            'class' => 'lonelythiker\\yii2\\sms\\Yunxin',
            'username' => 'username',
            'password' => 'password',
            'useFileTransport' => false
        ]
    ],
];
```

OR

```php
return [
    'components' => [
        '' => [
            // 云片网
            'class' => 'lonelythiker\\yii2\\sms\\Yunpian',
            'apikey' => 'apikey',
            'useFileTransport' => false
        ]
    ],
];
```

```php
Yii::$app->->send('15000000000', '短信内容');
```

```php
// 发送模板短信
Yii::$app->->sendByTemplate('15000000000', ['123456'], 1);
```

## License

**yii2-sms** is released under the BSD 3-Clause License. See the bundled `LICENSE` for details.


