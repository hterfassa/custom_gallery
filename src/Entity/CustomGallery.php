<?php

namespace Drupal\custom_gallery\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\node\Entity\Node;

/**
 * Defines the Custom Gallery entity.
 *
 * @ContentEntityType(
 *   id = "custom_gallery",
 *   label = @Translation("Custom Gallery"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\custom_gallery\Entity\CustomGalleryListBuilder",
 *     "form" = {
 *       "default" = "Drupal\custom_gallery\Form\CustomGalleryForm",
 *       "add" = "Drupal\custom_gallery\Form\CustomGalleryForm",
 *       "edit" = "Drupal\custom_gallery\Form\CustomGalleryForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *   },
 *   base_table = "custom_gallery",
 *   admin_permission = "administer custom gallery entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "label" = "title",
 *   },
 *   links = {
 *     "canonical" = "/custom-gallery/{custom_gallery}",
 *     "add-form" = "/custom-gallery/add",
 *     "edit-form" = "/custom-gallery/{custom_gallery}/edit",
 *     "delete-form" = "/custom-gallery/{custom_gallery}/delete",
 *     "collection" = "/custom-gallery",
 *   },
 * )
 */
class CustomGallery extends ContentEntityBase {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    // Existing field definitions...

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Custom Gallery entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Custom Gallery entity.'))
      ->setReadOnly(TRUE);

    $fields['field_title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The title of the Custom Gallery entity.'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setDefaultValue('');

    $fields['field_images'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Images'))
      ->setDescription(t('The images of the Custom Gallery entity.'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('form', [
        'type' => 'image',
        'weight' => 0,
      ])
      ->setDisplayOptions('view', [
        'type' => 'image',
        'weight' => 0,
      ]);

    // Existing field definitions...

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE) {
    parent::postSave($storage, $update);

    // Create a new node of the gallery content type.
    $node = Node::create([
      'type' => 'custom_gallery', // Replace 'custom_gallery' with your gallery content type machine name.
      'title' => $this->get('field_title')->value,
      'field_images' => $this->get('field_images')->getValue(),
    ]);
    $node->save();
  }

}