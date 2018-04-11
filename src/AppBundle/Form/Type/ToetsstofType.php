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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Doctrine\ORM\EntityRepository;

class ToetsstofType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
  		$builder->add('toets', EntityType::class, array(
  			'class' => 'AppBundle:Toets',
  			'choice_label' => 'displayValue',
  			'query_builder' => function(EntityRepository $er) {
  				return $er->createQueryBuilder('t')
  					->orderBy('t.naam', 'ASC');
  			},
  			'required' => true,
        'disabled' => true,
  		));
      $builder->add('literatuur', EntityType::class, array(
  			'class' => 'AppBundle:Literatuur',
  			'choice_label' => 'displayValue',
  			'required' => true,
        'query_builder' => function(EntityRepository $er) {
  				return $er->createQueryBuilder('l')
  					->orderBy('l.auteur', 'ASC');
  			},
  		));
      $builder->add('stof', TextType::class);
    }

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Toetsstof',
		));
	}
}
?>
