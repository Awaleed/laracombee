<?php

namespace Amranidev\Laracombee\Console\Commands;


use Amranidev\Laracombee\Console\LaracombeeCommand;


class DropColumnsCommand extends LaracombeeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laracombee:drop
                            {columns* : Columns}
                            {--from= : table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop columns form recombee db';

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
     * @return mixed
     */
    public function handle()
    {
        if (!$this->option('from')) {
            $this->error('--from option is required!');
            exit;
        }

        $this->laracombee->batch($this->loadColumns($this->argument('columns'))->all())
            ->then(function ($response) {
                $this->info('Done!');
            })
            ->otherwise(function ($error) {
                $this->error($error);
            })
            ->wait();
    }

    /**
     * Load columns.
     *
     * @param array $columns
     *
     * @return \Illuminate\Support\Collection
     */
    public function loadColumns(array $columns)
    {
        return collect($columns)->map(function (string $column) {
            return $this->{'delete' . ucfirst($this->option('from')) . 'Property'}($column);
        });
    }
}
