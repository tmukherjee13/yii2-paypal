<?php

namespace tmukherjee13\paypal;

use common\components\Paypal;
use PayPal\Service\AdaptivePaymentsService;
use PayPal\Types\AP\PayRequest;
use PayPal\Types\AP\Receiver;
use PayPal\Types\AP\ReceiverList;
use PayPal\Types\Common\RequestEnvelope;
use Yii;
require_once Yii::getAlias('@tmukherjee13/paypal/Constants.php');
trait PaypalChained
{

    public function process()
    {
        // $ClientID     = 'AZqbqKawgs_m9kBABM7-BVtmDSDS-1LRZ55J5PSx_C5W51_mQh67003J2vI2rMfk6oQ9egA6QSaujsvT';
        // $ClientSecret = 'EIHYSDKFYF73cFIIn4rCS0E9XpbEoWLyKrfMJyqDc8-m6u8jMy_TBvqz80IoGdyQ7bZOIMHh9hcaq8V0';
        // $config       = array(
        //     'mode'            => 'sandbox',
        //     "acct1.UserName"  => "suvojit.seal.bus.gbp_api1.gmail.com",
        //     "acct1.Password"  => "FXMGECS26KH8MTNL",
        //     "acct1.Signature" => "AFcWxV21C7fd0v3bYYYRCpSSRl31AsZxCO0NSI9fH.KqCt9wIK708qRl",
        //     "acct1.AppId"     => "APP-80W284485P519543T",
        //     'log.LogEnabled'  => YII_DEBUG ? 1 : 0,
        //     'log.FileName'    => Yii::getAlias('@runtime/logs/paypal.log'),
        //     'log.LogLevel'    => 'FINE',
        // );

        // $_receivers = [
        //     [
        //         'Amount'         => '50.00', // Required.  Amount to be paid to the receiver.
        //         'Email'          => 'suvojit.seal-facilitator@indusnet.co.in', // Receiver's email address. 127 char max.
        //         'InvoiceID'      => '', // The invoice number for the payment.  127 char max.
        //         'PaymentType'    => 'DIGITALGOODS', // Transaction type.  Values are:  GOODS, SERVICE, PERSONAL, CASHADVANCE, DIGITALGOODS
        //         'PaymentSubType' => '', // The transaction subtype for the payment.
        //         'AccountID'      => '',
        //         'Phone'          => array('CountryCode' => 'US', 'PhoneNumber' => '4089164280', 'Extension' => ''), // Receiver's phone number.   Numbers only.
        //         'Primary'        => true, // Whether this receiver is the primary receiver.  Values are boolean:  TRUE, FALSE
        //     ],
        //     [
        //         'Amount'         => '5.00', // Required.  Amount to be paid to the receiver.
        //         'Email'          => 'srana@gmail.com', // Receiver's email address. 127 char max.
        //         'InvoiceID'      => '', // The invoice number for the payment.  127 char max.
        //         'PaymentType'    => 'DIGITALGOODS', // Transaction type.  Values are:  GOODS, SERVICE, PERSONAL, CASHADVANCE, DIGITALGOODS
        //         'PaymentSubType' => '', // The transaction subtype for the payment.
        //         'AccountID'      => '',
        //         'Phone'          => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => ''), // Receiver's phone number.   Numbers only.
        //         'Primary'        => false, // Whether this receiver is the primary receiver.  Values are boolean:  TRUE, FALSE
        //     ],

        //     // [
        //     //     'Amount'         => '20.00', // Required.  Amount to be paid to the receiver.
        //     //     'Email'          => 'suvojit.seal@indusnet.co.in', // Receiver's email address. 127 char max.
        //     //     'InvoiceID'      => '', // The invoice number for the payment.  127 char max.
        //     //     'PaymentType'    => 'DIGITALGOODS', // Transaction type.  Values are:  GOODS, SERVICE, PERSONAL, CASHADVANCE, DIGITALGOODS
        //     //     'PaymentSubType' => '', // The transaction subtype for the payment.
        //     //     'AccountID'      => '',
        //     //     'Phone'          => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => ''), // Receiver's phone number.   Numbers only.
        //     //     'Primary'        => FALSE, // Whether this receiver is the primary receiver.  Values are boolean:  TRUE, FALSE
        //     // ]
        // ];

        // $receivers = [];
        // $i         = 0;
        // foreach ($_receivers as $receiver) {
        //     $receivers[$i] = new Receiver();

        //     $receivers[$i]->amount = $receiver['Amount'];
        //     $receivers[$i]->email  = $receiver['Email'];
        //     // $receivers[$i]->invoiceID = $receiver['InvoiceID'];
        //     // $receivers[$i]->paymentType = $receiver['PaymentType'];
        //     // $receivers[$i]->paymentSubType = $receiver['PaymentSubType'];
        //     // $receivers[$i]->accountID = $receiver['AccountID'];
        //     // $receivers[$i]->phone = $receiver['Phone'];
        //     $receivers[$i]->primary = $receiver['Primary'];
        //     $i++;
        // }

        $receiverList = new ReceiverList($this->_receivers);
        $payRequest   = new PayRequest(new RequestEnvelope("en_US"), $this->actionType, $this->cancelUrl, $this->currencyCode, $receiverList, $this->returnUrl);
        // Add optional params
        $payRequest->feesPayer = 'EACHRECEIVER'; //SENDER, PRIMARYRECEIVER, EACHRECEIVER, SECONDARYONLY

        $service  = new AdaptivePaymentsService($this->config);
        $response = $service->Pay($payRequest);
        if (strtoupper($response->responseEnvelope->ack) == 'SUCCESS') {
            $token     = $response->payKey;
            $payPalURL = PAYPAL_REDIRECT_URL . '_ap-payment&paykey=' . $token;
            return Yii::$app->response->redirect($payPalURL);
        }
    }

}
