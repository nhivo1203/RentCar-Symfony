<?php

namespace App\Command;

use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(name: 'app:worker')]
class WorkerCommand extends Command
{
    protected static $defaultName = 'app:worker';
    private SqsClient $sqsClient;
    private ParameterBagInterface $params;

    public function __construct(SqsClient $sqsClient, ParameterBagInterface $params)
    {
        $this->sqsClient = $sqsClient;
        $this->params = $params;
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sqsURL = $this->params->get('amazon.sqs.url');
        $client = $this->sqsClient;
        try {
            $result = $client->receiveMessage(array(
                'AttributeNames' => ['SentTimestamp'],
                'MaxNumberOfMessages' => 1,
                'MessageAttributeNames' => ['All'],
                'QueueUrl' => $sqsURL, // REQUIRED
                'WaitTimeSeconds' => 0,
            ));
            if (!empty($result->get('Messages'))) {
            } else {
                echo "No messages in queue. \n";
            }
        } catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
        }
            $this->setHelp('This command allows you send message...');
            return Command::SUCCESS;
    }
}
