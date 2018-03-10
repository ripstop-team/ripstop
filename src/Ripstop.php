<?php namespace Ripstop;

use Robo\Common\ConfigAwareTrait;
use Robo\Config\Config;
use Robo\Robo;
use Robo\Runner as RoboRunner;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Ripstop
{
  const APPLICATION_NAME = 'Ripstop';

  const REPOSITORY = 'ripstop/ripstop';

  use ConfigAwareTrait;

  private $runner;

  public function __construct(
    Config $config,
    InputInterface $input = NULL,
    OutputInterface $output = NULL
  ) {

    // Create applicaton.
    $this->setConfig($config);
    $application = new Application(
        self::APPLICATION_NAME,
        $config->get('version')
    );

    // Create and configure container.
    $container = Robo::createDefaultContainer(
        $input,
        $output,
        $application,
        $config
    );

    $discovery = new \Consolidation\AnnotatedCommand\CommandFileDiscovery();
    $discovery->setSearchPattern('*.php');
    $commandClasses = $discovery->discover('src/Command', '\Ripstop\Command');

    // Instantiate Robo Runner.
    $this->runner = new RoboRunner($commandClasses);
    $this->runner->setContainer($container);
    $this->runner->setSelfUpdateRepository(self::REPOSITORY);
  }

  public function run(InputInterface $input, OutputInterface $output) : int
  {
    return $this->runner->run($input, $output);
  }

}
