<?php namespace Ripstop;

use League\Container\Argument\RawArgument;
use League\Container\ReflectionContainer;
use Mustache_Engine;
use RIPS\Connector\API;
use Robo\Common\ConfigAwareTrait;
use Robo\Config\Config;
use Robo\Robo;
use Robo\Runner as RoboRunner;
use Swift_Mailer;
use Swift_SendmailTransport;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Ripstop
{
    const APPLICATION_NAME = 'Ripstop';

    const REPOSITORY = 'ripstop/ripstop';

    const KEY_BASE_URI = 'base_uri';
    const KEY_TEMPLATE = 'email_template';
    const BASE_URI = 'https://api-2.ripstech.com';
    const TEMPLATE = 'templates/email_message.mustache';

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
        $base_uri    = $config->has(self::KEY_BASE_URI)
            ? $config->get(self::KEY_BASE_URI)
            : self::BASE_URI;

        $apiConfig = ['base_uri' => $base_uri];
        $container->share(API::class, API::class)
                  ->withArgument(new RawArgument($credentials->username()))
                  ->withArgument(new RawArgument($credentials->password()))
                  ->withArgument(new RawArgument($apiConfig));

        $container->share('applications', Service\Applications::class)
            ->withArgument(API::class);
        $container->share('scans', Service\Scans::class)
            ->withArgument(API::class);
        $container->share('reports', Service\Reports::class)
                  ->withArgument(API::class);

        $template = $config->has(self::KEY_TEMPLATE)
            ? $config->get(self::KEY_TEMPLATE)
            : self::TEMPLATE;
        $container->share('emailer', Service\Emailer::class)
                  ->withArgument(Swift_Mailer::class)
                  ->withArgument(Mustache_Engine::class)
                  ->withArgument(new RawArgument($template));

        $container->share(Swift_Mailer::class, Swift_Mailer::class)
            ->withArgument(new Swift_SendmailTransport('/usr/sbin/sendmail -bs'));
        $container->share('app_data', Service\ApplicationData::class)
            ->withArgument(API::class);
        $container->share('applicationIdForName', Service\ApplicationIdForName::class)
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
