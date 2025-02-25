<?php

namespace Kinde\KindeSDK\Sdk\OAuth2;

use Kinde\KindeSDK\KindeClientSDK;
use Kinde\KindeSDK\Sdk\Enums\GrantType;
use Kinde\KindeSDK\Sdk\Enums\StorageEnums;
use Kinde\KindeSDK\Sdk\Storage\Storage;
use Kinde\KindeSDK\Sdk\Utils\Utils;

class AuthorizationCode
{
    /**
     * @var Storage
     */
    protected $storage;

    public function __construct()
    {
        $this->storage = Storage::getInstance();
    }

    public function authenticate(KindeClientSDK $clientSDK, array $additionalParameters = [])
    {
        $state = Utils::randomString();
        $this->storage->setState($state);
        $searchParams = [
            'client_id' => $clientSDK->clientId,
            'grant_type' => GrantType::authorizationCode,
            'redirect_uri' => $clientSDK->redirectUri,
            'response_type' => 'code',
            'scope' => $clientSDK->scopes,
            'state' => $state,
            'start_page' => 'login'
        ];
        $mergedAdditionalParameters = Utils::addAdditionalParameters($clientSDK->additionalParameters, $additionalParameters);
        $searchParams = array_merge($searchParams, $mergedAdditionalParameters);

        return redirect($clientSDK->authorizationEndpoint . '?' . http_build_query($searchParams))
            ->cookie(Storage::$prefix . '_' . StorageEnums::STATE, $state, now()->addHours(2)->timestamp);
    }
}
