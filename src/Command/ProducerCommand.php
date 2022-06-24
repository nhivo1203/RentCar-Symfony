<?php

namespace App\Command;

use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(name: 'app:producer')]
class ProducerCommand extends Command
{
    protected static $defaultName = 'app:producer';
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
        $this->setHelp('This command allows you send message...');
        $params = [
            'DelaySeconds' => 10,
            'MessageAttributes' => [
                "Title" => [
                    'DataType' => "String",
                    'StringValue' => "The Hitchhiker's Guide to the Galaxy"
                ],
                "Author" => [
                    'DataType' => "String",
                    'StringValue' => "Douglas Adams."
                ],
                "WeeksOn" => [
                    'DataType' => "Number",
                    'StringValue' => "6"
                ]
            ],
            'MessageBody' => "Information about current NY Times fiction bestseller for week of 12/11/2016.",
            'QueueUrl' => $sqsURL
        ];

        try {
            $result = $client->sendMessage($params);
        } catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
        }
        return Command::SUCCESS;
    }
}
