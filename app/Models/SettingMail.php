<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingMail extends Model
{
    protected $fillable = [
        'mail_address',
        'mail_name',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'welcome_email',
        'new_order_to_admin',
        'invoice_mail',
        'mail_order_status',
        'mail_header_theme_color',
        'mail_body_theme_color',
        'mail_footer_theme_color',
        'mail_layout_background_theme_color',
    ];
}
