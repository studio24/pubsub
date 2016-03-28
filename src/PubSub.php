<?php
namespace studio24\PubSub;

/**
 * Class to manage task running, uses the PubSub design pattern
 *
 * @package S24\Atomic\Service
 */
class PubSub
{
    /**
     * Array of tasks to run
     *
     * @var array
     */
    protected $tasks = [];

    /**
     * Disable constructor
     */
    protected function __construct() { }

    /**
     * Disable clone
     */
    protected function __clone() { }

    /**
     * Get singleton instance of class
     *
     * @return PubSub
     */
    public static function getInstance()
    {
        static $instance;

        if ($instance instanceof PubSub) {
            return $instance;
        }

        $instance = new PubSub();
        return $instance;
    }

    /**
     * Add task to run after this task
     *
     * @param string $event Event to run tasks on
     * @param callable $callback Function to run
     * @param int $weight Defines order function runs, lower = earlier, higher = later. Tasks with the same weight run in the order they are added
     * @throws PubSubException
     */
    public static function subscribe($event, callable $callback, $weight = 10)
    {
        $pubSub = PubSub::getInstance();

        if (empty($event) || !is_string($event)) {
            throw new PubSubException('Event name must be a string');
        }

        if (!isset($pubSub->tasks[$event][$weight])) {
            $pubSub->tasks[$event][$weight] = [];
        }
        $pubSub->tasks[$event][$weight][] = $callback;
    }

    /**
     * Run tasks
     *
     * If no tasks are subscribed to, this does nothing
     *
     * @param string $event Event to run
     * @param mixed $args, One or many arguments to pass to the callback function
     * @throws PubSubException
     */
    public static function publish($event, ...$args)
    {
        $pubSub = PubSub::getInstance();

        if (empty($event) || !is_string($event)) {
            throw new PubSubException('Event name must be a string');
        }

        if (!isset($pubSub->tasks[$event])) {
            return;
        }

        ksort($pubSub->tasks[$event]);
        foreach ($pubSub->tasks[$event] as $values) {
            foreach ($values as $callback) {
                $callback(...$args);
            }
        }
    }

}