<?php

/**
 * Copyright (C) Phppot
 *
 * Distributed under 'The MIT License (MIT)'
 * In essense, you can do commercial use, modify, distribute and private use.
 * Though not mandatory, you are requested to attribute Phppot URL in your code or website.
 * 
 * * * 
 * 
 * Library class used for CSRF protection.
 * CSRF is abbreviation for Cross Site Request Forgery.
 * Cross-Site Request Forgery (CSRF) is an attack that forces an end user to execute unwanted actions on a
 * web application in which they're currently authenticated. Defn. by OWASP.
 *
 * User session based token is generated and hashed with their IP address.
 * There are types of operations using which the DDL are executed.
 * Submits using general HTML form and submits using AJAX.
 * We are inserting a CSRF token inside the form and it is validated against the token present in the session.
 * This ensures that the CSRF attacks are prevented.
 *
 * If you are customizing the application and creating a new form,
 * you should ensure that the CSRF prevention is in place. form-footer.php
 * is the file that should be included where the token is to be echoed.
 * After echo the validation of the token happens in controller and it is
 * the common entry point for all calls. So there is no need to do any separate code for
 * CSRF validation with respect to each functionality.
 *
 * The CSRF token is written as a hidden input type inside the html form tag with a label $formTokenLabel.
 *
 * @author Vincy
 * @version 3.5 - IP Address tracking removed as it is good for GDPR compliance.
 *
 */

namespace Source\Support;

/**
 * Class Csrf
 * 
 * @package Source\Support
 */
class Csrf
{
    private $formTokenLabel = 'csrf_token';
    private $sessionTokenLabel = 'CSRF_TOKEN';
    private $serverTokenLabel = 'HTTP_X_CSRF_TOKEN';
    private $post = [];
    private $session = [];
    private $server = [];
    private $excludeUrl = [];
    private $hmac_ip = true;

    /**
     * @param null $excludeUrl
     * @param null $post
     * @param null $session
     * @param null $server
     * 
     * @return void
     */
    public function __construct($excludeUrl = null, &$post = null, &$session = null, &$server = null)
    {
        if (!\is_null($excludeUrl)) {
            $this->excludeUrl = $excludeUrl;
        }

        if (!\is_null($post)) {
            $this->post = &$post;
        } else {
            $this->post = &$_POST;
        }

        if (!\is_null($server)) {
            $this->server = &$server;
        } else {
            $this->server = &$_SERVER;
        }

        if (!\is_null($session)) {
            $this->session = &$session;
        } elseif (!\is_null($_SESSION) && isset($_SESSION)) {
            $this->session = &$_SESSION;
        } else {
            throw new \Error('Nenhuma sessão disponível para continuar');
        }
    }

    /**
     * @return string|null
     */
    public function insertHiddenToken(): string|null
    {
        $csrfToken = $this->getCSRFToken();

        return $this->xssafe($csrfToken);
    }

    /**
     * @return string|null
     */
    public function insertMetaToken(): string|null
    {
        $csrfToken = $this->getCSRFToken();

        return '<meta name="' . $this->formTokenLabel . '" content="' . $this->xssafe($csrfToken) . '" />';
    }

    /**
     * @param string $data
     * @param string $encoding
     * 
     * @return string
     */
    public function xssafe(string $data, string $encoding = 'UTF-8'): string
    {
        return htmlspecialchars($data, ENT_QUOTES | ENT_HTML401, $encoding);
    }

    /**
     * @return string
     */
    public function getCSRFToken(): string
    {
        if (empty($this->session[$this->sessionTokenLabel])) {
            $this->session[$this->sessionTokenLabel] = bin2hex(openssl_random_pseudo_bytes(16));
        }

        if ($this->hmac_ip === true) {
            $token = $this->hMacWithIp($this->session[$this->sessionTokenLabel]);
        } else {
            $token = $this->session[$this->sessionTokenLabel];
        }

        return $token;
    }

    /**
     * @param array|string $token
     * 
     * @return string
     */
    private function hMacWithIp(array|string $token): string
    {
        return \hash_hmac('sha256', base64_decode('R8+K1J6WNxf500kqu7zVvFIjNq+mnHcmaQlE781sSzU='), $token);
    }

    /**
     * @return string
     */
    private function getCurrentRequestUrl(): string
    {
        $protocol = 'http';
        if (isset($this->server['HTTPS'])) {
            $protocol = 'https';
        }

        return $protocol . '://' . $this->server['HTTP_HOST'] . $this->server['REQUEST_URI'];
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $currentUrl = $this->getCurrentRequestUrl();

        if (!in_array($currentUrl, $this->excludeUrl)) {
            if (!empty($this->post)) {
                $isAntiCSRF = $this->validateRequest();
                if (!$isAntiCSRF) {
                    return false;
                }
            }

            return true;
        }
    }

    /**
     * @return bool
     */
    public function isValidRequest(): bool
    {
        $isValid = false;
        $currentUrl = $this->getCurrentRequestUrl();

        if (!in_array($currentUrl, $this->excludeUrl)) {
            if (!empty($this->post)) {
                $isValid = $this->validateRequest();
            }
        }

        return $isValid;
    }

    /**
     * @return bool
     */
    public function validateRequest(): bool
    {
        if (!isset($this->session[$this->sessionTokenLabel])) {
            return false;
        }

        if (!empty($this->post[$this->formTokenLabel])) {
            $token = $this->post[$this->formTokenLabel];
        } else {
            return false;
        }

        if (!\is_string($token)) {
            return false;
        }

        if ($this->hmac_ip !== false) {
            $expected = $this->hMacWithIp($this->session[$this->sessionTokenLabel]);
        } else {
            $expected = $this->session[$this->sessionTokenLabel];
        }

        return \hash_equals($token, $expected);
    }

    /**
     * @return void
     */
    public function unsetToken(): void
    {
        if (!empty($this->session[$this->sessionTokenLabel])) {
            unset($this->session[$this->sessionTokenLabel]);
        }
    }
}
