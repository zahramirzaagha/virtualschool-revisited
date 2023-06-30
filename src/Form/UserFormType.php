<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('parent', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
                'choice_value' => function (?User $user): string {
                    return $user ? $user->getId() : '';
                },
                'query_builder' => function (UserRepository $userRepository): QueryBuilder {
                    $queryBuilder = $userRepository->createQueryBuilder('u');
                    return $queryBuilder
                        ->where($queryBuilder->expr()->like('u.roles', ':roles'))
                        ->setParameter('roles', '%'.Role::Teacher->value.'%')
                        ->orderBy('u.name', 'ASC');
                }
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
