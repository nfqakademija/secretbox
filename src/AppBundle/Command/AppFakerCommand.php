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
    private $fk;

    public function __construct($name = null, EntityManager $em)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->fk = \Faker\Factory::create();
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
        $registeredDate = $this->fk->dateTimeBetween('-2 years', 'now');

        $user = new User();
        $user->setFacebookId($this->fk->randomNumber(8, true));
        $user->setEmail($this->fk->freeEmail());
        $user->setFirstName($this->fk->firstName);
        $user->setLastName($this->fk->lastName);
        $user->setRegisteredDate($registeredDate);
        $user->setLoggedDate($this->fk->dateTimeBetween($registeredDate, 'now'));
        $user->setLoginCount($this->fk->numberBetween(1, 20));
        $this->em->persist($user);
    }

    private function generateOrder()
    {
        $status = array(
            1 => "new",
            2 => "revealed"
        );
        $order = new Order();
        $order->setUserId($this->fk->numberBetween(1, 100));
        $order->setProductId($this->fk->numberBetween(1, 100));
        $order->setOrderDate($this->fk->dateTimeBetween('-2 years', 'now'));
        $order->setSellingPrice(19.99);
        $order->setStatus($status[$this->fk->numberBetween(1, 2)]);
        $order->setDeliveryAddress($this->fk->streetAddress);
        $this->em->persist($order);
    }

    private function generateProduct()
    {
        $product = new Product();
        $product->setTitle($this->fk->sentence(2));
        $product->setDescription($this->fk->sentence(8));
        $product->setSupplierPrice($this->fk->randomFloat(2, 5, 14.99));
        $product->setSupplier($this->fk->company);

        $validFrom = $this->fk->dateTimeBetween('-2 years', '-7 days');

        $product->setValidFrom($validFrom);
        $product->setValidTo($this->fk->dateTimeBetween($validFrom, '+ 1 year'));
        $this->em->persist($product);
    }

    private function generateImpression()
    {
        $impression = new Impression();
        $impression->setUserId($this->fk->numberBetween(1, 100));
        $impression->setImpression($this->fk->realText(50, 2));
        $impression->setCreated($this->fk->dateTimeBetween('-2 years', '-5 days'));
        $this->em->persist($impression);
    }
}
