<?php

namespace Forms\Form;

use Forms\Form\DataTransformer\EmailToUserTransformer;
use SecurityAuth\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class UserSelectFormType extends AbstractType
{
    private $userRepository;
    private $router;

    public function __construct(UserRepository $userRepository, RouterInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new EmailToUserTransformer(
            $this->userRepository,
            $options['its_my_finder_callback']
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // totally override the attr option => use buildView
            /*'attr' => [
                'class' => 'js-user-autocomplete',
                'data-my-autocomplete-url' => $this->router->generate('admin_utility_users'), // need to be 'data'-.....
            ],*/

            'invalid_message' => 'Hmm, user not found!',

            // callback for EmailToUserTransformer
            'its_my_finder_callback' => function(UserRepository $userRepository, string $email) {
                return $userRepository->findOneBy(['email' => $email]);
            }
        ]);
    }

    // override default buildView method
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $attr = $view->vars['attr'];
        $attr['class'] = ($attr['class'] ?? '') . ' js-user-autocomplete';
        $attr['data-my-autocomplete-url'] = $this->router->generate('admin_utility_users');

        // set
        $view->vars['attr'] = $attr;
    }
}