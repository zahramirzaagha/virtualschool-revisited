<?php

namespace App\Form;

use App\Entity\CourseRegistration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CourseRegistrationType extends AbstractType
{
    private UrlGeneratorInterface $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entity = $options['data'];

        if ($entity instanceof CourseRegistration) {
            $builder
                ->setAction($this->router->generate('app_course_registration_new', [
                    'courseId' => $entity->getCourse()->getId()
                ]))
                ->setMethod('POST')
                ->add('submit', SubmitType::class, [
                    'label' => 'Register',
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CourseRegistration::class,
        ]);
    }
}
