<?php

namespace Tests\AppBundle\Services;

use AppBundle\Services\TwitterApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * TwitterApi unit tests
 */
class TwitterApiTest extends KernelTestCase
{
    /**
     * Test get user tweets passing an username
     * @return null
     */
    public function testGetUserTweetsWithUsername()
    {
        // Start the symfony kernel
        self::bootKernel();

        // Get servcie
        $twitterApi = static::$kernel->getContainer()->get('twitter.api.service');

        $tweets = $twitterApi->getUserTweets('nasa');

        $this->assertTrue(is_array($tweets), 'Result is an array');
        $this->assertCount(10, $tweets, 'Result count is 10');

        foreach ($tweets as $tweet) {
            $this->assertInstanceOf('StdClass', $tweet, 'Each result element is an StdClass object');
            $this->assertObjectHasAttribute('text', $tweet, 'Each result element has text property');
        }
    }

    /**
     * Test get user tweets for default username
     * @return null
     */
    public function testGetUserTweetsWithoutUsername()
    {
        // Start the symfony kernel
        self::bootKernel();

        // Get servcie
        $twitterApi = static::$kernel->getContainer()->get('twitter.api.service');

        $tweets = $twitterApi->getUserTweets();

        $this->assertTrue(is_array($tweets), 'Result is an array');
        $this->assertTrue(array_key_exists('errors', $tweets), 'Result is an array');
    }

    /**
     * Test get user tweets passing count
     * @return null
     */
    public function testGetUserTweetsWithCount()
    {
        // Start the symfony kernel
        self::bootKernel();

        // Get servcie
        $twitterApi = static::$kernel->getContainer()->get('twitter.api.service');

        $tweets = $twitterApi->getUserTweets('nasa', 5);

        $this->assertTrue(is_array($tweets), 'Result is an array');
        $this->assertCount(5, $tweets, 'Result count is 5');

        foreach ($tweets as $tweet) {
            $this->assertInstanceOf('StdClass', $tweet, 'Each result element is an StdClass object');
            $this->assertObjectHasAttribute('text', $tweet, 'Each result element has text property');
        }
    }

    /**
     * Test get user tweets passing count
     * @return null
     */
    public function testGetNonExistentUserTweets()
    {
        // Start the symfony kernel
        self::bootKernel();

        // Get servcie
        $twitterApi = static::$kernel->getContainer()->get('twitter.api.service');

        $tweets = $twitterApi->getUserTweets('nasaerror', 5);

        $this->assertInstanceOf('StdClass', $tweets, 'Result is an StdClass object');
        $this->assertObjectHasAttribute('errors', $tweets, 'Result object has errors attribute');
    }

    /**
     * Test get user tweets text
     * @return null
     */
    public function testGetUserTweetsText()
    {
        // Start the symfony kernel
        self::bootKernel();

        // Get servcie
        $twitterApi = static::$kernel->getContainer()->get('twitter.api.service');

        $tweets = $twitterApi->getUserTweetsText('nasa');

        $this->assertTrue(is_array($tweets), 'Result is an array');
        $this->assertEquals(10, count($tweets), 'Result count is 10');
    }

    /**
     * Test get user tweets of nonexistent username
     * @return null
     */
    public function testGetNonexistentUserTweetsText()
    {
        // Start the symfony kernel
        self::bootKernel();

        // Get servcie
        $twitterApi = static::$kernel->getContainer()->get('twitter.api.service');

        $tweets = $twitterApi->getUserTweetsText('nasaerror', 5);

        $this->assertTrue(is_array($tweets), 'Result is an array');
        $this->assertTrue(array_key_exists('errors', $tweets), 'Array result has errors key');

        foreach ($tweets as $tweet) {
            $this->assertTrue(is_array($tweet[0]), 'Each error result element is an array');
            $this->assertTrue(array_key_exists('code', $tweet[0]), 'Each error result element has code key');
            $this->assertTrue(array_key_exists('message', $tweet[0]), 'Each error result element has message key');
        }
    }
}
