<?php

class EmailNotification {
    private $smtpHost;
    private $smtpPort;
    private $fromEmail;
    private $fromName;

    public function __construct($config = []) {
        $this->smtpHost = $config['smtp_host'] ?? 'smtp.gmail.com';
        $this->smtpPort = $config['smtp_port'] ?? 587;
        $this->fromEmail = $config['from_email'] ?? 'noreply@taxsafar.com';
        $this->fromName = $config['from_name'] ?? 'TaxSafar CA Inquiry System';
    }

    /**
     * Send inquiry confirmation email to user
     */
    public function sendInquiryConfirmation($toEmail, $fullName, $inquiryId) {
        $subject = 'Inquiry Received - Ticket #' . $inquiryId;
        $body = "Dear $fullName,\n\nThank you for submitting your inquiry.\n";
        $body .= "Your inquiry ID is: $inquiryId\n";
        $body .= "We will respond within 24 hours.\n\nBest regards,\nTaxSafar Team";
        
        return $this->send($toEmail, $subject, $body);
    }

    /**
     * Send status update notification to user
     */
    public function sendStatusUpdate($toEmail, $fullName, $status, $message = '') {
        $subject = 'Inquiry Status Updated';
        $body = "Dear $fullName,\n\nYour inquiry status has been updated to: $status\n";
        if (!empty($message)) {
            $body .= "Message: $message\n";
        }
        $body .= "\nBest regards,\nTaxSafar Team";
        
        return $this->send($toEmail, $subject, $body);
    }

    /**
     * Send new inquiry notification to admin
     */
    public function sendAdminNotification($adminEmail, $inquiryData) {
        $subject = 'New Inquiry Received: ' . $inquiryData['full_name'];
        $body = "New inquiry received:\n\n";
        $body .= "Name: " . $inquiryData['full_name'] . "\n";
        $body .= "Email: " . $inquiryData['email'] . "\n";
        $body .= "Mobile: " . $inquiryData['mobile'] . "\n";
        $body .= "Query: " . substr($inquiryData['query'], 0, 100) . "...\n";
        
        return $this->send($adminEmail, $subject, $body);
    }

    /**
     * Generic send email method
     */
    private function send($to, $subject, $body) {
        $headers = "From: {$this->fromName} <{$this->fromEmail}>\r\n";
        $headers .= "Reply-To: {$this->fromEmail}\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        return mail($to, $subject, $body, $headers);
    }
}
