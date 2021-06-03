<?php

namespace App\Classes;

use Illuminate\Support\Facades\Http as Guzzle;

class ApiConnector
{    
    public $url = ''; // Request URI of external server
    public $data = []; // Additional data send as POST request fields in JSON or GET request URL query parameters
    public $form = false; // Send application/x-www-form-urlencoded data
    // Guzzle request setup
    public $accept = ''; // For example: 'application/json'
    public $headers = []; // Set headers as key / value pairs
    public $token = ''; // A bearer token
    public $timeout = 0; // Seconds before response timeout
    public $auth = ''; // Can be set to 'basic' or 'digest' for corresponding authentication methods
    public $user = ''; // Username
    public $password = ''; // User password
    // Class settings
    private $client; // The HTTP client
    private $options = false; // Call request method with options

    /**
     * Setup Guzzle as HTTP client with class properties 
     */

    public function setupGuzzle()
    {
        $this->client = new Guzzle;

        switch (true) {
            case $this->accept:
                $this->client = $this->client::accept($this->accept);
                $this->options = true;
            case $this->headers:
                $this->client = $this->options ? $this->client->withHeaders($this->headers) : $this->client::withHeaders($this->headers);
                $this->options = true;
            case $this->token:
                $this->client = $this->options ? $this->client->withToken($this->token) : $this->client::withToken($this->token);
                $this->options = true;
            case $this->timeout:
                $this->client = $this->options ? $this->client->timeout($this->timeout) : $this->client::timeout($this->timeout);
                $this->options = true;
            case $this->auth == 'basic':
                $this->client = $this->options ? $this->client->withBasicAuth($this->user, $this->password) : $this->client::withBasicAuth($this->user, $this->password);
                $this->options = true;
            case $this->auth == 'digest':
                $this->client = $this->options ? $this->client->withDigestAuth($this->user, $this->password) : $this->client::withDigestAuth($this->user, $this->password);
                $this->options = true;
        }
    }

    /**
     * Perform a GET request with Guzzle set up as client
     */

    public function get()
    {
        $this->setupGuzzle();

        if ($this->options) {
            $response = $this->client->get($this->url, $this->data);
        } else {
            $response = $this->client::get($this->url, $this->data);
        }

        return $response;
    }

    /**
     * Perform a POST request with Guzzle set up as client
     */

    public function post()
    {
        $this->setupGuzzle();

        if ($this->form) {
            $this->client = $this->options ? $this->client->asForm() : $this->client::asForm();
            $this->options = true;
        }

        if ($this->options) {
            $response = $this->client->post($this->url, $this->data);
        } else {
            $response = $this->client::post($this->url, $this->data);
        }

        return $response;
    }
}
