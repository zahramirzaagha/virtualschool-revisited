<?php

namespace App\Form;

use App\Entity\School;
use App\Entity\User;
use App\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationFormType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => $this->translator->trans('name'),
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('please_enter_your_name'),
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => $this->translator->trans('email'),
                'attr' => ['autocomplete' => 'email'],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('please_enter_your_email'),
                    ]),
                ],
            ])
            ->add('school', ChoiceType::class, [
                'label' => $this->translator->trans('school'),
                'choices'  => [
                    'Preschool' => School::Preschool->value,
                    'Elementary' => School::Elementary->value,
                    'Secondary' => School::Secondary->value,
                ],
                'multiple' => false,
                'expanded' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('please_enter_your_school'),
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => $this->translator->trans('agree_to_terms'),
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => $this->translator->trans('should_agree_to_terms'),
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('enter_password'),
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => $this->translator->trans('password_min_limit'),
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add("roles", ChoiceType::class, [
                'choices'  => [
                    'Teacher' => Role::Teacher->value,
                    'Parent' => Role::Parent->value,
                    'Student' => Role::Student->value,
                ],
                'multiple' => true,
                'expanded' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('please_enter_your_role'),
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
