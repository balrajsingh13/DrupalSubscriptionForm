<?php
namespace Drupal\contactus\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

/**
  * Controller for RSVP List Report
  */
class ContactController extends ControllerBase {
  public function report() {
    /**
     * Get all Subscribers info.
     * @return array
     */
    $query = \Drupal::database()->select('contactus', 'c');
    $query->fields('c', ['id', 'name', 'phone', 'mail', 'message', 'created']);
    $results = $query->execute()->fetchAll();
         
    $rows = array();
    foreach ($results as $row => $data) {
      $rows[] = array(
        'data' => array($data->id, $data->name, $data->phone, $data->mail, $data->message, $data->created));
    }
    // Create the header.
    $header = array( t('Id'), t('Name'), t('Cont No.'), t('Email'), t('Message'), t('Created on'));
      $output = array(
        '#type' => 'table',   
        '#header' => $header,
        '#rows' => $rows
      );
      return $output;
  }
}