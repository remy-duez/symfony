<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;
class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextareaField::new('content'),
            AssociationField::new('category')->autocomplete(),
            AssociationField::new('author')->hideonForm(),
            TextField::new('imageFile')->setFormType(VichImageType::class),
            ImageField::new('image')->setBasePath('uploads/post_images')->onlyOnIndex()
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Post')
        ->setEntityLabelInPlural('Posts')
        ->setDefaultSort(['createdAt' => 'DESC'])
        ->setSearchFields(['title', 'content']);
    }

}
