<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace SocialConnect\Auth;

interface AccessTokenInterface
{
    /**
     * @return string|null
     */
    public function getToken();

    /**
     * @return integer|null
     */
    public function getUserId();
}
