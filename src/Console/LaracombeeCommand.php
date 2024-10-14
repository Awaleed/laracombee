<?php

namespace Amranidev\Laracombee\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;

class LaracombeeCommand extends Command
{
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
     * Add User property.
     *
     * @param string $property.
     * @param string $type.
     *
     * @return \Recombee\RecommApi\Requests\AddUserProperty
     */
    public function addUserProperty(string $property, string $type)
    {
        return $this->laracombee->addUserProperty($property, $type);
    }

    /**
     * Add Item property.
     *
     * @param string $property.
     * @param string $type.
     *
     * @return \Recombee\RecommApi\Requests\AddItemProperty
     */
    public function addItemProperty(string $property, string $type)
    {
        return $this->laracombee->addItemProperty($property, $type);
    }

    /**
     * Delete User property.
     *
     * @param string $property.
     *
     * @return \Recombee\RecommApi\Requests\DeleteUserProperty
     */
    public function deleteUserProperty(string $property)
    {
        return $this->laracombee->deleteUserProperty($property);
    }

    /**
     * Delete Item property.
     *
     * @param string $property.
     *
     * @return \Recombee\RecommApi\Requests\DeleteItemProperty
     */
    public function deleteItemProperty(string $property)
    {
        return $this->laracombee->deleteItemProperty($property);
    }

    /**
     * Add user to recombee.
     *
     * @param \Illuminate\Foundation\Auth\User $user.
     *
     * @return \Recombee\RecommApi\Requests\Request
     */
    public function addUser(User $user)
    {
        return $this->laracombee->addUser($user);
    }

    /**
     * Add item to recombee.
     *
     * @param \Illuminate\Database\Eloquent\Model $item.
     *
     * @return \Recombee\RecommApi\Requests\Request
     */
    public function addItem(Model $item)
    {
        return $this->laracombee->addItem($item);
    }

    /**
     * Add users as bulk.
     *
     * @param array $batch.
     *
     * @return \Recombee\RecommApi\Requests\Request
     */
    public function addUsers(array $batch)
    {
        return $this->laracombee->addUsers($batch);
    }

    /**
     * Add items as bulk.
     *
     * @param array $batch.
     *
     * @return \Recombee\RecommApi\Requests\Request
     */
    public function addItems(array $batch)
    {
        return $this->laracombee->addItems($batch);
    }
}
