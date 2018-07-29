<?php
/**
 * Created by PhpStorm.
 * User: jpeterson
 * Date: 7/25/18
 * Time: 11:39 PM
 */

namespace DealPerch\Retail\Client;


use Ramsey\Uuid\UuidInterface;

class Configuration
{
    const EXC_GRANT_TYPE_INVALID_MSG = 'An invalid grant type was provided. Valid grant types are: "client_credentials", "authorization_code", "password", "trusted"';

    const VALID_GRANT_TYPES = ['client_credentials', 'authorization_code', 'password', 'trusted'];

    private $credentialCachePath;

    private $retailBaseURL;

    private $SSOBaseURL;

    private $grantType;

    private $PWGrantUsername;

    private $PWGrantPassword;

    private $trustedGrantUserIdToImpersonate;

    /**
     * Configuration constructor.
     * @param string $credentialCachePath
     * @param string $retailBaseURL
     * @param string $SSOBaseURL
     * @param string $grantType
     * @param string|null $PWGrantUsername
     * @param string|null $PWGrantPassword
     * @param UuidInterface|null $trustedGrantUserIdToImpersonate
     */
    public function __construct($credentialCachePath, $retailBaseURL, $SSOBaseURL, $grantType, string $PWGrantUsername = null, string $PWGrantPassword = null, UuidInterface $trustedGrantUserIdToImpersonate = null)
    {
        if (!file_exists($credentialCachePath)) {
            $handle = fopen($credentialCachePath, 'x');
            if (!is_resource($handle)) {
                throw new \RuntimeException(sprintf('Unable to create credential cache at, "%1$s"', $this->credentialCachePath));
            }
            fclose($handle);
        }

        $this->credentialCachePath = $credentialCachePath;
        $this->retailBaseURL = $retailBaseURL;
        $this->SSOBaseURL = $SSOBaseURL;
        $this->PWGrantUsername = $PWGrantUsername;
        $this->PWGrantPassword = $PWGrantPassword;
        $this->trustedGrantUserIdToImpersonate = $trustedGrantUserIdToImpersonate;

        if (!in_array($grantType, static::VALID_GRANT_TYPES)) {
            throw new \RuntimeException(static::EXC_GRANT_TYPE_INVALID_MSG);
        }
        $this->grantType = $grantType;
    }

    /**
     * @return string
     */
    public function getCredentialCachePath()
    {
        return $this->credentialCachePath;
    }

    /**
     * @return string
     */
    public function getRetailBaseURL()
    {
        return $this->retailBaseURL;
    }

    /**
     * @return string
     */
    public function getSSOBaseURL()
    {
        return $this->SSOBaseURL;
    }

    /**
     * @return string
     */
    public function getGrantType()
    {
        return $this->grantType;
    }

    /**
     * @return null|string
     */
    public function getPWGrantUsername(): ?string
    {
        return $this->PWGrantUsername;
    }

    /**
     * @return null|string
     */
    public function getPWGrantPassword(): ?string
    {
        return $this->PWGrantPassword;
    }

    /**
     * @return null|UuidInterface
     */
    public function getTrustedGrantUserIdToImpersonate(): ?UuidInterface
    {
        return $this->trustedGrantUserIdToImpersonate;
    }
}