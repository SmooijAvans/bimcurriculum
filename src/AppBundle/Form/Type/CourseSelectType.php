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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class CourseSelectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
        ->add('courses', EntityType::class, array(
          'label' => 'Filter op course',
          'class' => 'AppBundle:Course',
          'query_builder' => function (EntityRepository $er) {
            return $er->createQueryBuilder('c')
            ->addOrderBy('c.jaar', 'ASC')
            ->addOrderBy('c.periode', 'ASC');
          },
          'choice_label' => 'naam',
          'required' => false,
          ))
        ->add('bronnen', TextType::class, array(
          'label' => 'Zoek op bron',
          'required' => false,
        ));
    }

	public function configureOptions(OptionsResolver $resolver)
	{

	}
}
?>
