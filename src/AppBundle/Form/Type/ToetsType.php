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

class ToetsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('code', TextType::class)
        ;
		$builder
			->add('naam', TextType::class)
		;
		$builder
			->add('voorkennis', TextType::class)
		;
		$builder
			->add('hulpmiddelen', TextType::class)
		;
    $builder
			->add('duurinminuten', IntegerType::class, array('label' => 'Duur in minuten'))
		;
		$builder->add('course', EntityType::class, array(
			'class' => 'AppBundle:Course',
			'choice_label' => 'naam',
			'query_builder' => function(EntityRepository $er) {
				return $er->createQueryBuilder('c')
					->orderBy('c.jaar', 'ASC');
			},
			'required' => false,
		));
    $builder->add('verantwoordelijke', EntityType::class, array(
			'class' => 'AppBundle:Medewerker',
			'choice_label' => 'volledigenaam',
			'required' => false,
		));
    $builder->add('reviewer', EntityType::class, array(
			'class' => 'AppBundle:Medewerker',
			'choice_label' => 'volledigenaam',
			'required' => false,
		));
		$builder->add('soort', EntityType::class, array(
			'class' => 'AppBundle:ToetsSoort',
			'choice_label' => 'naam',
			'required' => false,
		));
		$builder->add('resultaatschaal', ChoiceType::class, array(
			'choices'  => array(
			'0-10' => "0-10",
			'ZO/O/V/RV/G/ZG' => 'ZO/O/V/RV/G/ZG',
			'O/V' => 'O/V',
		),
		));
    $builder->add('weging', TextType::class, array(
      'data' => 1,
    ));
    $builder->add('minimale_eis', TextType::class);
    $builder->add('compensabel', ChoiceType::class, array(
			'choices'  => array(
        0 => "Nee",
        1 => "Ja",
		),
		));
    $builder->add('taal', ChoiceType::class, array(
			'choices'  => array(
        "NL" => "Nederlands",
        "EN" => "Engels",
		),
		));
		$builder->add('domein', EntityType::class, array(
			'class' => 'AppBundle:Domein',
			'choice_label' => 'naam',
			'required' => false,
		));
		$builder->add('ec', NumberType::class, array(
			"scale" => 1,
		));
		$builder->add('onderzoeksaspect', CheckboxType::class, array(
			'label'    => 'Bevat aspecten m.b.t. onderzoek',
			'required' => false,
		));
    $builder->add('veranderaspect', CheckboxType::class, array(
			'label'    => 'Bevat aspecten m.b.t. verandermanagement',
			'required' => false,
		));
		$builder->add('duurzaamheidsaspect', CheckboxType::class, array(
			'label'    => 'Bevat aspecten m.b.t. duurzaamheid',
			'required' => false,
		));
    $builder->add('internationaliseringsaspect', CheckboxType::class, array(
			'label'    => 'Bevat aspecten m.b.t. internationalisering',
			'required' => false,
		));
    }

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Toets',
		));
	}
}
?>
