<?php
/**
 * Office365Plugin plugin for phplist.
 *
 * This file is a part of Office365Plugin Plugin.
 *
 * This plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @category  phplist
 *
 * @author    Duncan Cameron
 * @copyright 2016-2017 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * Registers the plugin with phplist.
 */
if (!interface_exists('EmailSender')) {
    return;
}
class Office365Plugin extends phplistPlugin implements EmailSender
{
    const VERSION_FILE = 'version.txt';

    /** @var Office365 connector instance */
    private $mail;

    /*
     *  Inherited variables
     */
    public $name = 'Office365 Plugin';
    public $authors = 'Tristan Mahe';
    public $description = 'Use Office365 to send emails';
    public $documentationUrl = 'https://resources.phplist.com/plugin/Office365';
    public $settings = array(
        'Office365_from_email' => array(
            'value' => '',
            'description' => 'Office365 Email',
            'type' => 'text',
            'allowempty' => false,
            'category' => 'Office365',
          ),
        'Office365_from_password' => array(
            'value' => '',
            'description' => 'Office365 Password',
            'type' => 'text',
            'allowempty' => false,
            'category' => 'Office365',
        ),
    );

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->coderoot = dirname(__FILE__) . '../PHPMailer/';
        parent::__construct();
        $this->version = (is_file($f = $this->coderoot . self::VERSION_FILE))
            ? file_get_contents($f)
            : '';
    }

    /**
     * Provide the dependencies for enabling this plugin.
     *
     * @return array
     */
    public function dependencyCheck()
    {
        global $emailsenderplugin;

        return array(
            'PHP version 5.4.0 or greater' => version_compare(PHP_VERSION, '5.4') > 0,
            'phpList version 3.3.0 or greater' => version_compare(getConfig('version'), '3.3') > 0,
            'No other plugin to send emails can be enabled' => empty($emailsenderplugin) || get_class($emailsenderplugin) == __CLASS__,
        );
    }

    /**
     * Send an email using the Office365 API.
     *
     * @see https://Office365.com/docs/API_Reference/Web_API/mail.html
     *
     * @param PHPlistMailer $phpmailer mailer instance
     * @param string        $headers   the message http headers
     * @param string        $body      the message body
     *
     * @return bool success/failure
     */
    public function send(PHPlistMailer $phpmailer, $headers, $body)
    {
        if ($this->mail === null) {
          require $this->coderoot . 'PHPMailerAutoload.php';

          $pop = POP3::popBeforeSmtp('outlook.office365.com', 995, 30, getConfig('Office365_from_email'), getConfig('Office365_from_password'), 0);
          $this->mail = new PHPMailer(true);
        }
        $this->mail->isSMTP();


    $this->mail->SMTPDebug = 2;
    //Ask for HTML-friendly debug output
    $this->mail->Debugoutput = 'html';
    //Set the hostname of the mail server
    $this->mail->Host = "smtp.office365.com";
    $this->mail->Helo = 'stmp.office365.com';
    //Set the SMTP port number - likely to be 25, 465 or 587
    $this->mail->Port = 587;
    //Whether to use SMTP authentication
    $this->mail->Username = getConfig('Office365_from_email');
    $this->mail->Password = getConfig('Office365_from_password');
    $mail->SMTPAuth = true;
    //Set who the message is to be sent from
    $this->mail->setFrom(getConfig('Office365_from_email'), $phpmailer->FromName);
    //Set an alternative reply-to address
    $this->mail->addReplyTo(getConfig('Office365_from_email'), 'Sailors Academy');

    $to = $phpmailer->getToAddresses();

    //Set who the message is to be sent to
    $this->mail->addAddress($to[0][0]);

    //Set the subject line
    $this->mail->Subject = $phpmailer->Subject;

    $isHtml = $phpmailer->AltBody != '';

    $this->mail->msgHTML($phpmailer->Body);
    //Replace the plain text body with one created manually
    $this->mail->AltBody = $phpmailer->AltBody;

    try {
      $this->mail->send();
      return true;
    } catch phpmailerException $e {
      echo 'PHPMailer exception';
      echo $e->errorMessage();
      return false;
    }
    catch (Exception $e) {
      echo $e->getMessage();
      return false;
    }

    }
}
