<?php
/**
 * Created by PhpStorm.
 * User: jpeterson
 * Date: 7/25/18
 * Time: 11:39 PM
 */

namespace DealPerch\Retail\Client;


class Configuration
{
    const EXC_GRANT_TYPE_INVALID_MSG = 'An invalid grant type was provided. Valid grant types are: "client_credentials", "authorization_code", "password"';

    const VALID_GRANT_TYPES = ['client_credentials', 'authorization_code', 'password'];

    private $credentialCachePath;

    private $retailBaseURL;

    private $SSOBaseURL;

    private $grantType;

    private $PWGrantUsername;

    private $PWGrantPassword;

    /**
     * Configuration constructor.
     * @param string $credentialCachePath
     * @param string $retailBaseURL
     * @param string $SSOBaseURL
     * @param string $grantType
     * @param string|null $PWGrantUsername
     * @param string|null $PWGrantPassword
     */
    public function __construct($credentialCachePath, $retailBaseURL, $SSOBaseURL, $grantType, string $PWGrantUsername = null, string $PWGrantPassword = null)
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


}