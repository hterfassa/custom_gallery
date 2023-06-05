<?php

namespace Drupal\custom_gallery\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Provides a form for creating a custom gallery.
 */
class CustomGalleryForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_gallery_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#required' => TRUE,
    ];

    $form['images'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Images'),
      '#upload_location' => 'public://custom_gallery',
      '#multiple' => TRUE,
      '#description' => $this->t('Upload one or more images for the gallery.'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $title = $form_state->getValue('title');
    $images = $form_state->getValue('images');

    $fileIds = [];
    foreach ($images as $image) {
      if (!empty($image[0])) {
        $file = File::load($image[0]);
        if ($file) {
          $file->setPermanent();
          $file->save();
          $fileIds[] = $file->id();
        }
      }
    }

    \Drupal::messenger()->addMessage($this->t('Custom Gallery with title @title and images (@images) created.', [
      '@title' => $title,
      '@images' => implode(', ', $fileIds),
    ]));
  }

}