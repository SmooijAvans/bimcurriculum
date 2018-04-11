<?php
// src/AppBundle/Form/Type/TaskType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class MedewerkerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('code', TextType::class)
        ;
		$builder
			->add('volledigeNaam', TextType::class)
            ->add(
            'rol', 'choice', [
                'choices' => ['ROLE_CUCO' => 'Curriculum Coordinator', 'ROLE_DOCENT' => 'Docent', 'ROLE_ONTWIKKELAAR' => 'Ontwikkelaar'],
                'expanded' => true,
                'multiple' => false,
            ]
        );
    }

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Medewerker',
		));
	}
}
?>
