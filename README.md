<h1 align="center"> snownight/easyai </h1>

<p align="center"> snownight/easyai 是一个基于百度开放ai能力的sdk开发组件，
目前主要提供了百度ai的文本审核能力、文字识别能力、人脸识别能力、图像审核能力.</p>


## 安装

```shell
$ composer require snownight/easyai -vvv
```

## 示例
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

## API 对照表
### 文字识别
|Api|方法|
|---|---|
|通用文字识别|$app->ocr->generalBasic|
| 通用文字识别高精度版|$app->ocr->accurateBasic|
|通用文字识别含高精度版|$app->ocr->accurate|
|通用文字识别含生僻字版|$app->ocr->generalEnhanced|
|手写文字识别|$app->ocr->handwriting|
|身份证识别|$app->ocr->idcard|
|银行卡识别|$app->ocr->bankcard|
|营业执照识别|$app->ocr->businessLicense|
|名片识别|$app->ocr->businessCard|
|户口本识别|$app->ocr->householdRegister|
|出生医学证明识别|$app->ocr->birthCertificate|
|港澳通行证识别|$app->ocr->hongKongMacauExitentrypermit|
|台湾通行证识别|$app->ocr->taiwanExitentrypermit|
|表格文字识别(异步接口)|$app->ocr->formOcrRequest|
|表格文字识别(同步接口)|$app->ocr->form|
|表通用票据识别|$app->ocr->receipt|
|增值税发票识别|$app->ocr->vatInvoice|
|火车票识别|$app->ocr->trainTicket|
|出租车票识别|$app->ocr->taxi_Receipt|
|定额发票识别|$app->ocr->quotaInvoice|
|驾驶证识别|$app->ocr->drivingLicense|
|行驶证识别|$app->ocr->vehicleLicense|
|车牌识别|$app->ocr->licensePlate|
|机动车销售发票识别|$app->ocr->vehicleInvoice|
|车辆合格证识别|$app->ocr->vehicleCertificate|
|VIN码识别|$app->ocr->vinCode|
|二维码识别|$app->ocr->qrcode|
|数字识别|$app->ocr->numbers|
|网络图片文字识别|$app->ocr->webimage|
|彩票识别|$app->ocr->lottery|
|保险单识别|$app->ocr->insuranceDocuments    |
|通用机打发票识别|$app->ocr->invoice|
|行程单识别|$app->ocr->airTicket|

###人脸识别
|Api|方法|
|---|---|
|人脸检测与属性分析|$app->face->detect|
|人脸对比|$app->face->match|
|人脸搜索|$app->face->search|
|人脸搜索 M:N 识别|$app->face->multiSearch|
|人脸注册|$app->face->add|
|人脸更新|$app->face->update|
|人脸删除|$app->face->delete|
|用户信息查询|$app->face->get|
|获取用户人脸列表|$app->face->getlist|
|获取用户列表|$app->face->getusers|
|复制用户|$app->face->copy|
|删除用户|$app->face->userDelete|
|创建用户组|$app->face->groupAdd|
|删除用户组|$app->face->groupDelete|
|组列表查询|$app->face->groupList|
|公安验证|$app->face->personVerify|
|身份证与名字比对|$app->face->idmatch|
|在线活体检测|$app->face->faceverify|
|语音校验码接口|$app->face->voiceSessionCode|
|视频活体检测接口|$app->face->faceLivenessVerify|
|人脸融合|$app->face->faceMerge|
### 图像审核
|Api|方法|
|---|---|
|短视频审核接口|$app->image_audit->videoCensor|
|组合服务接口|$app->image_audit->imageCensor|
### 图像处理
|Api|方法|
|---|---|
|图像无损放大|$app->image_process->imageQualityEnhance|
|图像去雾|$app->image_process->dehaze|
|图像对比度增强|$app->image_process->contrastEnhance|
|黑白图像上色|$app->image_process->colourize|
|拉伸图像恢复|$app->image_process->stretchRestore|
|图像风格转换|$app->image_process->styleTrans|
### 文本处理
|Api|方法|
|---|---|
|文本审核|$app->text->spam|
## License

MIT