<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {
            $isJson = $request->isJson() || $request->wantsJson();
            if ($e->getPrevious() instanceof ModelNotFoundException && $isJson) {
                $modelWithNamespace = explode('\\', $e->getPrevious()->getModel());
                $resource = array_pop($modelWithNamespace);
                return response()->json([
                    'errors' => [
                        'error' => "The requested resource: '${resource}' was not found."
                    ]
                ], 404);
            }
        });

        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            return response()->json([
                'errors' => [
                    'error' => "Access is denied."
                ]
            ], 403);
        });
    }
}
