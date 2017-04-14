<?php

/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 18-2-2017
 * Time: 18:07
 */

namespace AppBundle\Form;

use Doctrine\Common\Util\Debug;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class TaskType extends AbstractType
{
    /**
     *
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'Taak',
                'attr' => array('minlength' => 2),
                'required' => true,
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Omschrijving'
            ))
            ->add('subject', EntityType::class, [
                'class' => 'AppBundle:Subject',
                'label' => 'Vak',
                'required' => true,
            ])
            ->add('date_raw', TextType::class, [
                'mapped' => false
            ])
            ->add('timeStart', TextType::class, array(
                'label' => 'Tijd',
                'data' => $options['time_start']
            ));

//            if($options['time_start']) {
//                $builder->get('timeStart')->setData($options['time_start']);
//            }
//            ->add('submit', SubmitType::class, [
//                'label' => 'Taak toevoegen',
//            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Task',
            'time_start' => null,
            'translation_domain' => 'form',
        ));
    }
}
