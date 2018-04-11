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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class VerbeteractieType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('toets', EntityType::class, array(
			'class' => 'AppBundle:Toets',
			'choice_label' => 'naam',
			'required' => false,
			'disabled' => true,
		));
        $builder
			->add('actie', TextType::class)
        ;
        $builder
			->add('beschrijving', TextareaType::class, array(
				'required' => 'false'
				))
        ;
        $builder
			->add('uren', NumberType::class, array('label' => 'Duur in (klok)uren'))
        ;
        $builder
			->add('status', HiddenType::class, array(
				'read_only' => true,
				'data' => 'aanvraag',
		))
        ;
        $builder
			->add('medewerker', EntityType::class, array(
			'class' => 'AppBundle:Medewerker',
			'choice_label' => 'volledigenaam',
			'required' => false,
			'label' => 'Medewerker die de actie gaat uitvoeren',
		));
        ;
        ;
		
    }
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Verbeteractie',
		));
	}
}
?>