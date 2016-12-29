<?php

namespace Xaamin\Validation;

use Illuminate\Validation\Factory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Container\Container as IlluminateContainer;

class Validator
{
    /**
     * @var \Illuminate\Validation\Factory
     */
    protected $factory;

    /**
     * The current globally used instance.
     *
     * @var object
     */
    protected static $instance;

    /**
     * Register the validator.
     *
     * @param \Illuminate\Contracts\Container\Container $container
     */
    public function __construct(Container $container = null)
    {
        $container = $container ? : new IlluminateContainer;

        $this->factory = new Factory($this->getTranslator($container), $container);

        if ($container && $container->bound('db')) {
            $this->setConnection($container['db']);
        }
    }

    /**
     * Set the database instance used by the presence verifier.
     *
     * @param  \Illuminate\Database\ConnectionResolverInterface $db
     * @return void
     */
    public function setConnection(ConnectionResolverInterface $db)
    {
        $this->factory->setPresenceVerifier(new DatabasePresenceVerifier($db));
    }

    /**
     * Set the language lines used by the translator.
     *
     * @param  array $lines
     * @return void
     */
    public function setLines(array $lines)
    {
        $translator = $this->factory->getTranslator();

        if ($translator instanceof Translator) {
            $translator->setLines($lines);
        }
    }

    /**
     * Get the validation factory instance.
     *
     * @return \Illuminate\Contracts\Validation\Factory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Get a translator instance.
     *
     * @param  \Illuminate\Contracts\Container\Container $container
     * @return \Symfony\Component\Translation\TranslatorInterface
     */
    protected function getTranslator(Container $container = null)
    {
        if ($container && $container->bound('translator')) {
            return $container['translator'];
        }

        return new Translator(require __DIR__.'/lang/en.php');
    }

    /**
     * Make this instance available globally.
     *
     * @return void
     */
    public static function instance()
    {
        if (!static::$instance) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Call validator methods dynamically.
     *
     * @param  string $method
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->factory, $method], $arguments);
    }

    /**
     * Call static validator methods dynamically.
     *
     * @param  string $method
     * @param  array  $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        $factory = static::instance()->getFactory();

        return call_user_func_array([$factory, $method], $arguments);
    }
}