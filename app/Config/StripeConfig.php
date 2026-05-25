<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class StripeConfig extends BaseConfig
{
    public $publishable_key;
    public $secret_key;

    public function __construct()
    {
        parent::__construct();
        $this->publishable_key = getenv('STRIPE_PUBLISHABLE_KEY') ?: '';
        $this->secret_key      = getenv('STRIPE_SECRET_KEY') ?: '';
    }
}
