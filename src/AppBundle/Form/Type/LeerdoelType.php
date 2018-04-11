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

class LeerdoelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('beschrijving', TextType::class)
        ;
		$builder->add('bron', EntityType::class, array(
			'class' => 'AppBundle:Bron',
			'choice_label' => 'naam',
			'required' => false,
		));
    $builder->add('context', EntityType::class, array(
			'class' => 'AppBundle:Context',
			'choice_label' => 'beschrijving',
			'required' => false,
		));
		$builder->add('toets', EntityType::class, array(
			'class' => 'AppBundle:Toets',
			'choice_label' => 'naam',
      'query_builder' => function(EntityRepository $repository) {
            return $repository->createQueryBuilder('t')->orderBy('t.naam', 'ASC');
        },
			'required' => false,
		));
		$builder
			->add('bloomniveau', IntegerType::class, array(
				"required" => false,
			))
        ;
		$builder
			->add('code', TextType::class, array(
				"required" => false,
			))
        ;
    }

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Leerdoel',
		));
	}
}
?>
