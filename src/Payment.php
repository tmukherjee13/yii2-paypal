<?php

namespace tmukherjee13\paypal;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\log\Logger;
use PayPal\Types\AP\Receiver;

class Payment extends Component
{

    use \tmukherjee13\paypal\PaypalChained;
    const METHOD_CHAINED = 'chained';
    const ACTION_PAY = 'PAY';
    const ACTION_CREATE = 'CREATE';
    const ACTION_PAY_PRIMARY = 'PAY_PRIMARY';

    public $config = [];
    public $method = self::METHOD_CHAINED;
    public $actionType = self::ACTION_PAY;
    public $cancelUrl;
    public $returnUrl;
    public $currencyCode = 'USD';

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
        // echo "<pre>";
        // print_r($recievers);
        // echo "</pre>";
        // die;

        $i = 0;
        foreach ($recievers as $receiver) {
            $this->_receivers[$i] = new Receiver();

            $this->_receivers[$i]->amount = $receiver['Amount'];
            $this->_receivers[$i]->email  = $receiver['Email'];
            // $this->_receivers[$i]->invoiceID = $receiver['InvoiceID'];
            // $this->_receivers[$i]->paymentType = $receiver['PaymentType'];
            // $this->_receivers[$i]->paymentSubType = $receiver['PaymentSubType'];
            // $this->_receivers[$i]->accountID = $receiver['AccountID'];
            // $this->_receivers[$i]->phone = $receiver['Phone'];
            $this->_receivers[$i]->primary = $receiver['Primary'];
            $i++;
        }

        try {
            $this->process();
            return;
        } catch (Exception $e) {

        }
    }
}
