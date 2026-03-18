<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AuditReport extends Mailable
{
    use Queueable, SerializesModels;
    public $order;
    public $orderCode;
    public $description;
    public $storeManagerName;
    public $storeName;
    public $vendorName;
    public $storeAddress;
    public $storePhone;
    protected $vendorRecepit;
    protected $storeManagerRecepit;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order,$orderCode, $description,$storeManagerName,$storeName,$storeAddress,$storePhone,$vendorName,$vendorRecepit,$storeManagerRecepit)
    {
        $this->order = $order;
        $this->orderCode = $orderCode;
        $this->description = $description;
        $this->storeManagerName = $storeManagerName;
        $this->storeName = $storeName;
        $this->storeAddress = $storeAddress;
        $this->storePhone =$storePhone;
        $this->vendorName = $vendorName;
        $this->storeManagerRecepit = $storeManagerRecepit;
        $this->vendorRecepit = $vendorRecepit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.AuditReport')->with([
            'order' => $this->order,
            'orderCode' => $this->orderCode,  // Pass order code to the view
            'description' => $this->description,
            'storeManagerName' => $this->storeManagerName,
            'storeName' => $this->storeName,
            'storeAddress' => $this->storeAddress,
            'storePhone' => $this->storePhone,
            'vendorName' => $this->vendorName,
            'vendorRecepit' => $this->vendorRecepit,
            'storeManagerRecepit' => $this->storeManagerRecepit,
            ])
        ->subject('Audit Report : Overcharged Prices');

    }
}
