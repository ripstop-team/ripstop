<?php namespace Ripstop;

use League\Container\ReflectionContainer;
use RIPS\Connector\API;
use Robo\Common\ConfigAwareTrait;
use Robo\Config\Config;
use Robo\Robo;
use Robo\Runner as RoboRunner;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use League\Container\Argument\RawArgument;

class Ripstop
{
    const APPLICATION_NAME = 'Ripstop';

    const REPOSITORY = 'ripstop/ripstop';

    const BASE_URI = 'https://api-2.ripstech.com';

    use ConfigAwareTrait;

    private $runner;

    public function __construct(
        Config $config,
        InputInterface $input = null,
        OutputInterface $output = null
    ) {

        // Create applicaton.
        $application = new Application(
            self::APPLICATION_NAME,
            '0.0.1'
        );

        // Create and configure container.
        $container = Robo::createDefaultContainer(
            $input,
            $output,
            $application,
            $config
        );

        $container->delegate(new ReflectionContainer());

        /** @var Credentials $credentials */
        $credentials = (new Service\Credentials())();
        $apiConfig   = ['base_uri' => self::BASE_URI];
        $container->share(API::class, API::class)
                  ->withArgument(new RawArgument($credentials->username()))
                  ->withArgument(new RawArgument($credentials->password()))
                  ->withArgument(new RawArgument($apiConfig));
        $container->share('scans', Service\Scans::class)
                  ->withArgument(API::class);
        $container->share('reports', Service\Reports::class)
                  ->withArgument(API::class);

        $discovery = new \Consolidation\AnnotatedCommand\CommandFileDiscovery();
        $discovery->setSearchPattern('*.php');
        $commandClasses = $discovery->discover('src/Command', '\Ripstop\Command');

        // Instantiate Robo Runner.
        $this->runner = new RoboRunner($commandClasses);
        $this->runner->setContainer($container);
        $this->runner->setSelfUpdateRepository(self::REPOSITORY);
    }

    public function run(): int
    {
        return $this->runner->execute($_SERVER['argv']);
    }

}
