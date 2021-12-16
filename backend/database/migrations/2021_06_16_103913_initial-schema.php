<?php

use App\Enums\IdentityProvider;
use App\Enums\PIIStatus;
use App\Enums\RepositoryType;
use App\Enums\ResourceStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitialSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropColumns('users', [
            'name',
            'email',
            'password',
            'email_verified_at',
            'created_at',
            'updated_at'
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->unique()->after('id');
            $table->string('password')->nullable()->after('email');
            $table->enum('identity_provider', IdentityProvider::getValues())
                ->default(IdentityProvider::SCRIBE);
            $table->string('identity_provider_external_id')->nullable()->default(null);
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->enum('role', ['repo_manager', 'team_member'])->default('repo_manager');
            $table->string('avatar_url')->nullable();
            $table->string('ui_language')->default('en');
            $table->string('ui_language_display_format')->default('endonym');
            $table->string('ui_date_display_format')->default('YY-MM-DD');
            $table->timestamps();
        });

        Schema::create('user_repositories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->enum('type', RepositoryType::getValues())->nullable();
            $table->string('name');
            $table->string('api_endpoint');
            $table->string('client_secret')->nullable();
            $table->boolean('connection_verified')->default(false);
            $table->timestamps();
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('team_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')
                ->constrained('teams')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->timestamps();
            $table->unique(['team_id', 'user_id']);
        });

        Schema::create('invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')
                ->constrained('teams')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();

            // Indexes
            $table->unique(['team_id', 'user_id']);
        });

        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')
                ->constrained('teams')
                ->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('inherit_information_to_resources')->default(false);
            $table->boolean('keywords_extracted_from_resources')->default(true);
            $table->boolean('publish_as_catalogue_of_resources')->default(false);
            $table->string('doi')->nullable();
            $table->string('publisher')->nullable();
            $table->timestamp('embargo_date')->nullable();
            $table->boolean('geospatial_coverage_calculated_from_resources')->default(false);
            $table->boolean('temporal_coverage_calculated_from_resources')->default(false);
            $table->float('findable_score')->default(0);
            $table->float('accessible_score')->default(0);
            $table->float('interoperable_score')->default(0);
            $table->float('reusable_score')->default(0);
            $table->float('fair_scoring')->default(0);
            $table->timestamps();
        });

        Schema::create('collection_keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')
                ->constrained('collections')
                ->onDelete('cascade');
            $table->string('keyword');
            $table->timestamps();
        });

        Schema::create('collection_geospatial_coverages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')
                ->constrained('collections')
                ->onDelete('cascade');
            $table->string('country');
            $table->timestamps();
        });

        Schema::create('collection_temporal_coverages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')
                ->constrained('collections')
                ->onDelete('cascade');
            $table->enum('type', ['period', 'timepoint']);
            $table->text('description')->nullable();
            $table->timestamp('from_date')->nullable();
            $table->timestamp('to_date')->nullable();
            $table->timestamps();
        });

        // TODO: Finish the table structure.
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')
                ->constrained('teams')
                ->onDelete('cascade');
            $table->string('version');
            $table->string('external_metadata_record_id')->nullable();
            $table->string('title');
            $table->text('description');
            $table->string('type')->nullable();
            $table->string('subtype')->nullable();
            $table->enum('status', ResourceStatus::getValues())->default(ResourceStatus::DRAFT);
            $table->float('findable_score')->default(0);
            $table->float('accessible_score')->default(0);
            $table->float('interoperable_score')->default(0);
            $table->float('reusable_score')->default(0);
            $table->float('fair_scoring')->default(0);
            $table->text('comments')->nullable();
            // TODO: Maybe include information regarding who published the resource?
            $table->timestamp('published_at')->nullable()->default(null);
            $table->timestamp('issued_at')->nullable()->default(null);
            $table->foreignId('author_id')
                ->constrained('users');
            $table->foreignId('publisher_id')
                ->nullable()
                ->constrained('users')
                ->default(null);
            $table->timestamps();

            $table->unique(['id', 'version']);
            $table->index(['status']);
        });

        Schema::create('collection_resource', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')
                ->constrained('collections')
                ->onDelete('cascade');
            $table->foreignId('resource_id')
                ->constrained('resources')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('resource_authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')
                ->constrained('resources')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->unique(['resource_id', 'user_id']);
            $table->timestamps();
        });

        Schema::create('resource_reviewers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')
                ->constrained('resources')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->unique(['resource_id', 'user_id']);
            $table->timestamps();
        });

        Schema::create('resource_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')
                ->constrained('resources')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->string('filename');
            $table->string('path');
            $table->string('pii_check_status')->default(PIIStatus::PENDING);
            $table->string('pii_check_status_identifier')->nullable()->default(null);
            $table->timestamp('pii_terms_accepted_at')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::create('resource_thumbnails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')
                ->constrained('resources')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->string('filename');
            $table->string('path');
            $table->string('pii_check_status')->default(PIIStatus::PENDING);
            $table->string('pii_check_status_identifier')->nullable()->default(null);
            $table->timestamp('pii_terms_accepted_at')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('users', [
            'email',
            'identity_provider',
            'identity_provider_external_id',
            'firstname',
            'lastname',
            'role',
            'avatar_url',
            'ui_language',
            'ui_language_display_format',
            'ui_date_display_format',
        ]);
        Schema::table('users', function (Blueprint $table) {
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
        });
        Schema::drop('user_repositories');
        Schema::drop('collection_keywords');
        Schema::drop('collection_geospatial_coverages');
        Schema::drop('collection_temporal_coverages');
        Schema::drop('invites');
        Schema::drop('team_user');
        Schema::drop('collection_resource');
        Schema::drop('resource_authors');
        Schema::drop('resource_reviewers');
        Schema::drop('resource_files');
        Schema::drop('resource_thumbnails');
        Schema::drop('collections');
        Schema::drop('teams');
        Schema::drop('resources');
    }
}
