<?php

namespace Database\Seeders;

use DB;
use Schema;
use Storage;
use Exception;
use App\Models\Team;
use App\Models\User;
use Faker\Generator;
use App\Models\Invite;
use App\Models\Resource;
use App\Models\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Container\Container;

class InitialSeeder extends Seeder
{
    /**
     * The current Faker instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Create a new seeder instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    /**
     * Get a new Faker instance.
     *
     * @return \Faker\Generator
     */
    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('teams')->truncate();
        DB::table('team_user')->truncate();
        DB::table('collections')->truncate();
        DB::table('resources')->truncate();
        DB::table('collection_resource')->truncate();
        DB::table('resource_files')->truncate();
        DB::table('resource_thumbnails')->truncate();
        DB::table('invites')->truncate();
        DB::connection('mongodb')->table('metadata_records')->truncate();
        DB::disableQueryLog();

        $user = User::findOrFail(1);
        $email = $user->email;

        // Demo users
        $users = User::factory(['password' => bcrypt('password')])
            ->count(5)
            ->create();

        $orcidUser = User::factory([
            'email' => null,
            'firstname' => 'Orcid',
            'lastname' => 'User',
            'password' => null,
            'identity_provider' => 'orcid',
            'identity_provider_external_id' => '0000-0002-8769-4783'
        ])->create();

        $recordNotFound = false;
        $record = null;
        try {
            $record = Storage::disk('local')->get('record.json');
        } catch (Exception $ex) {
            $recordNotFound = true;
        }

        $team = Team::factory(['owner_id' => $user->id])
            ->count(1)
            ->create();

        // Resources.
        $resources = Resource::factory([
            'author_id' => $user->id,
            'team_id' => $team->first()->id,
        ])->count(5)
            ->create()
            ->each(function ($resource) use ($record, $recordNotFound) {
                if ($recordNotFound === false) {
                    $json = json_decode($record, true);
                    $resource->setOrCreateMetadataRecord($json);
                }
            });

        // Create teams.
        $teams = Team::factory(['owner_id' => $user->id])
            ->count(5)
            ->create()
            ->each(function ($team) use ($resources) {
                // Create collections and associate with resources.
                Collection::factory(['team_id' => $team->id])
                    ->count(5)
                    ->create()->each(function ($collection) use ($resources) {
                        $collection->resources()->attach($resources);
                    });
            });

        // Resources.
        $ownerId = 2;

        $sharedTeams = Team::factory(['owner_id' => $ownerId])
            ->count(5)->create()->each(
                function ($team) use ($user, $resources, $ownerId) {

                    $resources = Resource::factory([
                        'author_id' => $ownerId,
                        'team_id' => $team->id
                    ])->count(5)->create();

                    // Create the invites as well.
                    Invite::factory(['team_id' => $team->id, 'user_id' => $user->id])->create();

                    // Create collections with resources for team.
                    Collection::factory(['team_id' => $team->id])
                        ->count(5)
                        ->create()->each(function ($collection) use ($resources) {
                            $collection->resources()->attach($resources);
                        });
                }
            );

        Schema::enableForeignKeyConstraints();
    }
}
