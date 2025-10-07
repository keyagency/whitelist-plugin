<?php namespace Key\Whitelist\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Key\Whitelist\Models\EmergencyAccess as EmergencyAccessModel;
use Key\Whitelist\Models\Settings;
use Flash;
use Request;
use Lang;
use Mail;
use Redirect;

/**
 * Emergency Access Back-end Controller
 */
class EmergencyAccess extends Controller
{
    /**
     * Request emergency access for a blocked IP
     */
    public function request()
    {
        $settings = Settings::instance();

        // Check if emergency access is enabled
        if (!$settings->enable_emergency_access) {
            Flash::error(Lang::get('key.whitelist::lang.emergency.error_disabled'));
            return Redirect::back();
        }

        $ipAddress = $this->getClientIp();

        // Check if there's already a pending request
        if (EmergencyAccessModel::hasPendingRequest($ipAddress)) {
            Flash::warning(Lang::get('key.whitelist::lang.emergency.error_already_requested'));
            return Redirect::back();
        }

        // Get token duration from settings
        $duration = $settings->access_token_duration ?: 24;

        // Create the request
        $accessRequest = EmergencyAccessModel::createRequest($ipAddress, $duration);

        // Get admin emails
        $adminEmails = $this->getAdminEmails($settings);

        if (empty($adminEmails)) {
            Flash::error(Lang::get('key.whitelist::lang.emergency.error_generic'));
            return Redirect::back();
        }

        // If manual approval required, send email
        if ($settings->require_manual_approval) {
            $this->sendApprovalEmail($accessRequest, $adminEmails);
            Flash::success(Lang::get('key.whitelist::lang.emergency.request_sent_message'));
        } else {
            // Auto-approve
            $accessRequest->approve();
            Flash::success(Lang::get('key.whitelist::lang.emergency.approved_message'));
        }

        return Redirect::back();
    }

    /**
     * Approve an emergency access request via token
     */
    public function approve($token)
    {
        $accessRequest = EmergencyAccessModel::findByToken($token);

        if (!$accessRequest) {
            Flash::error(Lang::get('key.whitelist::lang.emergency.token_invalid'));
            return Redirect::to('/');
        }

        if ($accessRequest->isExpired()) {
            Flash::error(Lang::get('key.whitelist::lang.emergency.token_expired'));
            return Redirect::to('/');
        }

        if ($accessRequest->approve()) {
            Flash::success(Lang::get('key.whitelist::lang.emergency.approved_message'));
        } else {
            Flash::error(Lang::get('key.whitelist::lang.emergency.token_expired'));
        }

        return Redirect::to('/');
    }

    /**
     * Get client IP address
     */
    protected function getClientIp(): string
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_REAL_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR',
        ];

        foreach ($headers as $header) {
            if ($ip = Request::server($header)) {
                // Handle X-Forwarded-For which can contain multiple IPs
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                return $ip;
            }
        }

        return Request::ip();
    }

    /**
     * Get admin emails from settings
     */
    protected function getAdminEmails(Settings $settings): array
    {
        if (empty($settings->emergency_access_emails)) {
            return [];
        }

        $emails = array_filter(
            array_map('trim', explode("\n", $settings->emergency_access_emails))
        );

        // Validate emails
        return array_filter($emails, function ($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        });
    }

    /**
     * Send approval email to administrators
     */
    protected function sendApprovalEmail(EmergencyAccessModel $accessRequest, array $adminEmails): void
    {
        $approvalUrl = url('/whitelist/emergency-access/approve/' . $accessRequest->token);

        $data = [
            'ip_address' => $accessRequest->ip_address,
            'approval_url' => $approvalUrl,
            'expires_at' => $accessRequest->expires_at->format('Y-m-d H:i:s'),
            'requested_at' => $accessRequest->created_at->format('Y-m-d H:i:s'),
        ];

        foreach ($adminEmails as $email) {
            Mail::send('key.whitelist::mail.emergency_access_request', $data, function ($message) use ($email) {
                $message->to($email);
                $message->subject('Emergency Access Request - IP Whitelist');
            });
        }
    }
}
