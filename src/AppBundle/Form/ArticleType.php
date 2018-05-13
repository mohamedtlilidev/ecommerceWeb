<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->
        add('code')->
        add('name')->
        add('htmlName',TextareaType::class)->
        add('price')->
        add('solde')->
        add('isOffer')->
        add('isSpecial')->
        add('isNew')->
        add('urlPicture',
            FileType::class,
            array(
                'label' => 'Inserer une image pour votre produit',
                'data_class' =>null,
                'required'=>false
            ))->
        add('category')->
        add('brand')->
        add('rank')->
        add('isPublished');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Article'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_article';
    }


}
