<?php

namespace Drupal\custom_gallery\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\custom_gallery\Entity\CustomGallery;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for the Custom Gallery module.
 */
class CustomGalleryController extends ControllerBase {

  /**
   * Renders the custom gallery form.
   *
   * @return array
   *   The form render array.
   */
  public function customGalleryForm() {
    $form = \Drupal::formBuilder()->getForm('Drupal\custom_gallery\Form\CustomGalleryForm');
    return $form;
  }

  /**
   * Renders a custom gallery item.
   *
   * @param \Drupal\custom_gallery\Entity\CustomGallery $custom_gallery
   *   The custom gallery entity.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The response object.
   */
  public function customGalleryItem(CustomGallery $custom_gallery) {
    // Customize this method to display the custom gallery item.
    // You can access the custom gallery entity properties using $custom_gallery.
    $content = 'Custom Gallery Item: ' . $custom_gallery->getTitle();
    return new Response($content);
  }

}