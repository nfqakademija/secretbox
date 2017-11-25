<?php

namespace AppBundle\Command;

use AppBundle\Entity\Impression;
use AppBundle\Entity\Order;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppFakerCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * AppFakerCommand constructor.
     *
     * @param null $name
     * @param EntityManager $em
     */
    public function __construct($name = null, EntityManager $em)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->faker = \Faker\Factory::create();
    }

    protected function configure()
    {
        $this
            ->setName('app:faker')
            ->setDescription('generate random data for secretbox database')
            ->addArgument('rowsCount', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('users', null, InputOption::VALUE_NONE, 'Option description')
            ->addOption('orders', null, InputOption::VALUE_NONE, 'Option description')
            ->addOption('products', null, InputOption::VALUE_NONE, 'Option description')
            ->addOption('impressions', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rowsCount = $input->getArgument('rowsCount');


        for ($i = 0; $i < $rowsCount; $i++) {
            if ($input->getOption('users')) {
                $this->generateUser();
            }
            if ($input->getOption('orders')) {
                $this->generateOrder();
            }
            if ($input->getOption('products')) {
                $this->generateProduct();
            }
            if ($input->getOption('impressions')) {
                $this->generateImpression();
            }
        }
        $this->em->flush();
        $output->writeln('faker reports: DONE');
        $output->writeln('Generated new rows: ' . $rowsCount);
    }

    private function generateUser()
    {
        $registeredDate = $this->faker->dateTimeBetween('-2 years', 'now');

        $user = new User();
        $user->setFacebookId($this->faker->randomNumber(8, true));
        $user->setEmail($this->faker->freeEmail());
        $user->setFirstName($this->faker->firstName);
        $user->setLastName($this->faker->lastName);
        $user->setRegisteredDate($registeredDate);
        $user->setLoggedDate($this->faker->dateTimeBetween($registeredDate, 'now'));
        $user->setLoginCount($this->faker->numberBetween(1, 20));
        $this->em->persist($user);
    }

    private function generateOrder()
    {
        $status = array(
            1 => "new",
            2 => "revealed"
        );
        $order = new Order();
        $order->setUserId($this->faker->numberBetween(1, 100));
        $order->setProductId($this->faker->numberBetween(1, 100));
        $order->setOrderDate($this->faker->dateTimeBetween('-2 years', 'now'));
        $order->setSellingPrice(19.99);
        $order->setStatus($status[$this->faker->numberBetween(1, 2)]);
        $order->setDeliveryAddress($this->faker->streetAddress);
        $this->em->persist($order);
    }

    private function generateProduct()
    {
        $product = new Product();
        $product->setTitle($this->faker->sentence(2));
        $product->setFacebookName($this->faker->sentence(2));
        $product->setDescription($this->faker->sentence(8));
        $product->setSupplierPrice($this->faker->randomFloat(2, 5, 14.99));
        $product->setSupplier($this->faker->company);
//        $product->setAgeRange();
        $product->setGender($this->faker->randomElement(['male','female','unisex']));
        $validFrom = $this->faker->dateTimeBetween('-2 years', '-7 days');

        $product->setValidFrom($validFrom);
        $product->setValidTo($this->faker->dateTimeBetween($validFrom, '+ 1 year'));
        $this->em->persist($product);
    }

    private function generateImpression()
    {
        $impression = new Impression();
        $impression->setUserId($this->faker->numberBetween(1, 100));
        $impression->setImpression($this->faker->realText(50, 2));
        $impression->setCreated($this->faker->dateTimeBetween('-2 years', '-5 days'));
        $this->em->persist($impression);
    }
}
