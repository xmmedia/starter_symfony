<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @codeCoverageIgnore
 */
class LoadDefaultFixtures extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $loader = new NativeLoader();
        $userManager = $this->container->get('fos_user.user_manager');
        $passwordEncoder = $this->container->get('security.password_encoder');

        $objects = $loader->loadFile(__DIR__.'/fixtures.yml');

        foreach ($objects->getObjects() as $ref => $entity) {
            if ($entity instanceof User) {
                $entity->setPassword(
                    $passwordEncoder->encodePassword($entity, $entity->getPassword())
                );
                $userManager->updateUser($entity, false /* no flush */);
            } else {
                $manager->persist($entity);
            }

            $this->addReference($ref, $entity);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}