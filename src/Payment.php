<?php

namespace tmukherjee13\paypal;

use Yii;
use yii\base\Component;
use yii\log\Logger;

class Payment extends Component
{


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

    

   
}
