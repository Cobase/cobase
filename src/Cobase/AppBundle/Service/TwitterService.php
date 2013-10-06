<?php

namespace Cobase\AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use TwitterAPIExchange;

class TwitterService
{
    /**
     * @param array $hashKeys
     * @return array
     */
    public function getTweetsByHashKeys(Array $hashKeys, Array $settings = null)
    {
        if ($settings['twitter_enabled'] === false) {
            return array();
        }

        if ($settings === null) {
            return array();
        }

        $tweets = $this->getTweets($hashKeys, $settings);

        $tweetsArray = $this->processTweetList($tweets);

        return $tweetsArray;
    }

    /**
     * @param array $hashKeys
     * @param array $settings
     * @return string
     */
    private function getTweets(Array $hashKeys, Array $settings)
    {
        $hashQuery = implode('+OR+', $hashKeys);

        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        $requestMethod = 'GET';
        $getfield = '?q=' . $hashQuery . '&result_type=recent';

        $twitter = new TwitterAPIExchange($settings);
        $tweets = $twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

        return $tweets;
    }

    /**
     * @param $tweets
     * @return array
     */
    private function processTweetList($tweets)
    {
        $tweetList = array();
        $tweetArray = json_decode($tweets);

        if ($tweetArray) {
            foreach($tweetArray->statuses as $tweet) {
                $tweetList[] = $tweet->text;
            }
        }

        return $tweetList;
    }
}
