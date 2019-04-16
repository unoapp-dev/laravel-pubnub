<?php

    namespace Unoappdev\PubnubDriver;
    
    use Illuminate\Support\Arr;
    use Illuminate\Support\Str;
    use Illuminate\Broadcasting\Broadcasters\Broadcaster;
    use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
    
    class PubnubBroadcaster extends Broadcaster
    {
        protected $pubnub;
        
        public function __construct($pubnub)
        {
            $this->pubnub = $pubnub;
        }
        
        public function broadcast(array $channels, $event, array $payload = [])
        {
            $payload = [
                'event' => $event,
                'data' => $payload,
                'socket' => Arr::pull($payload, 'socket'),
            ];
            
            $channels = $this->formatChannels($channels);

            $result = $this->pubnub->hereNow()
                ->channels($channels)
                ->includeUuids(false)
                ->includeState(false)
                ->sync();

            foreach ($result->getChannels() as $channelData) {
                if ($channelData->getOccupancy() >= 1) {
                    $this->pubnub->publish()
                        ->channel($channelData->getChannelName())
                        ->message($payload)
                        ->usePost(true)
                        ->sync();
                }
            }
        }
        
        public function auth($request)
        {
            if (Str::startsWith($request->channel_name, ['private-', 'presence-']) &&
                ! $request->user()) {
                throw new AccessDeniedHttpException;
            }
            
            $channelName = Str::startsWith($request->channel_name, 'private-')
                ? Str::replaceFirst('private-', '', $request->channel_name)
                : Str::replaceFirst('presence-', '', $request->channel_name);
            
            return parent::verifyUserCanAccessChannel(
                $request, $channelName
            );
        }
        
        public function validAuthenticationResponse($request, $result)
        {
            if (is_bool($result)) {
                return json_encode($result);
            }
            
            return json_encode(['channel_data' => [
                'user_id' => $request->user()->getAuthIdentifier(),
                'user_info' => $result,
            ]]);
        }
    }
