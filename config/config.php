<?php

use app\engine\Request;
use app\engine\Db;
use app\engine\Session;
use \app\models\repositories\UsersRepository;
use \app\models\repositories\RightsMenuRepository;
use \app\models\repositories\OrdersRepository;
use \app\models\repositories\OrdersTimelineRepository;
use \app\models\repositories\CompanyRepository;
use \app\models\repositories\FrstatusRepository;
use \app\models\repositories\SitesRepository;
use \app\models\repositories\FilesRepository;
use \app\models\repositories\TableViewsRepository;
use \app\models\repositories\RoleRepository;
use \app\models\repositories\MailRepository;
use \app\models\repositories\PriorityRepository;
use \app\models\repositories\ManagerSitesRepository;
use \app\models\repositories\PoWorksheetsRepository;
use \app\models\repositories\WorksheetsStatusRepository;
use \app\models\repositories\QuoteRepository;
use \app\models\repositories\SiteTypesRepository;
use \app\models\repositories\CitiesRepository;
use \app\models\repositories\ReportsRepository;
use \app\models\repositories\MKBRepository;
use \app\models\repositories\RoleMenuRepository;
use \app\models\repositories\AccessRepository;
use \app\models\repositories\PagesRepository;
use \app\models\repositories\TemplateSitecapabilityRepository;
use \app\models\repositories\DiseasesSitecapabilityRepository;
use \app\models\repositories\TissuesSitecapabilityRepository;
use \app\models\repositories\TissuesRepository;
use \app\models\repositories\ManagerSitecapabilityRepository;
use \app\models\repositories\SiteCapabilityTypeRepository;
use \app\models\repositories\ManagerSiteCapabilityAnswerRepository;
use \app\models\repositories\SiteCapabilityQuestionRepository;
use \app\models\repositories\ManagerSiteCapabilityQuestionRepository;
use \app\models\repositories\DepartmentsRepository;
use \app\models\repositories\OrdersStatusRepository;
use \app\models\repositories\OrdersStatusActionsRepository;
use \app\models\repositories\WorksheetsInvertoryRepository;
use \app\models\repositories\WorksheetsLaboratoryRepository;
use \app\models\repositories\MarketplaceRepository;
use \app\models\repositories\TypeScriptRepository;
use \app\models\repositories\СurrencyRepository;
use \app\models\repositories\CompanyStaffRepository;
use \app\models\repositories\CompanyTypesRepository;
use \app\models\repositories\CompaniesContactsRepository;
use \app\models\repositories\CompaniesContactsSpecialRepository;
use \app\models\repositories\TicketsRepository;
use \app\models\repositories\ShippingRepository;
use \app\models\repositories\CourierRepository;
use \app\models\repositories\ContainerRepository;
use \app\models\repositories\OfferRepository;
use \app\models\repositories\OfferItemRepository;
use \app\models\repositories\OfferItemTimelineRepository;
use \app\models\repositories\OfferItemInclusionCriteriaRepository;
use \app\models\repositories\OfferItemExclusionCriteriaRepository;
use \app\models\repositories\OfferItemClinicalInformationRepository;
use \app\models\repositories\OfferItemListOfSamplesRepository;
use \app\models\repositories\OfferOptionRepository;
use \app\models\repositories\DiseaseRepository;
use \app\models\repositories\DiseaseCategoryRepository;
use \app\models\repositories\DiseaseGroupRepository;
use \app\models\repositories\BiospecimenTypeRepository;
use \app\models\repositories\StorageConditionsRepository;
use \app\models\repositories\OfferApprovalRepository;
use \app\models\repositories\OfferStatusTriggerRepository;
use \app\models\repositories\OfferApeRepository;
use \app\models\repositories\OfferApeItemRepository;
use \app\models\repositories\OfferApeItemTimelineRepository;
use \app\models\repositories\OfferApeItemInclusionCriteriaRepository;
use \app\models\repositories\OfferApeItemExclusionCriteriaRepository;
use \app\models\repositories\OfferApeItemAssociatedInformationRepository;
use \app\models\repositories\OfferApeItemListOfSamplesRepository;
use \app\models\repositories\OrderDiseasesRepository;
use \app\models\repositories\DiseasesBiospecimenTypesRepository;
use \app\models\repositories\ClinicalCaseRepository;
use \app\models\repositories\ClinicalCaseDraftRepository;
use \app\models\repositories\SampleModRepository;
use \app\models\repositories\SiteLogWorkStatusesRepository;
use \app\models\repositories\QuoteDoctorRepository;
use \app\models\repositories\NewTicketsRepository;
use \app\models\repositories\NewTicketChatsRepository;
use \app\models\repositories\NewTicketApprovalsRepository;
use \app\models\repositories\NewTicketRatingRepository;
use \app\models\repositories\NewTicketTargetRepository;
use \app\models\repositories\LabLevelsRepository;
use \app\models\repositories\QuoteSampleRepository;
use \app\services\MailWorker;
use \app\models\repositories\OfferRuRepository;
use \app\models\repositories\OfferRuItemRepository;
use \app\models\repositories\OfferRuItemTimelineRepository;
use \app\models\repositories\OfferRuItemInclusionCriteriaRepository;
use \app\models\repositories\OfferRuItemExclusionCriteriaRepository;
use \app\models\repositories\OfferRuItemClinicalInformationRepository;
use \app\models\repositories\OfferRuItemListOfSamplesRepository;
use \app\models\repositories\VacationRepository;
use \app\models\repositories\IpAccessRepository;
use \app\models\repositories\TicketsScoreRepository;
use \app\models\repositories\LogChangeRepository;
use \app\models\repositories\PoRepository;
use \app\models\repositories\WorksheetsRepository;
use \app\models\repositories\PoDiseaseRepository;
use \app\models\repositories\PoDiseaseSampleModRepository;
use \app\models\repositories\PoQuoteRepository;
use \app\models\repositories\PoQuoteDoctorRepository;
use \app\models\repositories\PoQuoteSampleRepository;
use \app\models\repositories\PoStatusRepository;
use \app\models\repositories\CurrencyRepository;
use \app\models\repositories\PoDataRepository;
use \app\models\repositories\ChatRepository;



return [
    'root_dir' => __DIR__ . "/../",
    'template_dir' => __DIR__ . "/../twigTamplates/",
    'controllers_namespaces' => "app\controllers\\",
    'components' => [
        'request' => [
            'class' => Request::class
        ],
        'session' => [
            'class' => Session::class
        ],

        'rightsMenuRepository' => [
            'class' => RightsMenuRepository::class
        ],
        'ordersRepository' => [
            'class' => OrdersRepository::class
        ],
        'usersRepository' => [
            'class' => UsersRepository::class
        ],
        'companyRepository' => [
            'class' => CompanyRepository::class
        ],
        'frstatusRepository' => [
            'class' => FrstatusRepository::class
        ],
        'sitesRepository' => [
            'class' => SitesRepository::class
        ],
        'filesRepository' => [
            'class' => FilesRepository::class
        ],
        'tableViewsRepository' => [
            'class' => TableViewsRepository::class
        ],
        'roleRepository' => [
            'class' => RoleRepository::class
        ],
        'mailRepository' => [
            'class' => MailRepository::class
        ],
        'priorityRepository' => [
            'class' => PriorityRepository::class
        ],
        'managerSitesRepository' => [
            'class' => ManagerSitesRepository::class
        ],
        'worksheetsStatusRepository' => [
            'class' => WorksheetsStatusRepository::class
        ],
        'quoteRepository' => [
            'class' => QuoteRepository::class
        ],
        'siteTypesRepository' => [
            'class' => SiteTypesRepository::class
        ],
        'сitiesRepository' => [
            'class' => CitiesRepository::class
        ],
        'reportsRepository' => [
            'class' => ReportsRepository::class
        ],
        'mkbRepository' => [
            'class' => MKBRepository::class
        ],
        'rolemenuRepository' => [
            'class' => RoleMenuRepository::class
        ],
        'accessRepository' => [
            'class' => AccessRepository::class
        ],
        'pagesRepository' => [
            'class' => PagesRepository::class
        ],
        'templateSitecapabilityRepository' => [
            'class' => TemplateSitecapabilityRepository::class
        ],
        'diseasesSitecapabilityRepository' => [
            'class' => DiseasesSitecapabilityRepository::class
        ],
        'tissuesSitecapabilityRepository' => [
            'class' => TissuesSitecapabilityRepository::class
        ],
        'tissuesRepository' => [
            'class' => TissuesRepository::class
        ],
        'managerSitecapabilityRepository' => [
            'class' => ManagerSitecapabilityRepository::class
        ],
        'siteCapabilityTypeRepository' => [
            'class' => SiteCapabilityTypeRepository::class
        ],
        'managerSiteCapabilityAnswerRepository' => [
            'class' => ManagerSiteCapabilityAnswerRepository::class
        ],
        'siteCapabilityQuestionRepository' => [
            'class' => SiteCapabilityQuestionRepository::class
        ],
        'managerSiteCapabilityQuestionRepository' => [
            'class' => ManagerSiteCapabilityQuestionRepository::class
        ],
        'departmentsRepository' => [
            'class' => DepartmentsRepository::class
        ],
        'ordersStatusRepository' => [
            'class' => OrdersStatusRepository::class
        ],
        'ordersStatusActionsRepository' => [
            'class' => OrdersStatusActionsRepository::class
        ],
        'worksheetsInvertoryRepository' => [
            'class' => WorksheetsInvertoryRepository::class
        ],
        'worksheetsLaboratoryRepository' => [
            'class' => WorksheetsLaboratoryRepository::class
        ],
        'typeScriptRepository' => [
            'class' => TypeScriptRepository::class
        ],
        'marketplaceRepository' => [
            'class' => MarketplaceRepository::class
        ],
        'currencyRepository' => [
            'class' => СurrencyRepository::class
        ],
        'companyStaffRepository' => [
            'class' => CompanyStaffRepository::class
        ],
        'companyTypesRepository' => [
            'class' => CompanyTypesRepository::class
        ],
        'companiesContactsRepository' => [
            'class' => CompaniesContactsRepository::class
        ],
        'ticketsRepository' => [
            'class' => TicketsRepository::class
        ],
        'shippingRepository' => [
            'class' => ShippingRepository::class
        ],
        'courierRepository' => [
            'class' => CourierRepository::class
        ],
        'containerRepository' => [
            'class' => ContainerRepository::class
        ],
        'offerRepository' => [
            'class' => OfferRepository::class
        ],
        'offerItemRepository' => [
            'class' => OfferItemRepository::class
        ],
        'offerItemTimelineRepository' => [
            'class' => OfferItemTimelineRepository::class
        ],
        'offerItemInclusionCriteriaRepository' => [
            'class' => OfferItemInclusionCriteriaRepository::class
        ],
        'offerItemExclusionCriteriaRepository' => [
            'class' => OfferItemExclusionCriteriaRepository::class
        ],
        'offerItemClinicalInformationRepository' => [
            'class' => OfferItemClinicalInformationRepository::class
        ],
        'offerItemListOfSamplesRepository' => [
            'class' => OfferItemListOfSamplesRepository::class
        ],
        'offerOptionRepository' => [
            'class' => OfferOptionRepository::class
        ],
        'companiesContactsSpecialRepository' => [
            'class' => CompaniesContactsSpecialRepository::class
        ],
        'diseaseRepository' => [
            'class' => DiseaseRepository::class
        ],
        'diseaseCategoryRepository' => [
            'class' => DiseaseCategoryRepository::class
        ],
        'diseaseGroupRepository' => [
            'class' => DiseaseGroupRepository::class
        ],
        'biospecimenTypeRepository' => [
            'class' => BiospecimenTypeRepository::class
        ],
        'storageConditionsRepository' => [
            'class' => StorageConditionsRepository::class
        ],
        'offerApprovalRepository' => [
            'class' => OfferApprovalRepository::class
        ],
        'offerStatusTriggerRepository' => [
            'class' => OfferStatusTriggerRepository::class
        ],
        'offerApeRepository' => [
            'class' => OfferApeRepository::class
        ],
        'offerApeItemRepository' => [
            'class' => OfferApeItemRepository::class
        ],
        'offerApeItemTimelineRepository' => [
            'class' => OfferApeItemTimelineRepository::class
        ],
        'offerApeItemInclusionCriteriaRepository' => [
            'class' => OfferApeItemInclusionCriteriaRepository::class
        ],
        'offerApeItemExclusionCriteriaRepository' => [
            'class' => OfferApeItemExclusionCriteriaRepository::class
        ],
        'offerApeItemAssociatedInformationRepository' => [
            'class' => OfferApeItemAssociatedInformationRepository::class
        ],
        'offerApeItemListOfSamplesRepository' => [
            'class' => OfferApeItemListOfSamplesRepository::class
        ],
        'orderDiseasesRepository' => [
            'class' => OrderDiseasesRepository::class
        ],
        'diseasesBiospecimenTypesRepository' => [
            'class' => DiseasesBiospecimenTypesRepository::class
        ],
        'clinicalCaseRepository' => [
            'class' => ClinicalCaseRepository::class
        ],
        'clinicalCaseDraftRepository' => [
            'class' => ClinicalCaseDraftRepository::class
        ],
        'sampleModRepository' => [
            'class' => SampleModRepository::class
        ],
        'siteLogWorkStatusesRepository' => [
            'class' => SiteLogWorkStatusesRepository::class
        ],
        'quoteDoctorRepository' => [
            'class' => QuoteDoctorRepository::class
        ],
        'newTicketsRepository' => [
            'class' => NewTicketsRepository::class
        ],
        'newTicketChatsRepository' => [
            'class' => NewTicketChatsRepository::class
        ],
        'newTicketApprovalsRepository' => [
            'class' => NewTicketApprovalsRepository::class
        ],
        'labLevelsRepository' => [
            'class' => LabLevelsRepository::class
        ],
        'quoteSampleRepository' => [
            'class' => QuoteSampleRepository::class
        ],
        'newTicketRatingRepository' => [
            'class' => NewTicketRatingRepository::class
        ],
        'newTicketTargetRepository' => [
            'class' => NewTicketTargetRepository::class
        ],
        'offerRuRepository' => [
            'class' => OfferRuRepository::class
        ],
        'offerRuItemRepository' => [
            'class' => OfferRuItemRepository::class
        ],
        'offerRuItemTimelineRepository' => [
            'class' => OfferRuItemTimelineRepository::class
        ],
        'offerRuItemInclusionCriteriaRepository' => [
            'class' => OfferRuItemInclusionCriteriaRepository::class
        ],
        'offerRuItemExclusionCriteriaRepository' => [
            'class' => OfferRuItemExclusionCriteriaRepository::class
        ],
        'offerRuItemClinicalInformationRepository' => [
            'class' => OfferRuItemClinicalInformationRepository::class
        ],
        'vacationRepository' => [
            'class' => VacationRepository::class
        ],
        'offerRuItemListOfSamplesRepository' => [
            'class' => OfferRuItemListOfSamplesRepository::class
        ],
        'ipAccessRepository' => [
            'class' => IpAccessRepository::class
        ],
        'ticketsScoreRepository' => [
            'class' => TicketsScoreRepository::class
        ],
        'logChangeRepository' => [
            'class' => LogChangeRepository::class
        ],
        'worksheetsRepository' => [
            'class' => WorksheetsRepository::class
        ],
        'poRepository' => [
            'class' => PoRepository::class
        ],
        'poworksheetsRepository' => [
            'class' => PoWorksheetsRepository::class
        ],
        'poDiseaseRepository' => [
            'class' => PoDiseaseRepository::class
        ],
        'poDiseaseSampleModRepository' => [
            'class' => PoDiseaseSampleModRepository::class
        ],
        'poquoteRepository' => [
            'class' => PoQuoteRepository::class
        ],
        'poquoteDoctorRepository' => [
            'class' => PoQuoteDoctorRepository::class
        ],
        'poquoteSampleRepository' => [
            'class' => PoQuoteSampleRepository::class
        ],
        'postatusRepository' => [
            'class' => PoStatusRepository::class
        ],
        'currencyRepository' => [
            'class' => CurrencyRepository::class
        ],
        'poDataRepository' => [
            'class' => PoDataRepository::class
        ],
        'chatRepository' => [
            'class' => ChatRepository::class
        ]
    ],
];
