<?php

namespace TPS\Birzha\Actions;

use Backend\Models\User as AdminUserModel;
use Backend\Models\UserGroup as AdminGroupModel;
use Illuminate\Support\Facades\Log;
use Mail;
use October\Rain\Exception\ApplicationException;
use RainLab\Notify\Classes\ActionBase;
use System\Models\MailTemplate;

class MailToAdminsAction extends ActionBase
{
    /**
     * Returns information about this event, including name and description.
     */
    public function actionDetails()
    {
        return [
            'name'        => 'Send a mail message to administrators',
            'description' => 'Send a message to a recipient',
            'icon'        => 'icon-envelope'
        ];
    }

    /**
     * Field configuration for the action.
     */
    public function defineFormFields()
    {
        return 'fields.yaml';
    }

    /**
     * Defines validation rules for the custom fields.
     * @return array
     */
    public function defineValidationRules()
    {
        return [
            'mail_template' => 'required',
        ];
    }
    public function getTitle()
    {
        return 'Compose mail to administrators';
    }

    public function getActionIcon()
    {
        return 'icon-envelope-square';
    }

    public function getText()
    {
        $hostObj = $this->host;

        if ($groupId = $this->host->send_to_admin) {
            if ($group = AdminGroupModel::find($groupId)) {
                $adminText = $group->name;
            }
            else {
                $adminText = '?';
            }

            $adminText .= ' admin group';
        }
        else {
            $adminText = 'all admins';
        }
        return sprintf(
            'Send a message to %s using template %s',
            $adminText,
            $hostObj->mail_template
        );

    }
    public function getSendToAdminOptions()
    {
        $options = ['' => '- All administrators -'];

        $groups = AdminGroupModel::lists('name', 'id');

        return $options + $groups;
    }

    public function getMailTemplateOptions()
    {
        $codes = array_keys(MailTemplate::listAllTemplates());
        $result = array_combine($codes, $codes);
        return $result;
    }

    protected function getRecipientAddress()
    {
        if ($groupId = $this->host->send_to_admin) {
            if (!$group = AdminGroupModel::find($groupId)) {
                throw new ApplicationException('Unable to find admin group with ID: '.$groupId);
            }

            return $group->users->lists('full_name', 'email');
        }
        else {
            return AdminUserModel::all()->lists('full_name', 'email');
        }
    }
    /**
     * Triggers this action.
     * @param array $params
     * @return void
     */
    public function triggerAction($params)
    {
        $template = $this->host->mail_template;

        $recipient = $this->getRecipientAddress();

        if (!$recipient || !$template) {
            throw new ApplicationException('Missing valid recipient or mail template');
        }

        Mail::sendTo($recipient, $template, $params);
    }
}
