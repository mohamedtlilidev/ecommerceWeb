<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 20/02/2017
 * Time: 11:07
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Category;

class CategoryType extends  AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameFr')
            ->add('nameEn')
            ->add('bgPicture',FileType::class,
            array(
                'label' => 'Inserer une image pour votre category',
                'data_class' =>null,
                'required'=>false
            ))
            ->add('isPublished');

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Category'
        ));
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_category';
    }
}