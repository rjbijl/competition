<?php

namespace CompetitionBundle\Form\Type;

use CompetitionBundle\Entity\Player;
use CompetitionBundle\Entity\Round;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * CompetitionBundle\Form\Type\RoundType
 *
 * @author Robert-Jan Bijl <robert-jan@prezent.nl>
 */
class RoundType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', HiddenType::class, [
                'label' => 'form.round.name',
            ])
            ->add('players', EntityType::class, [
                'label' => 'form.round.players',
                'class' => Player::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options['create'] ? 'form.round.create' : 'form.round.update',
                'attr' => [
                    'class' => 'button',
                ]
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined('create')
            ->setAllowedTypes('create', 'boolean')
            ->setRequired('create')
            ->setDefaults([
                'data_class' => Round::class,
            ])
        ;
    }
}