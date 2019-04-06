<?php
/**
 * This file is part of the snownight/easyai.
 *
 * (c) snownight <yexueduxing@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyAi\Vision\Ocr;


use EasyAi\Kernel\BaseClient;
use EasyAi\Kernel\Traits\NormalRequest;

/**
 * Class Client
 * @package EasyAi\Vision\Ocr
 */
class Client extends BaseClient
{
    use NormalRequest;

    /**
     * @date 2019/4/1 14:06
     * @param $file
     * @param array $options
     * @param bool $isUrl
     * @return mixed
     */
    public function generalBasic($file, array $options = [], $isUrl = true)
    {
        return $this->basic($file, 'rest/2.0/ocr/v1/general_basic', $options, $isUrl);
    }

    /**
     * @param $file
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:09
     */
    public function accurateBasic($file, array $options = [])
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/accurate_basic', $options);
    }

    /**
     * @param $file
     * @param array $options
     * @param bool $isUrl
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:09
     */
    public function general($file, $options = [], $isUrl = true)
    {
        return $this->basic($file, 'rest/2.0/ocr/v1/general', $options, $isUrl);
    }


    /**
     * @param $file
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:09
     */
    public function accurate($file, array $options = [])
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/accurate', $options);
    }

    /**
     * @param $file
     * @param array $options
     * @param bool $isUrl
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:09
     */
    public function generalEnhanced($file, array $options = [], $isUrl = true)
    {
        return $this->basic($file, 'est/2.0/ocr/v1/general_enhanced', $options, $isUrl);
    }

    /**
     * @param $file
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:09
     */
    public function handwriting($file, array $options = [])
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/handwriting', $options);
    }

    /**
     * @param $file
     * @param bool $cardSide
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:09
     */
    public function idCard($file, $cardSide = true, array $options = [])
    {
        $options = array_merge(['id_card_side' => $cardSide], $options);
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/idcard', $options);
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:09
     */
    public function bankCard($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/bankcard');
    }


    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:09
     */
    public function businessLicense($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/business_license');
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:09
     */
    public function passport($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/passport');
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:09
     */
    public function businessCard($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/business_card');
    }


    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function householdRegister($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/household_register');
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function birthCertificate($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/birth_certificate');
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function hongKongMacauExitentrypermit($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/HK_Macau_exitentrypermit');
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function taiwanExitentrypermit($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/taiwan_exitentrypermit');
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function formOcrRequest($file)
    {
        return $this->checkByImage($file, 'rest/2.0/solution/v1/form_ocr/request');
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function form($file)
    {
        return $this->checkByImage($file, '/rest/2.0/ocr/v1/form');
    }

    /**
     * @param $file
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function receipt($file, array $options = [])
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/receipt', $options);
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function vatInvoice($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/vat_invoice');
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function trainTicket($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/train_ticket');
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function taxiReceipt($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/taxi_receipt');
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function quotaInvoice($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/quota_invoice');
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function drivingLicense($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/driving_license');
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function vehicleLicense($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/vehicle_license');
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function licensePlate($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/license_plate');
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function vehicleInvoice($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/vehicle_invoice');
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function vehicleCertificate($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/vehicle_certificate');
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function vinCode($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/vin_code');
    }

    /**
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:10
     */
    public function qrCode($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/qrcode');
    }

    /**
     * @param $file
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:11
     */
    public function numbers($file, array $options = [])
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/numbers', $options);
    }

    /**
     * @param $file
     * @param array $options
     * @param bool $isUrl
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:11
     */
    public function webImage($file, array $options = [], $isUrl = true)
    {
        if ($isUrl) {
            return $this->checkByUrl($file, 'est/2.0/ocr/v1/webimage', $options);
        }
        return $this->checkByImage($file, 'est/2.0/ocr/v1/webimage', $options);
    }

    /**
     * @param $file
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:11
     */
    public function lottery($file, array $options = [])
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/lottery', $options);
    }

    /**
     * @param $file
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:11
     */
    public function insuranceDocuments($file, $options = [])
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/insurance_documents', $options);
    }

    /**
     * itinerary
     * @param $file
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:11
     */
    public function airTicket($file)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/air_ticket');
    }

    /**
     * General machine invoice identification
     * @param $file
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:11
     */
    public function invoice($file, array $options)
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/invoice', $options);
    }

    /**
     *  Custom template recognition
     * @param $file
     * @param array $options
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:11
     */
    public function recognise($file, array $options = [])
    {
        return $this->checkByImage($file, 'rest/2.0/ocr/v1/recognise', $options);
    }


    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @author snownight
     * @date 2019/4/1 14:11
     */
    public function __call($name, $arguments)
    {
        return $this->checkByUrl(...$arguments);
    }
}