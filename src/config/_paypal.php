<?php
return [
    'components' => [
        'paypal' => [
            'class'  => 'tmukherjee13\paypal\Payment',
            'config' => [
                "mode"            => "sandbox",
                "acct1.UserName"  => "suvojit.seal.bus.gbp_api1.gmail.com",
                "acct1.Password"  => "FXMGECS26KH8MTNL",
                "acct1.Signature" => "AFcWxV21C7fd0v3bYYYRCpSSRl31AsZxCO0NSI9fH.KqCt9wIK708qRl",
                "acct1.AppId"     => "APP-80W284485P519543T",
                "log.LogEnabled"  => YII_DEBUG ? 1 : 0,
                "log.FileName"    => Yii::getAlias("@runtime/logs/paypal.log"),
                "log.LogLevel"    => "FINE",
            ],
        ],
    ],
];
