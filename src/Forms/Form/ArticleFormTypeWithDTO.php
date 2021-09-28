<?php

namespace Forms\Form;

use Forms\Form\UserSelectFormType;
use Forms\Model\ArticleFormModelDTO;
use SecurityAuth\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/*****************
 * Duplicated from ArticleFormType
 * 
 * We can remove all the "mapped" params
 ************/
class ArticleFormTypeWithDTO extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $article = $options['data'] ?? null; // if creation or editing page
        $isEdit = $article && $article->getId();

        $builder
            ->add('title', null, [
                'help' => 'Choose something catchy!',
            ])
            ->add('content') //can override the TextareaSizeExtension 'my-rows' here
            ->add('unexistedParam', TextType::class, [ // eg: plain password user
                'constraints' => [ // constraint used in Entity
                    new NotBlank([
                        'message' => 'Choose a param!'
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Come on, you can think of a password longer than that!'
                    ])
                ]
            ])
            ->add('author', UserSelectFormType::class, [
                'its_my_finder_callback' => function(UserRepository $userRepository, string $id) {
                    return $userRepository->findOneBy(['id' => $id]);
                },
                // disable on html AND validating
                'disabled' => $isEdit // don't allow on edit form
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'constraints' => [
                    new IsTrue([
                        'message' => 'You must agree to our terms'
                    ])
                ]
            ])
            ->add('publishedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'with_seconds' => false,
                'required' => false, //check for doctrine "nullable" if no class provided
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ArticleFormModelDTO::class
        ]);
    }
}
