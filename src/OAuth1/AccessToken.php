<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace SocialConnect\OAuth1;

use SocialConnect\Auth\AccessTokenInterface;

class AccessToken extends \SocialConnect\OAuth1\Token implements AccessTokenInterface
{
    /**
     * @var integer
     */
    protected $userId;

    /**
     * @var string
     */
    protected $screenName;

    /**
     * @var int
     */
    protected $x_auth_expires = 0;

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getScreenName()
    {
        return $this->screenName;
    }

    /**
     * @param $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param $screenName
     */
    public function setScreenName($screenName)
    {
        $this->screenName = $screenName;
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        // Not supported for OAuth1, only getUserId()
        return null;
    }
}
