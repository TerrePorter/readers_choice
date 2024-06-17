<?php

namespace Modules\ErrorHandler\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{


    public function render($request, Throwable $exception)
    {
        if ($this->isHttpRequest($request)) {
            // Custom error handling logic for HTTP requests
        }

        return parent::render($request, $exception);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });


        // get error renderables from all modules
        $modules = app()['modules']->all();
        foreach (array_keys($modules) as $index => $item) {
            $item = strtolower($item);
            $r = config($item.".error_handler");

            if (!empty($r)) {
                $c = app()->make($r);
                $tmp = $c->getErrorHandlerActions();
                foreach ($tmp as $index2 => $item2) {
                    $this->renderable($item2);
                }
            }
        }


/*
        $tmp = [
            0 => function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
                return redirect()->route('login');
            }
        ];

        foreach ($tmp as $index => $item) {
            $this->renderable($item);
        }
*/

        //$this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
//            return redirect()->route('login');
  //      });


    }


    protected function isHttpRequest($request)
    {
        return $request instanceof \Illuminate\Http\Request;
    }
}
