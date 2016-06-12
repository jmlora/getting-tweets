<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\View\View as FOSView;
use FOS\RestBundle\Util\Codes;

/**
 * @RouteResource("Tweet")
 */
class TweetsController extends FOSRestController
{
    /**
     * @param ParamFetcherInterface $paramFetcher
     * @return Response
     * 
     * @View(serializerEnableMaxDepthChecks=true)
     * 
     * @QueryParam(name="username", nullable=false, description="Username.")
     * @QueryParam(name="count", nullable=true, default=10, description="Username.")
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher)
    {
        $username = $paramFetcher->get('username');
        $count = $paramFetcher->get('count');

        if ($username == null) {
            return FOSView::create(array('error' => 'Username query param is required'), Codes::HTTP_BAD_REQUEST);
        }

        $twitterApi = $this->get('twitter.api.service');

        $tweets = $twitterApi->getUserTweetsText($username, $count);

        if (array_key_exists('errors', $tweets)) {
            return FOSView::create($tweets, Codes::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $tweets;
    }
}
