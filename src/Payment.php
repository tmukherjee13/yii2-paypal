<?php

namespace tmukherjee13\paypal;

use Yii;
use yii\base\Component;
use yii\log\Logger;
use yii\base\InvalidConfigException;

class Payment extends Component
{

    use \tmukherjee13\paypal\PaypalChained;
    const METHOD_CHAINED = 'chained';

    public $config = [];
    public $method = self::METHOD_CHAINED;
    public $cancelUrl;
    public $returnUrl;
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

    public function test()
    {
        try {
            $this->process();

            return;
        } catch (Exception $e) {

        }

    }

}
