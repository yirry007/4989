<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/we_notify/*',
        '/topup_notify/*',
		'weixin/listen',
        '/upload_one/*',
        '/user_upload/*',
        '/upload_headimg',
        '/product_add',
    ];
}
