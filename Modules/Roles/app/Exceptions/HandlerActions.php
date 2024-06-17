<?php

namespace Modules\Roles\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class HandlerActions
{

    /**
     * Register the exception handling callbacks for the application.
     *
     * used by the error handler module to set callbacks for errors
     *
     * return array
     */
    public function getErrorHandlerActions(): array
    {
        $r = [];

        // if the user is not logged in and attempts to access a page that requires login, bounce to the login form
        $r[] = function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return redirect()->route('login');
        };

        // return the array of callbacks
        return $r;
    }
}
