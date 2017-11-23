<?php
/**
 * Created by PhpStorm.
 * User: hamler
 * Author: Viktoras Bezubec
 * Date: 17.11.22
 * Time: 11.50
 */

namespace AppBundle\Service;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class FacebookInfoService
{
    private $facebook;
    private $accessToken;

    /**
     * FacebookInfoService constructor.
     *
     * @param ContainerInterface $container
     * @param Session $session
     */
    public function __construct(ContainerInterface $container, Session $session)
    {
        $this->accessToken = $session->get('facebook_user_access_token');
        $this->facebook =  new Facebook([
            'app_id' => $container->getParameter('facebook_client_id'),
            'app_secret' => $container->getParameter('facebook_client_secret'),
            'default_graph_version' => $container->getParameter('facebook_graph_api')
        ]);
        $this->facebook->setDefaultAccessToken($this->accessToken);
    }

    /**
     * getPersonInfo method gets info about person from his facebook account
     *
     * @param string $fields
     *
     * @return array
     */
    public function getPersonInfo($fields)
    {
        try {
            $response = $this->facebook->get(
                '/me?fields=' . $fields
            );
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $personInfo = $response->getGraphNode()->asArray();

        return $personInfo;
    }

    /**
     * getUserDataByReference method gets data from person facebook account by reference
     *
     * @param string $reference
     *
     * @return array
     */
    public function getUserDataByReference($reference)
    {
        try {
            $response = $this->facebook->get(
                '/me/' . $reference
            );
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $referenceData = $response->getGraphEdge();
        $dataArray = [];

        if ($this->facebook->next($referenceData)) {
            $dataArray = array_merge($dataArray, $referenceData->asArray());
            while ($referenceData = $this->facebook->next($referenceData)) {
                $dataArray = array_merge($dataArray, $referenceData->asArray());
            }
        } else {
            $dataArray = array_merge($dataArray, $referenceData->asArray());
        }

        return $dataArray;
    }
}
