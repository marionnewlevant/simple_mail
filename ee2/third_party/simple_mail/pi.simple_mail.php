<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Simple_mail Class
 *
 * @package   ExpressionEngine
 * @category  Plugin
 * @author    Marion Newlevant
 * @copyright Copyright (c) 2013, Marion Newlevant
 * @license   MIT
 */

$plugin_info = array(
    'pi_name'         => 'Simple Mail',
    'pi_version'      => '1.0',
    'pi_author'       => 'Marion Newlevant',
    'pi_author_url'   => 'http://marion.newlevant.com/',
    'pi_description'  => 'Very basic e-mail, suitable for ExpressionEngine Core',
    'pi_usage'        => Simple_mail::usage()
);

/**
 * Simple_mail - minimal e-mail sending EE plugin.
 * 
 * I use this with EE sites that use the Core license (which doesn't
 * include e-mail module). It is a fairly thin layer on top of the
 * CodeIgniter e-mail.
 * 
 * Limitations:
 * - error handling is pretty much non-existant
 * - form validation errors are not very granular
 * - user documentation is minimal
 */
class Simple_mail {
  
  public function __construct() {
  }
  
  /**
   * send: send e-mail if form is valid and filled out.
   */
  public function send() {
    $this->CI =& get_instance(); 
    if ($this->is_sent()) {
      // send the e-mail
      $senderEmail = $this->CI->input->post('email');
      $message = $this->CI->input->post('message');
      $subject = $this->CI->input->post('subject');
      $recipient = ee()->TMPL->fetch_param('recipient');
      $this->CI->load->library('email');
      $this->CI->email->from($senderEmail);
      $this->CI->email->to($recipient);
      $this->CI->email->subject($subject.' (EE simple_mail)');
      $this->CI->email->message($message);
      if (!$this->CI->email->send()) {
        log_message('error', 'mail sending failed');
        log_message('error', $this->email->print_debugger());
      }
    }
  }
  
  /**
   * sent: Is the form valid and filled out (and therefore the e-mail will have been sent)?
   * 
   * @access public
   * @return boolean
   */
  public function sent() {
    return $this->is_sent();
  }
  
  /**
   * validation_errors: return validation errors as series of <p>
   * 
   * @access public
   * @return string
   */
  public function validation_errors() {
    return validation_errors();
  }
  
  public function usage() {
    $usage = <<<EOT
E-mail sending plugin. In the usual way, we have a form which
posts back to the same page, so the first thing is to check whether
the form has been filled in and is valid. If so, send the e-mail, and display the
confirmation. If not, display the form (including any validation errors).

=== Example ===
{exp:simple_mail:send recipient="recipient@sample.com"}

{if "{exp:simple_mail:sent}"}
  <p>Thank You</p>
{if:else}
  <div class="error">{exp:simple_mail:validation_errors}</div>
  <form method="post">
    <input type="email" name="email" value="" id="email"  />
    <select name="subject" id="subject">
      <option value="just hi">just hi</option>
    </select>
    <textarea name="message" id="message"></textarea><br/><br/>
    <input type="submit" value="submit"/>
  </form>
{/if}
       
=== Details ===
Form fields are:
* email - sender's e-mail address
* subject - subject of the e-mail
* message - content of the e-mail message
the form action should post back to the same page

Tags:
* exp:simple_mail:send - parameter: recipient
-  at top of page: will send the e-mail if the form is filled in.
* exp:simple_mail:sent
-  was the mail sent? boolean
* exp:simple_mail:validation_errors
-  possibly empty list of <p> formatted form errors
EOT;
    return $usage;
  }
  
  /**
   * is_sent: is the form validly filled out? Runs the form validation.
   */
  private function is_sent() {
    $this->CI =& get_instance(); 
    $this->CI->load->helper('form');      
    $this->CI->load->library('form_validation');     
    $this->CI->form_validation->set_rules('message', 'Message', 'required');
    $this->CI->form_validation->set_rules('email', 'E-mail Address', 'required|valid_email');
    return $this->CI->form_validation->run();
  }
}

/* End of file pi.simple_mail.php */
/* Location: ./system/expressionengine/third_party/simple_mail/pi.simple_mail.php */
