<?php
namespace Sumeet\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Yaml\Yaml;

class GithubImportCommand extends Command
{
    protected function configure()
    {
      $this
            // the name of the command (the part after "bin/console")
            ->setName('github:import')

            // the short description shown while running "php bin/console list"
            ->setDescription('Imports all repositores beloning to symfony organization from github into mysql database.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you import all repositores beloning to symfony organization from github into mysql database.')

            // configure an argument
            ->addArgument('database', InputArgument::REQUIRED, 'Mysql database name.')
            ->addArgument('user', InputArgument::REQUIRED, 'Mysql database user.')
            ->addArgument('password', InputArgument::OPTIONAL, 'Mysql database password.');


    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

      try {

        $database = $input->getArgument('database');
        $user = $input->getArgument('user');
        $password = $input->getArgument('password');

        $array = array(
            'database' => $database,
            'user' => $user,
            'password' => $password
        );

        $yaml = Yaml::dump($array);

        file_put_contents(__DIR__.'/../dbconfig.yaml', $yaml);

        $database = $input->getArgument('database');
        $user = $input->getArgument('user');
        $password = $input->getArgument('password');

        $conn = new \PDO("mysql:host=localhost;dbname=$database", $user, $password);

        // set the PDO error mode to exception
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $output->writeln('<info>Sucessfully connected to database</info>');

        $output->writeln('');
        $output->write(
            '<info>Fetching repositores from github..</info>'
        );

        $client = new \Github\Client();

        $api = $client->api('user')->setPerPage(200);

        $repos = $api->repositories('symfony');
        $output->write('<info>Success</info>');
        $output->writeln('');
        $output->write('<info>Creating database table..</info>');

        $sql ="CREATE table repos (
                id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR( 50 ) NOT NULL,
                full_name VARCHAR( 250 ) NOT NULL,
                description VARCHAR( 500 )
              )";

        $conn->exec($sql);

        $output->write('<info>Success</info>');
        $output->writeln('');
        $output->write('<info>Now dumping data to mysql database..</info>');

        $insert = $conn->prepare("INSERT INTO repos (name, full_name, description) VALUES (:name, :fullname, :description)");
        $insert->bindParam(':name', $name);
        $insert->bindParam(':fullname', $fullname);
        $insert->bindParam(':description', $description);

        foreach($repos as $repo) {
          $name = $repo['name'];
          $fullname = $repo['full_name'];
          $description = $repo['description'];
          $insert->execute();
        }

        $output->write('<info>Done</info>');
        $output->writeln('');
        $output->writeln([
        '<info>===============</info>',
        '<info>Congrats! Github import completed successfully.</info>',
        '<info>To browse the database please navigate to github-api-test/index.php in the browser.</info>'
      ]);

      } catch (PDOException $e) {

        $output->writeln('<error>' . $e->getMessage() . '</error>');

      }

    }
}
