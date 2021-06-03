<?php

namespace App\Classes;

use App\Models\TrumpQuote;

/**
 * Class handles incoming API requests for tronalddump.io, via ApiConnector
 */

class TronaldDump extends ApiConnector
{
    public $baseurl = 'https://www.tronalddump.io';
    public $response = '';

    function __construct()
    {
        $this->accept = 'application/json';
    }

    /**
     * Get a random quote from tronalddump.io
     */

    function getRandomQuote()
    {
        $this->url = $this->baseurl . '/random/quote';
        $this->response = $this->get();

        $ids = TrumpQuote::select('quote_id')->pluck('quote_id')->toArray();

        if (!in_array($this->response['quote_id'], $ids)) {
            $this->storeQuote();
        }

        return $this->response;
    }

    /**
     * Get all quote tags from tronalddump.io
     */

    function getQuoteTags()
    {        
        $this->url = $this->baseurl . '/tag';
        $this->response = $this->get();

        return $this->response;
    }

    /**
     * Get quotes by tag from tronalddump.io
     */

    function getTaggedQuotes($value)
    {
        $this->data = ['tag' => $value];
        $this->url = $this->baseurl . '/search/quote';
        $this->response = $this->get();

        return $this->response;
    }

    /**
     * Store response data to the databse
     */

    function storeQuote()
    {
        $quote = new TrumpQuote;

        $tags = json_decode($this->response, true)['tags'];

        if (!array_key_exists(0, $tags)) {
            return $this->response;
        }
        
        $quote->quote_id = $this->response['quote_id'];
        $quote->tag = $this->response['tags'][0];
        $quote->quote = $this->response['value'];

        $quote->save();
    }
}
