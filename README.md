# PubSub

Simple implementation of the PubSub design pattern in PHP.

## Installation

```sh
composer require studio24/pubsub
```

## Usage

Import at the top of your PHP script via:

```sh
use Studio24\PubSub\PubSub;
```

### Add a task to run at a certain event (subscribe)

This will run the passed anonymous function when the event 'myevent' is run.

```sh
PubSub::subscribe('myevent', function($name){
    // My code goes here
    echo $name;
});
```

#### PubSub::subscribe($event, $callback, $weight)

Params:

* $event (string) Event name 
* $callback (callback) Callback function to run 
* $weight (int) Optional, weight to define the order subscribed tasks run, defaults to 10. The lower the number the earlier this callback runs

### Run tasks at a certain event (publish)

This runs all tasks which are subscribed to the 'myevent' event, passing the argument $name. 

```sh
PubSub::publish('myevent', $name);
```

#### PubSub::publish($event, ...$arguments)

Params:

* $event (string) Event name 
* $arguments (mixed) Optional, one or many arguments to pass to the callback function 

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- [Simon R Jones](https://github.com/simonrjones)