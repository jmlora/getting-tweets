<?php

namespace AppBundle\Services;

use Endroid\Twitter\Twitter;

/**
 * Service to manage Twitter API calls
 */
class TwitterApi
{
    /**
     * Endroid Twitter Service
     * @var Endroid\Twitter\Twitter
     */
    protected $endroidTwitter;

    protected $memcache;

    /**
     * Constructor
     * @param string $endroidTwitter Consumer key (defined in parameters)
     * @param string $secretKey   Consumer secret key (defined in parameters)
     */
    public function __construct(Twitter $endroidTwitter, $memcache)
    {
        $this->endroidTwitter = $endroidTwitter;
        $this->memcache = $memcache;
    }

    /**
     * Returns tweets from the given user
     * @param  string  $screenUsername  Username to get tweets (required)
     * @param  integer $count           Number of tweets to return (optional, default 10)
     * @return StdClas|array            StdClass with tweets or array with errors
     */
    public function getUserTweets($screenUsername = null, $count = 10)
    {
        if ($screenUsername == null) {
            return array('errors' => 'Username is required');
        }

        // Memcache key is compound by screen username and count
        $cacheKey = $screenUsername . '-' . $count;

        if (false === $tweets = $this->memcache->get($cacheKey)) {
            $tweets = $this->endroidTwitter->getTimeline(array(
                'count' => $count,
                'screen_name' => $screenUsername
            ));

            $this->memcache->set($cacheKey, $tweets, 0, 300);
        }
        
        return $tweets;
    }

    /**
     * Returns tweet texts from the given user
     * @param  string  $screenUsername  Username to get tweets (required)
     * @param  integer $count           Number of tweets to return (optional, default 10)
     * @return array                    Array with tweet texts or errors
     */
    public function getUserTweetsText($screenUsername = null, $count = 10)
    {
        $tweets = $this->getUserTweets($screenUsername, $count);
        
        if (!is_array($tweets)) {
            return json_decode(json_encode($tweets), true);
        }

        $texts = array_map(function($item){ return $item->text;}, $tweets);

        return $texts;
    }
}
