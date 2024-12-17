<?php

namespace App\Form;

use App\Entity\Offers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Offers;

class OffersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('contrat', TextType::class, [
            'label' => 'Contract Type',
            'attr' => [
                'list' => 'contractTypes', // Associe ce champ au <datalist> avec id="contractTypes"
                'placeholder' => 'Choose or type a contract type',
            ],
            'required' => true,
        ])
            ->add('experienceRequise', TextType::class, [
                'label' => 'Required Experience',
                'attr' => [
                    'placeholder' => 'Enter the required experience',
                    'maxlength' => 255,
                    'class' => 'form-control',
                ],
                'required' => true,
            ])
            ->add('formation', TextType::class, [
                'label' => 'Education',
                'attr' => [
                    'placeholder' => 'Enter the required education',
                    'maxlength' => 255,
                    'class' => 'form-control',
                ],
                'required' => true,
            ])
            ->add('langue', TextType::class, [
                'label' => 'Language',
                'attr' => [
                    'list' => 'languages', // Associe ce champ à l'élément <datalist> avec id="languages"
                    'placeholder' => 'Choose or type a language',
                ],
                'required' => true,
            ])
            ->add('nbrRecruter', NumberType::class, [
                'label' => 'Number to Recruit',
                'attr' => [
                    'placeholder' => 'Enter the number to recruit',
                    'min' => 1,
                    'class' => 'form-control',
                ],
                'required' => true,
            ])
            ->add('anneesExperience', NumberType::class, [
                'label' => 'Years of Experience',
                'attr' => [
                    'placeholder' => 'Enter years of experience',
                    'min' => 0,
                    'class' => 'form-control',
                ],
                'required' => true,
            ])
            ->add('connaissances', TextType::class, [
                'label' => 'Knowledge',
                'attr' => [
                    'placeholder' => 'Enter required knowledge',
                    'maxlength' => 255,
                    'class' => 'form-control',
                ],
                'required' => true,
            ])
            ->add('salaire', NumberType::class, [
                'label' => 'Salary',
                'attr' => [
                    'placeholder' => 'Enter salary amount',
                    'min' => 0,
                    'class' => 'form-control',
                ],
                'required' => true,
            ])
            ->add('specialite', TextType::class, [
                'label' => 'Speciality',
                'attr' => [
                    'placeholder' => 'Enter speciality',
                    'maxlength' => 255,
                    'class' => 'form-control',
                ],
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Job Description',
                'attr' => [
                    'placeholder' => 'Describe the job in detail',
                    'maxlength' => 1000,
                    'rows' => 5,
                    'class' => 'form-control',
                ],
                'required' => true,
            ])
            ->add('villeTravail', TextType::class, [
                'label' => 'Work City',
                'attr' => [
                    'placeholder' => 'Enter the work city',
                    'maxlength' => 100,
                    'class' => 'form-control',
                ],
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offers::class,
        ]);
    }
}

