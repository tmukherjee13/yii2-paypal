<?php

namespace tmukherjee13\paypal;

use common\components\Paypal;
use PayPal\Service\AdaptivePaymentsService;
use PayPal\Types\AP\PayRequest;
use PayPal\Types\AP\ReceiverList;
use PayPal\Types\Common\RequestEnvelope;
trait PaypalAdaptive
{

    public function initiate()
    {
        $receiverList          = new ReceiverList($this->_receivers);
        $payRequest            = new PayRequest(new RequestEnvelope("en_US"), $this->actionType, $this->cancelUrl, $this->currencyCode, $receiverList, $this->returnUrl);
        $payRequest->feesPayer = 'EACHRECEIVER'; //SENDER, PRIMARYRECEIVER, EACHRECEIVER, SECONDARYONLY

        $service  = new AdaptivePaymentsService($this->config);
        $response = $service->Pay($payRequest);
        if (strtoupper($response->responseEnvelope->ack) == 'SUCCESS') {
            $token        = $response->payKey;
            $this->payKey = $token;
            $payPalURL    = PAYPAL_REDIRECT_URL . '_ap-payment&paykey=' . $token;
            return $payPalURL;
        }else{
            echo "<pre>";
            print_r($response);
            echo "</pre>";
            die;
        }
    }

}
