<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait CookieAuthentication
{
    protected $adminCookie = 'admin-access-token';
    protected $baseCookie = 'base-access-token';

    /**
     * Create a new base cookie.
     * 
     * @param string|null $value
     * @return \Illuminate\Cookie\CookieJar|\Symfony\Component\HttpFoundation\Cookie
     */
    protected function createBaseCookie($value = null)
    {
        return cookie($this->baseCookie, $value, env('SANCTUM_ACCESS_TOKEN_EXPIRES'));
    }

    /**
     * Create a new admin cookie.
     * 
     * @param string|null $value
     * @return \Illuminate\Cookie\CookieJar|\Symfony\Component\HttpFoundation\Cookie
     */
    protected function createAdminCookie($value = null)
    {
        return cookie($this->adminCookie, $value, env('SANCTUM_ACCESS_TOKEN_EXPIRES'));
    }

    protected function forgetBaseCookie()
    {
        return cookie()->expire($this->baseCookie);
    }

    protected function forgetAdminCookie()
    {
        return cookie()->expire($this->adminCookie);
    }

    /**
     * Check if the requests has the cookie.
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $cookieName
     * @param bool
     */
    protected function hasCookie(Request $request, $cookieName)
    {
        return $request->cookie($cookieName) ? true : false;
    }

    /**
     * Check if the user's token match with token in cookie.
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $cookieName
     * @param bool
     */
    protected function isValidToken(Request $request, $cookieName)
    {   
        return $request->bearerToken() === $request->cookie($cookieName);
    }

    protected function hasValidAdminToken(Request $request)
    {
        return $this->hasCookie($request, $this->adminCookie) && $this->isValidToken($request, $this->adminCookie);
    }

    protected function hasValidBaseToken(Request $request)
    {
        return $this->hasCookie($request, $this->baseCookie) && $this->isValidToken($request, $this->baseCookie);
    }
}
