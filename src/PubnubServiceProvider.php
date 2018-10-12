<?php
    
    namespace Unoappdev\PubnubDriver;
    
    use PubNub\PubNub;
    use PubNub\PNConfiguration;
    use Illuminate\Support\ServiceProvider;

    class PubnubServiceProvider extends ServiceProvider
    {
        public function boot()
        {
            $this->app['Illuminate\Contracts\Broadcasting\Factory']->extend('pubnub', function ($app, $config) {
                $pnconfig = new PNConfiguration();
                $pnconfig->setSubscribeKey($config['subscribe_key']);
                $pnconfig->setPublishKey($config['publish_key']);
                return new PubnubBroadcaster(new PubNub($pnconfig));
            });
        }
    }