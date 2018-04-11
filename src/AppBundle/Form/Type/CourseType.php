<?php
// src/AppBundle/Form/Type/TaskType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('naam', TextType::class)
        ;
		$builder
			->add('beschrijving', TextareaType::class, array('attr' => array('rows' => '10')))
        ;
		$builder
			->add('jaar', IntegerType::class)
        ;
		$builder
			->add('periode', IntegerType::class)
        ;
		$builder->add('eigenaar', EntityType::class, array(
			'class' => 'AppBundle:Medewerker',
			'choice_label' => 'volledigenaam',
			'label' => 'Eigenaar / coordinator',
		));
		$builder
			->add('voltijddeeltijd', TextType::class)
        ;
    }

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Course',
		));
	}
}
?>
