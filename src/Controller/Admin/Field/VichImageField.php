<?php

namespace App\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Vich\UploaderBundle\Form\Type\VichImageType;

class VichImageField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(VichImageType::class)
            ->setCustomOption('image_uri', null)
            ->setCustomOption('download_uri', null)
            ;
    }

    public function setImageUri($imageUri): self
    {
        $this->setCustomOption('image_uri', $imageUri);

        return $this;
    }

    public function setDownloadUri($downloadUri): self
    {
        $this->setCustomOption('download_uri', $downloadUri);

        return $this;
    }
}