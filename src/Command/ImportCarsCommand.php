<?php

namespace App\Command;

use App\Entity\Car;
use App\Repository\CarRepository;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\UnavailableStream;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-cars',
    description: 'Import cars trough a CSV file, structure: car_name, price, createdAt',
)]
class ImportCarsCommand extends Command
{
    public function __construct(protected readonly CarRepository $carRepository, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('path', InputArgument::REQUIRED, 'Path to csv file')
            ->addOption('ignore-head', 'i', InputOption::VALUE_OPTIONAL, 'Add if csv has a heading row', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $path = $input->getArgument('path');

        if (!file_exists($path)) {
            $io->error("file at path: $path does not exists");
            return Command::FAILURE;
        }

        $skipHeading = $input->getOption('ignore-head') !== false;

        try {
            $skipped = [];
            $reader = Reader::createFromPath($path);
            foreach ($reader->getRecords() as $index => $record) {
                if ($index === 0 && $skipHeading) {
                    continue;
                }

                if (!isset($record[0], $record[1], $record[2])) {
                    $io->error("Not all information available: ".implode(', ', $record));

                    $skipped[] = $record;
                    continue;
                }

                $car = new Car();
                $car->setName($record[0]);
                $car->setPrice((int) $record[1]);
                $car->setCreatedAt(new \DateTimeImmutable($record[2]));
                $this->carRepository->save($car);
            }

            $this->carRepository->flush();

        } catch (UnavailableStream|Exception|\Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }

        if (count($skipped)) {
            $io->success('Import completed!');
            $io->warning('Not all cars are imported:');
            foreach ($skipped as $skippedItem) {
                $io->text(implode(', ', $skippedItem));
            }
        } else {
            $io->success('All cars are imported!');
        }

        return Command::SUCCESS;
    }
}
