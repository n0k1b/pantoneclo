<?php
namespace App\Traits;

use App\Mail\MailTesting;
use App\Mail\OrderMail;
use App\Models\Setting;
use App\Models\SettingMail;
use App\Models\SettingStore;
use App\Traits\UtilitiesTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

trait MailTrait{

    use UtilitiesTrait;

    public function sendMailWithOrderDetailsInvoice($reference_no)
    {
        $setting       = Setting::get()->keyBy('key');
        $setting_mail  = SettingMail::select('mail_header_theme_color','mail_body_theme_color','mail_footer_theme_color','mail_layout_background_theme_color')->latest()->first();
        $setting_store = SettingStore::select('store_name')->latest()->first();
        $order         = $this->getOrderByReference($reference_no);

        // For PDF
        $orderArray    = $this->getOrderArray($order);
        $pdf           = Pdf::loadView('admin.pdf.invoice', $orderArray);

        // For Email Tamplate Body
        $data_mail     = $this->newOrderEmailTemplateBody($reference_no, $order, $setting_mail, $setting, $setting_store);
        Mail::to($data_mail['email'])->send(new OrderMail($data_mail, $pdf));
    }



    private function newOrderEmailTemplateBody($reference_no, $order, $setting_mail, $setting, $setting_store)
    {
        $data_mail = [];
        $data_mail['fullname']                 = $order->billing_first_name.' '.$order->billing_last_name;
        $data_mail['email']                    = $order->billing_email;
        $data_mail['reference_no']             = $reference_no;
        $data_mail['message']                  = 'Thanks for the shopping. Your order reference no is ';
        $data_mail['mail_header_theme_color']  = $setting_mail->mail_header_theme_color ?? "#808080";
        $data_mail['mail_body_theme_color']    = $setting_mail->mail_body_theme_color ?? "#FFFFFF";
        $data_mail['mail_footer_theme_color']  = $setting_mail->mail_footer_theme_color ?? "#00A884";
        $data_mail['mail_layout_background_theme_color']  = $setting_mail->mail_layout_background_theme_color ?? "#FFFFFF";
        $data_mail['storefront_facebook_link'] = $setting['storefront_facebook_link']->plain_value ?? null;
        $data_mail['storefront_twitter_link']  = $setting['storefront_twitter_link']->plain_value ?? null;
        $data_mail['storefront_instagram_link']= $setting['storefront_instagram_link']->plain_value ?? null;
        $data_mail['storefront_youtube_link']  = $setting['storefront_youtube_link']->plain_value ?? null;
        $data_mail['store_name']               = $setting_store->store_name ?? null;

        return $data_mail;
    }

    public function checkMailForTesting($mail_to){
        Mail::to($mail_to)->send(new MailTesting());
    }
}
