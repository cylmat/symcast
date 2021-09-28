<?php

namespace Forms\Form;

use Forms\Form\UserSelectFormType;
use SecurityAuth\Entity\Article;
use SecurityAuth\Entity\User;
use SecurityAuth\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ArticleFormType extends AbstractType
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*$builder //auto data_class type hinting
        ->add('title')
        ->add('content')
        ->add('author') //automatically query entities from the database
        ;*/

        $article = $options['data'] ?? null; // if creation or editing page
        $isEdit = $article && $article->getId();

        // custom
        $builder
            ->add('title', null, [ //TextType::class
                'help' => 'Choose something catchy!',
            ])
            ->add('content') //can override the TextareaSizeExtension 'my-rows' here
            ->add('unexistedParam', TextType::class, [ // eg: plain password user
                'mapped' => false,
                'constraints' => [ // constraint used in Entity: used in form when attribute not mapped
                    new NotBlank([
                        'message' => 'Choose a param!'
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Come on, you can think of a password longer than that!'
                    ])
                ]
            ])
            /*
                ->add('author', EntityType::class, [ //EntityType permit "Data Transformation"
                'class' => User::class,
                'placeholder' => 'Header: Choose an author',
                // custom query
                    'choices' => $this->userRepository->findBy([], ['email' => 'ASC']),
                    //override __toString() class
                    'choice_label' => function(User $user) { // 'email'
                        return sprintf('(%d) %s', $user->getId(), $user->getEmail());
                    },
                    'invalid_message' => "Symfony sanity is too smart for your hacking!" // sanity validation
                    ])
                */
               // Replaced by UserSelectFormType custom type
               // and DataTransformer
            ->add('author', UserSelectFormType::class, [
                'its_my_finder_callback' => function(UserRepository $userRepository, string $id) {
                    return $userRepository->findOneBy(['id' => $id]);
                },
                // disable on html AND validating
                'disabled' => $isEdit // don't allow on edit form
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You must agree to our terms'
                    ])
                ]
            ]);

        if ($options['i_want_to_include_publishing'] ?? null) {
            $builder
                ->add('publishedAt', DateTimeType::class, [
                    'widget' => 'single_text',
                    'with_seconds' => false,
                    'required' => false, //check for doctrine "nullable" if no class provided
                ]);
        }


        /********************************************************************************** *
         * tuto: dependents fields
         ***********************************************************************************/
        $builder->add('location', ChoiceType::class, [
            'required' => false,
            "placeholder" => "Location incoming...",
            'choices' => [
                'The Solar System' => 'solar_system',
                'Near a star' => 'star',
                'Interstellar Space' => 'interstellar_space'
            ],
        ]);
        /**
         * ALLOW TO CHANGE WITH SUBMITTED VALUES
         * ADD THE CORRECT FIELD DEPENDING ON LOCATION FIELD SUBMITTED DATA
         * ("invalid value")
         */
        $builder->get('location')->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {
            $locationForm = $event->getForm();

            $this->setupSpecificLocationNameField(
                $locationForm->getParent(), //can remove or add field
                 //location
                $locationForm->getData() //or event->getData()
            );
        });

        /**
         * ADD A BLOCK WHEN EDITING ARTICLE
         * AND LOCATION IS ALREADY SET
         */

        // REPLACED BY FormEvents::PRE_SET_DATA, !!!!!!!!!!!!!!!!! 

        // $location = $article ? $article->location : null;
        // if ($location) {
        //     $builder->add('specificLocationName', ChoiceType::class, [
        //         'placeholder' => 'Where exactly?',
        //         'choices' => $this->getLocationNameChoices($location),
        //         'required' => false,
        //     ]);
        // }


        /**
         * AVOID DUPLICATION OF 
         * if ($location) {
         *      $builder->add('specificLocationName'
         * AND
         * setupSpecificLocationNameField()
         *      $form->add('specificLocationName'
         * 
         * but can't be called from -$builder-
         * and need to pass event->form
         */
        $builder->addEventListener(
            //
            // data set with class's data
            // on entity edition
            // 
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var Article|null $data */
                if (!$data = $event->getData()) { // no field for /new
                    return;
                }

                // add field for /edit
                // replace: $builder->add('specificLocationName')
                $this->setupSpecificLocationNameField(
                    $event->getForm(),
                    $data->location
                );
            }
        );

        ///////////////////////////////////////////////// -tuto
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined([
            'i_want_to_include_publishing'
        ]);

        // bind to a class orm type (@orm/text => textarea)
        // $form->getData() become an object
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }

    private function setupSpecificLocationNameField(FormInterface $form, ?string $location)
    {
        // if no location selected
        if (null === $location) { // if "Choose a location" selected 
            $form->remove('specificLocationName');
            return;
        }

        $form->add('specificLocationName', ChoiceType::class, [
            'placeholder' => 'Where exactly -inside setup- ?',
            'choices' => $this->getLocationNameChoices($location),
            'required' => false,
        ]);
    }

    private function getLocationNameChoices(string $location): ?array
    {
        $planets = [
            'Mercury',
            'Venus',
            'Earth',
            'Mars',
            'Jupiter',
            'Saturn',
            'Uranus',
            'Neptune',
        ];
        $stars = [
            'Polaris',
            'Sirius',
            'Alpha Centauari A',
            'Alpha Centauari B',
            'Betelgeuse',
            'Rigel',
            'Other'
        ];
        $locationNameChoices = [
            'solar_system' => array_combine($planets, $planets),
            'star' => array_combine($stars, $stars),
            'interstellar_space' => null,
        ];
        return $locationNameChoices[$location] ?? null; // in case of "Choose a location"
    }
}
