<?php

namespace Forms\Form\TypeExtension;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
/**
 * need a TAG form.type_extension 
 * 
 * Symfony 4.2:
 *  automatically detect FormTypeExtensionInterface
 */
class TextareaSizeExtension implements FormTypeExtensionInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    // override the "view" variables
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['rows'] = $options['my-rows'];
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'my-rows' => 4
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [TextareaType::class];
        //return [FormType::class]; if you want to modify EVERY fields
    }
}
