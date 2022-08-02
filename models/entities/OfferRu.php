<?php

namespace app\models\entities;
use app\models\Model;


class OfferRu extends Model
{
    public $id;
    protected $proj_id;
    protected $date_offer;
    protected $scripts_staff_id;
    protected $date_valid;
    protected $user_id;
    protected $courier_id;
    protected $turnaround;
    protected $storage;
    protected $shipping;
    protected $incoterms;
    protected $estimated;
    protected $shipping_id;
    protected $count_shipping;
    protected $export_permit;
    protected $count_export_permit;
    protected $customs_clearance;
    protected $count_customs_clearance;
    protected $thermologger;
    protected $count_thermologger;
    protected $packaging;
    protected $count_packaging;
    protected $deleted;
    protected $ru_shipping;
    protected $ru_gmc;
    protected $ru_capacity;
    protected $ru_thermal_mode;
    protected $ru_count;
    protected $ru_total;
    protected $zabolevanie;
    protected $opis;
    protected $kol;
    protected $cenaEtapa;
    protected $obstoimost;
    protected $typeDelivery;



    public function __construct(
        $proj_id = null,
        $date_offer = null,
        $scripts_staff_id = null,
        $date_valid = null,
        $user_id = 1,
        $courier_id = 1,
        $turnaround = '2-3 недели',
        $storage = '-80C',
        $shipping = '',
        $incoterms = 'DAP',
        $estimated = 1,
        $shipping_id = 1,
        $count_shipping = 1,
        $export_permit = 'no',
        $count_export_permit = 1,
        $customs_clearance = 'no',
        $count_customs_clearance = 1,
        $thermologger = 'no',
        $count_thermologger = 1,
        $packaging = 'no',
        $count_packaging = 1,
        $deleted = null,
        $ru_shipping = null,
        $ru_gmc = null,
        $ru_capacity = null,
        $ru_thermal_mode = null,
        $ru_count = null,
        $ru_total = null,

        $zabolevanie = null,
        $opis = null,
        $kol = null,
        $cenaEtapa = null,
        $obstoimost = null,
        $typeDelivery = 0
    )
    {
        $this->proj_id = $proj_id;
        $this->date_offer = $date_offer;
        $this->scripts_staff_id = $scripts_staff_id;
        $this->date_valid = $date_valid;
        $this->user_id = $user_id;
        $this->courier_id = $courier_id;
        $this->turnaround = $turnaround;
        $this->storage = $storage;
        $this->shipping = $shipping;
        $this->incoterms = $incoterms;
        $this->estimated = $estimated;
        $this->shipping_id = $shipping_id;
        $this->count_shipping = $count_shipping;
        $this->export_permit = $export_permit;
        $this->count_export_permit = $count_export_permit;
        $this->customs_clearance = $customs_clearance;
        $this->count_customs_clearance = $count_customs_clearance;
        $this->thermologger = $thermologger;
        $this->count_thermologger = $count_thermologger;
        $this->packaging = $packaging;
        $this->count_packaging = $count_packaging;
        $this->deleted = $deleted;
        $this->ru_shipping = $ru_shipping;
        $this->ru_gmc = $ru_gmc;
        $this->ru_capacity = $ru_capacity;
        $this->ru_thermal_mode = $ru_thermal_mode;
        $this->ru_count = $ru_count;
        $this->ru_total = $ru_total;
        $this->zabolevanie = $zabolevanie;
        $this->opis = $opis;
        $this->kol = $kol;
        $this->cenaEtapa = $cenaEtapa;
        $this->obstoimost = $obstoimost;
        $this->typeDelivery = $typeDelivery;

        $this->arrayParams['proj_id'] = false;
        $this->arrayParams['date_offer'] = false;
        $this->arrayParams['scripts_staff_id'] = false;
        $this->arrayParams['date_valid'] = false;
        $this->arrayParams['user_id'] = false;
        $this->arrayParams['courier_id'] = false;
        $this->arrayParams['turnaround'] = false;
        $this->arrayParams['storage'] = false;
        $this->arrayParams['shipping'] = false;
        $this->arrayParams['incoterms'] = false;
        $this->arrayParams['estimated'] = false;
        $this->arrayParams['shipping_id'] = false;
        $this->arrayParams['count_shipping'] = false;
        $this->arrayParams['export_permit'] = false;
        $this->arrayParams['count_export_permit'] = false;
        $this->arrayParams['customs_clearance'] = false;
        $this->arrayParams['count_customs_clearance'] = false;
        $this->arrayParams['thermologger'] = false;
        $this->arrayParams['count_thermologger'] = false;
        $this->arrayParams['packaging'] = false;
        $this->arrayParams['count_packaging'] = false;
        $this->arrayParams['deleted'] = false;
        $this->arrayParams['ru_shipping'] = false;
        $this->arrayParams['ru_gmc'] = false;
        $this->arrayParams['ru_capacity'] = false;
        $this->arrayParams['ru_thermal_mode'] = false;
        $this->arrayParams['ru_count'] = false;
        $this->arrayParams['ru_total'] = false;
        $this->arrayParams['zabolevanie'] = false;
        $this->arrayParams['opis'] = false;
        $this->arrayParams['kol'] = false;
        $this->arrayParams['cenaEtapa'] = false;
        $this->arrayParams['obstoimost'] = false;
        $this->arrayParams['typeDelivery'] = false;
    }
}
