<?php

  /**
    * @file
    * Contains \Drupal\contactus\Form\ContactUsForm
    */

  namespace Drupal\contactus\Form;

  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;

  /**
    * Provides an ContactUs form
    */

  class ContactForm extends FormBase {
  	/**
  	  * (@inheritdoc)
  	  */
  	public function getFormId() {
  	  return 'contactus_form';
  	}

  	/**
  	  * (@inheritdoc)
  	  */
  	public function buildForm(array $form, FormStateInterface $form_state) {
      $form['full_name'] = array(
        '#title' => t('Full Name'),
        '#type' => 'textfield',
        '#size' => 50,
        '#required' => TRUE,  
      );

      $form['phone_number'] = array(
        '#title' => t('Phone Number'),
        '#type' => 'tel',
        '#size' => 10,
        '#required' => TRUE,
      );

  	  $form['email'] = array(
  	    '#title' => t('Email Adderss'),
  	    '#type' => 'textfield',
  	    '#size' => 50,
  	    '#required' => TRUE,	
  	  );

      $form['message'] = array(
        '#title' => t('Message'),
        '#type' => 'textarea',
        '#size' => 555,
        '#required' => TRUE,  
      );

  	  $form['submit'] = array(
  	  	'#type' => 'submit',
  	  	'#value' => t('submit'),
  	  );
  	  return $form;
  	}

	  /**
  	  * (@inheritdoc)^[a-zA-Z]{0,50}$
  	  */
  	public function validateForm(array &$form, FormStateInterface $form_state) {
      //full name validation
      $full_name_value = $form_state->getValue('full_name');
      if (!preg_match('/^([a-zA-Z]+\s)*[a-zA-Z]{0,50}$/',$full_name_value)) {
        $form_state->setErrorByName('name', t('Full name should contains characters only and should be less than 50 characters'));
      }
      //phone number validation
      $phone_number_value = $form_state->getValue('phone_number');
      if (!preg_match('/^[0-9]{10}$/',$phone_number_value)) {
        $form_state->setErrorByName('phone number', t('Telephone numbers should contain 10 digits and numeric'));
      }
      //email validation
  	  $email_value = $form_state->getValue('email');
  	  if($email_value == !\Drupal::service('email.validator')->isValid($email_value)) {
  	    $form_state->setErrorByName('email', t('Email %mail is not valid', array('%mail' => $email_value)));
  	 	}
      //message validation
      $message_value = $form_state->getValue('message');
      if (!preg_match('/^[a-zA-Z0-9]{0,555}$/',$message_value)) {
        $form_state->setErrorByName('name', t('Message is too long'));
      }
  	}  	

  	/**
  	  * (@inheritdoc)
  	  */
  	public function submitForm(array &$form, FormStateInterface $form_state) {
      mail($form_state->getValue('email'),
        "Thank you for registering!",
        "Hello, thank you for registering!",
        "From: balrajsingh299@gmail.com"
      );
      db_insert('contactus')
        ->fields(array(
          'name' => $form_state->getValue('full_name'),
          'phone' => $form_state->getValue('phone_number'),
          'mail' => $form_state->getValue('email'),
          'message' => $form_state->getValue('message'),
          'created' => date("F d, Y h:i:s A", time()),
        ))
        ->execute();
      drupal_set_message(t('Thanks for subscribing.')); 	
    }
  }