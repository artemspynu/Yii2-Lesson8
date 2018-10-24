<?php

namespace app\components;

use yii\base\Component;

class TestService extends Component
{
    public $prop = 'firstProp';

    public function run()
    {
        return $this->prop;
    }
}