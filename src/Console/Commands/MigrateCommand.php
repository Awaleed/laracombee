<?php

namespace Amranidev\Laracombee\Console\Commands;


use Amranidev\Laracombee\Console\LaracombeeCommand;


class MigrateCommand extends LaracombeeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laracombee:migrate
    						{type : Catalog type (user or item)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate to recombee';

    /**
     * laracombee instance.
     *
     * @var \Amranidev\Laracombee\Laracombee
     */
    private $laracombee;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->laracombee = new \Amranidev\Laracombee\Laracombee();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $scope = $this->prepareScope()->all();

        $this->laracombee->batch($scope)
            ->then(function ($response) {
                $this->info('Done!');
            })
            ->otherwise(function ($error) {
                $this->error($error);
            })
            ->wait();
    }

    /**
     * Prepare scope.
     *
     * @return mixed
     */
    public function prepareScope()
    {
        $class = config('laracombee.' . $this->argument('type'));

        $properties = $class::$laracombee;

        return collect($properties)->map(function (string $type, string $property) {
            return $this->{'add' . ucfirst($this->argument('type')) . 'Property'}($property, $type);
        });
    }
}
