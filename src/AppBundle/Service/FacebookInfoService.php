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
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class FacebookInfoService
 *
 * @package AppBundle\Service
 */
class FacebookInfoService
{
    /**
     * @var Facebook
     */
    private $facebook;
    /**
     * @var string
     */
    private $accessToken;

    /**
     * FacebookInfoService constructor.
     *
     * @param integer $facebook_client_id
     * @param string  $facebook_client_secret
     * @param string  $facebook_graph_api
     * @param Session $session
     */
    public function __construct(
        $facebook_client_id,
        $facebook_client_secret,
        $facebook_graph_api,
        Session $session
    ) {
        $this->accessToken = $session->get('facebook_user_access_token');
        $this->facebook =  new Facebook(
            [
            'app_id' => $facebook_client_id,
            'app_secret' => $facebook_client_secret,
            'default_graph_version' => $facebook_graph_api,
            ]
        );
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

    /**
     * @return string
     */
    public function getPersonPictureUrl()
    {
        try {
            $response = $this->facebook->get(
                '/me/picture?type=large&redirect=false'
            );
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $pictureDataArray = $response->getGraphNode()->asArray();

        return $pictureDataArray['url'];
    }

    /**
     * @param $facebookId
     *
     * @return bool
     */
    public function isMyFriend($facebookId)
    {
        $friendsIds = $this->getUserDataByReference('friends?fields=id');
        foreach ($friendsIds as $id) {
            if ($id['id'] == $facebookId) {
                return true;
            }
        }

        return false;
    }
}
