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
use Doctrine\ORM\EntityRepository;

class LeeruitkomstType extends AbstractType
{

	protected $leerdoel;

	function __construct($leerdoel = -1)
	{
		$this->leerdoel = $leerdoel;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('beschrijving', TextType::class, array(
			'required' => true,
			))
			;
		$builder->add('leerdoel', EntityType::class, array(
			'class' => 'AppBundle:Leerdoel',
			'choice_label' => 'beschrijving',
			'query_builder' => function(EntityRepository $er) {
				return $er->createQueryBuilder('l')
					->orderBy('l.beschrijving', 'ASC');
			},
		));
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Leeruitkomst',
			'courseid' => $this->leerdoel,
		));
	}

}
?>
