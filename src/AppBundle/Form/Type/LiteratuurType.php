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

class LiteratuurType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('auteur', TextType::class, array(
  			'required' => false,
      ))
    ;
    
    $builder
      ->add('titel', TextType::class, array(
  			'required' => false,
      ))
    ;
    $builder
      ->add('identificatie', TextType::class, array(
  			'required' => false,
      ))
    ;
    $builder
      ->add('uitgever', TextType::class, array(
  			'required' => false,
      ))
    ;
    $builder
      ->add('jaartal', TextType::class, array(
  			'required' => false,
      ))
    ;
    $builder
      ->add('druk', TextType::class, array(
  			'required' => false,
      ))
    ;
  }

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Literatuur',
		));
	}
}
?>
