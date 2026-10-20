<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraints\Optional;
use Workerman\Worker;

#[AsCommand(
    name: 'websocket:start',
    description: 'Starts a WebSocket server on port 8080.',
)]
class WebsocketStartCommand extends Command {

    protected function configure():void {
        $this->addArgument('action', InputArgument::OPTIONAL, 'Action (start|stop|restart|status)', 'start');
    }

    protected function execute(InputInterface $input, OutputInterface $output):int {
        $io = new SymfonyStyle($input, $output);

        global $argv;
        $action = $input->getArgument('action') ?? 'start';
        $argv = ['console', $action];

        $wsWorker = new Worker("websocket://0.0.0.0:8080");
        $wsWorker->count = 1;

        $wsWorker->onMessage = function ($connection, $data) use ($wsWorker, $io) {
            $io->comment("Message Received! :$data.");
            foreach ($wsWorker->connections as $client) {
                $client->send($data);
            }
        };

        $wsWorker->onClose = function ($connection) use ($io) {
            $io->comment("Client [$connection->id] just Disconnected.");
        };

        Worker::runAll();
        return Command::SUCCESS;
    }
}
