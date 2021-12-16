<?php

use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\v1\OAuth\ORCIDController;
use App\Http\Controllers\API\v1\TeamInvitesController;
use App\Http\Controllers\API\v1\UserController;
use App\Http\Controllers\API\v1\TeamsController;
use App\Http\Controllers\API\v1\UserAvatarController;
use App\Http\Controllers\API\v1\UserPasswordController;
use App\Http\Controllers\API\v1\UserTeamsController;
use App\Http\Controllers\API\v1\UserInvitesController;
use App\Http\Controllers\API\v1\UserRepositoryController;
use App\Http\Controllers\API\v1\RepositoryTypesController;
use App\Http\Controllers\API\v1\TeamCollectionResourcesController;
use App\Http\Controllers\API\v1\TeamCollectionsController;
use App\Http\Controllers\API\v1\TeamResourcesCommentsController;
use App\Http\Controllers\API\v1\UserStatsController;
use App\Http\Controllers\API\v1\TeamResourcesController;
use App\Http\Controllers\API\v1\TeamResourcesFilesController;
use App\Http\Controllers\API\v1\TeamResourcesThumbnailsController;
use App\Http\Controllers\API\v1\Integrations\DoiController;
use App\Http\Controllers\API\v1\Integrations\ScioController;
use Illuminate\Support\Facades\Route;

// API v1
Route::prefix('v1')->name('api.v1.')->group(function () {

    // --- OAUTH ROUTES ---
    Route::prefix('oauth')->group(function () {

        // ORCID.
        Route::prefix('orcid')->group(function () {
            Route::get('/', [ORCIDController::class, 'redirect']);
            Route::get('/callback', [ORCIDController::class, 'callback']);
        });

        Route::prefix('globus')->group(function () {
            Route::get('/', function () {
                return response()->json("Not Implemented", 501);
            });
        });
    });

    // Authenticate a user.
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Logout a user.
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Register a new user.
    Route::post('/register', [UserController::class, 'store']);

    // Return the authenticated user or 401.
    Route::get('/auth/token/check', [AuthController::class, 'check']);

    // Authenticated and authorized (store) routes.
    Route::middleware(['auth.jwt'])->group(function () {

        // --- USER ROUTES ---

        // User management.
        Route::apiResource('users', UserController::class)->only(['index', 'show', 'update']);

        // User invites.
        Route::prefix('users/{user}/invites/{invite}')->group(function () {
            Route::post('/accept', [UserInvitesController::class, 'accept']);
            Route::post('/reject', [UserInvitesController::class, 'reject']);
        });
        Route::apiResource('users.invites', UserInvitesController::class)->only(['index']);

        // User statistics.
        Route::apiResource('users.stats', UserStatsController::class)->only(['index']);

        // User avatar management. Issue with file upload using PUT, must use POST.
        Route::get('/users/{user}/avatar', [UserAvatarController::class, 'show']);
        Route::post('/users/{user}/avatar', [UserAvatarController::class, 'update']);
        Route::delete('/users/{user}/avatar', [UserAvatarController::class, 'destroy']);

        // Update user password.
        Route::put('/users/{user}/password', [UserPasswordController::class, 'update']);

        // User owned teams.
        Route::get('/users/{user}/teams/all', [UserTeamsController::class, 'all']);
        Route::apiResource('users.teams', UserTeamsController::class);

        // --- USER REPOSITORY ROUTES ---

        // User repositories.
        Route::get('/users/{user}/repositories/all', [UserRepositoryController::class, 'all']);
        Route::apiResource('users.repositories', UserRepositoryController::class);

        // --- REPOSITORY TYPE ROUTES ---
        Route::apiResource('repository_types', RepositoryTypesController::class)->only('index');

        // --- RESOURCE TYPE ROUTES ---
        // TODO: Fetch them from an external service and provide them to the frontend.

        // --- SHARED TEAMS ROUTES ---

        // All shared team routes.
        Route::get('/teams/all', [TeamsController::class, 'all']);
        Route::post('/teams/{team}/invite', [TeamInvitesController::class, 'store']);
        Route::apiResource('teams', TeamsController::class)->only(['index', 'show', 'destroy']);

        // --- TEAM COLLECTION ROUTES ---
        Route::get('teams/{team}/collections/all', [TeamCollectionsController::class, 'all']);
        Route::apiResource('teams.collections', TeamCollectionsController::class);

        // --- TEAM RESOURCES ROUTES ---
        Route::put('teams/{team}/resources/{resource}/comments', [
            TeamResourcesCommentsController::class, 'update'
        ]);
        Route::post('teams/{team}/resources/{resource}/fairscore', [
            TeamResourcesController::class, 'calculateFairScore'
        ]);
        Route::post('teams/{team}/resources/{resource}/pii_status', [
            TeamResourcesController::class, 'getPIIStatus'
        ]);
        Route::apiResource('teams.resources', TeamResourcesController::class);
        Route::post('teams/{team}/resources/{resource}/files/{file}/accept_pii_terms', [
            TeamResourcesFilesController::class, 'acceptPIITerms'
        ]);
        Route::get('teams/{team}/resources/{resource}/files/{file}/pii_report', [
            TeamResourcesFilesController::class, 'getPIIReport'
        ]);
        Route::apiResource('teams.resources.files', TeamResourcesFilesController::class)
            ->only(['index', 'store', 'destroy']);
        Route::apiResource('teams.resources.thumbnails', TeamResourcesThumbnailsController::class)
            ->only(['index', 'show', 'store', 'destroy']);

        // --- TEAM COLLECTION RESOURCE ROUTES ---
        Route::apiResource('teams.collections.resources', TeamCollectionResourcesController::class);

        // --- THIRD PARTY SERVICE INTEGRATIONS ---
        Route::prefix('integrations')->group(function () {
            Route::post('doi', [DoiController::class, 'checkDoi']);
            Route::post('mimetypes', [ScioController::class, 'getMimetype']);
            Route::get('languages', [ScioController::class, 'listLanguages']);

            // Vocabularies specific stuff.
            Route::prefix('vocabularies')->group(function () {
                Route::get('/', [ScioController::class, 'listVocabularies']);
                Route::get('autocomplete', [ScioController::class, 'autocompleteTerm']);
                Route::post('terms/extract', [ScioController::class, 'extractTerms']);
            });

            // Projects.
            Route::prefix('projects')->group(function () {
                Route::get('/', [ScioController::class, 'listProjects']);
            });
        });
    });
});
