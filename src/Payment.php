<?php

namespace tmukherjee13\paypal;

use PayPal\Service\AdaptivePaymentsService;
use PayPal\Types\AP\ExecutePaymentRequest;
use PayPal\Types\AP\PaymentDetailsRequest;
use PayPal\Types\AP\Receiver;
use PayPal\Types\Common\RequestEnvelope;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\log\Logger;

require_once Yii::getAlias('@tmukherjee13/paypal/Constants.php');
class Payment extends Component
{

    use \tmukherjee13\paypal\PaypalAdaptive;
    const METHOD_CHAINED     = 'chained';
    const METHOD_PARALLEL    = 'parallel';
    const ACTION_PAY         = 'PAY';
    const ACTION_CREATE      = 'CREATE';
    const ACTION_PAY_PRIMARY = 'PAY_PRIMARY';

    public $config     = [];
    public $method     = self::METHOD_CHAINED;
    public $actionType = self::ACTION_PAY;
    public $cancelUrl;
    public $returnUrl;
    public $currencyCode = 'USD';
    public $payKey;

    private $_receivers = [];

    /**
     * Logs a message.
     * @see Logger
     * @param string $message message to be logged.
     * @param integer $level the level of the message.
     */
    protected function log($message, $level = Logger::LEVEL_INFO)
    {
        if (!YII_DEBUG && $level === Logger::LEVEL_INFO) {
            return;
        }
        $category = get_class($this);
        Yii::getLogger()->log($message, $level, $category);
    }

    public function init()
    {
        // $type = 'Paypal'.ucfirst($this->method);
        if ($this->cancelUrl === null) {
            throw new InvalidConfigException('The "cancelUrl" property must be set.');
        }
        if ($this->returnUrl === null) {
            throw new InvalidConfigException('The "returnUrl" property must be set.');
        }
    }

    public function pay($recievers = [])
    {

        $i = 0;
        foreach ($recievers as $receiver) {
            $this->_receivers[$i] = new Receiver();

            $this->_receivers[$i]->amount = $receiver['amount'];
            $this->_receivers[$i]->email  = $receiver['email'];

           
            // $this->_receivers[$i]->invoiceID = $receiver['InvoiceID'];
            // $this->_receivers[$i]->paymentType = $receiver['PaymentType'];
            // $this->_receivers[$i]->paymentSubType = $receiver['PaymentSubType'];
            // $this->_receivers[$i]->accountID = $receiver['AccountID'];
            // $this->_receivers[$i]->phone = $receiver['Phone'];
            if ($this->method === self::METHOD_CHAINED && !empty($receiver['primary'])) {
                $this->_receivers[$i]->primary = $receiver['primary'];
            }else if($this->method === self::METHOD_CHAINED && (!isset($receiver['primary']))){
                throw new InvalidConfigException('The "primary" property of receiver must be set.');
            }

            $i++;
        }

        try {
            $url =  $this->initiate();
            echo $url;
            die(__FILE__);
        } catch (Exception $e) {

        }
    }

    public function getDetails($payKey)
    {

        $requestEnvelope           = new RequestEnvelope("en_US");
        $paymentDetailsReq         = new PaymentDetailsRequest($requestEnvelope);
        $paymentDetailsReq->payKey = $payKey;
        $service                   = new AdaptivePaymentsService($this->config);
        $service->PaymentDetails($paymentDetailsReq);

        try {
            $response = $service->PaymentDetails($paymentDetailsReq);
        } catch (Exception $ex) {

        }
    }

    public function execute($payKey)
    {
        $executePaymentRequest = new ExecutePaymentRequest(new RequestEnvelope("en_US"), $payKey);
        $service               = new AdaptivePaymentsService($this->config);
        try {
            $response = $service->ExecutePayment($executePaymentRequest);
        } catch (Exception $ex) {
        }
    }
}
