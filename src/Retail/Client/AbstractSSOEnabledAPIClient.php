<?php
declare(strict_types=1);


namespace DealPerch\Retail\Client;

use DealPerch\Retail\Client\Grant\Trusted;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractSSOEnabledAPIClient
{


    protected $configuration;

    /**
     * @var ClientInterface|Client $HTTPClient
     */
    protected $HTTPClient;

    /**
     * @var AbstractProvider
     */
    protected $authProvider;

    /**
     * @var AccessToken|null
     */
    private $credentials;

    /**
     * @var UuidInterface|null
     */
    private $impersonateUserId;

    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    /**
     * @return AbstractProvider
     */
    public function getAuthProvider(): AbstractProvider
    {
        return $this->authProvider;
    }

    /**
     * @param UuidInterface $userId
     * @abstract works for trusted grant type
     */
    public function updateUserIdToImpersonate(UuidInterface $userId): void
    {
        $this->forceClearCredentials();
        $this->impersonateUserId = $userId;
    }

    public function getImpersonatingUserId(): ?UuidInterface
    {
        return $this->impersonateUserId;
    }

    public function forceClearCredentials(): void
    {
        $this->credentials = null;
        $this->cacheCredentials('');
    }


    public function readCachedCredentials(): ?string
    {
        if (!is_readable($this->getConfiguration()->getCredentialCachePath())) {
            throw new \RuntimeException(sprintf('Unable to read credential cache at, "%1$s"', $this->getConfiguration()->getCredentialCachePath()));
        }

        if (!is_writable($this->getConfiguration()->getCredentialCachePath())) {
            throw new \RuntimeException(sprintf('Unable to write credential cache at, "%1$s"', $this->getConfiguration()->getCredentialCachePath()));
        }

        $handle = fopen($this->getConfiguration()->getCredentialCachePath(), 'r');
        if (!is_resource($handle)) {
            throw new \RuntimeException('Unable to open up the credential cache.');
        }


        $filesize = filesize($this->getConfiguration()->getCredentialCachePath());
        if ($filesize === 0) {
            return null;
        }

        $out = fread($handle, $filesize);

        if ($out === false) {
            return null;
        }

        fclose($handle);
        return $out;
    }


    public function cacheCredentials(string $credentials): void
    {

        if (!is_writable($this->getConfiguration()->getCredentialCachePath())) {
            throw new \RuntimeException(sprintf('Unable to write credential cache at, "%1$s"', $this->getConfiguration()->getCredentialCachePath()));
        }

        $handle = fopen($this->getConfiguration()->getCredentialCachePath(), 'r+');
        if (!is_resource($handle)) {
            throw new \RuntimeException('Unable to open up the credential cache.');
        }


        $locked = flock($handle, LOCK_EX);
        if ($locked === false) {
            throw new \RuntimeException('unable to lock the cache credentials path. Perhaps another thread is doing the same thing at the same time.');
        }

        $successfulWrite = fwrite($handle, $credentials);
        fflush($handle);
        if ($successfulWrite === false) {
            throw new \RuntimeException('unable to cache credentials to disk.');
        }
        fclose($handle);
        return;
    }

    private function fetchAccessToken(): string
    {
        if (!$this->credentials instanceof AccessToken) {
            // attempt to read cached credentials
            $cachedCredentialsAsArr = json_decode($this->readCachedCredentials() ?? '', true);
            if (is_array($cachedCredentialsAsArr)) {
                $this->credentials = new AccessToken($cachedCredentialsAsArr);
            }
        }

        switch ($this->getConfiguration()->getGrantType()) {
            case 'password':
                $grantOptions = [
                    'username' => $this->getConfiguration()->getPWGrantUsername(),
                    'password' => $this->getConfiguration()->getPWGrantPassword()
                ];
                break;

            case 'trusted':
                $grant = new Trusted();
                $grantOptions = [
                    'id' => ($this->getImpersonatingUserId() instanceof UuidInterface ? $this->getImpersonatingUserId()->toString() : null)
                ];
                break;

            default:
                $grantOptions = [];
        }

        if (!$this->credentials instanceof AccessToken) {
            $accessToken = $this->getAuthProvider()->getAccessToken($grant ?? $this->getConfiguration()->getGrantType(), $grantOptions);
            $this->credentials = $accessToken;
            $this->cacheCredentials(json_encode($accessToken->jsonSerialize(), JSON_PRETTY_PRINT));
        }

// validate the credentials aren't expired. If they are, refresh.
        if ($this->credentials->hasExpired()) {
            $accessToken = $this->authProvider->getAccessToken($this->getConfiguration()->getGrantType(), $grantOptions);
            $this->credentials = $accessToken;
            $this->cacheCredentials(json_encode($accessToken->jsonSerialize(), JSON_PRETTY_PRINT));
        }
        return $this->credentials->getToken();
    }

    public function getAuthorizationHeaderValue(): string
    {
        return 'Bearer ' . $this->fetchAccessToken();
    }


}