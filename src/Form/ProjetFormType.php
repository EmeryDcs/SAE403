<?php

namespace App\Form;

use App\Entity\Projet;
use App\Entity\Competence;
use App\Entity\AC;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProjetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('description', TextareaType::class)
            ->add('annee', IntegerType::class)
            ->add('competences', EntityType::class, [
                'class' => Competence::class, 
                'choice_label'=>'nom',
                'choice_value'=>'id',
                'multiple' => true,
                'expanded' => true,
                'mapped'=>true,
            ])
            ->add('ac', EntityType::class, [
                'class' => AC::class, 
                'choice_label'=>'numero',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('acquis', ChoiceType::class, [
                'choices' => [
                    'Acquis'=>1,
                    "En cours d'acquisition"=>2,
                    'Non acquis'=>3,
                ],
            ])
            ->add('domaine', ChoiceType::class, [
                'choices' => [
                    'Développement Web' => 'Développement Web',
                    'Graphisme' => 'Graphisme',
                    'Communication' => 'Communication',
                    'Audiovisuel' => 'Audiovisuel'
                ],
                'multiple' => true,
                'expanded' => true,
                'mapped' => false,
            ])
            ->add('photo', FileType::class, array('mapped' => false))
            ->add('video', TextType::class, ['required' => false])
            ->add('send', SubmitType::class, array('label'=>'Créer un nouveau projet'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
