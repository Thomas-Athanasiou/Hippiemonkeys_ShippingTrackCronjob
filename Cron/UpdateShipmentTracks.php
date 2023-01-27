<?php
    /**
     * @Thomas-Athanasiou
     *
     * @author Thomas Athanasiou {thomas@hippiemonkeys.com}
     * @link https://hippiemonkeys.com
     * @link https://github.com/Thomas-Athanasiou
     * @copyright Copyright (c) 2022 Hippiemonkeys Web Inteligence EE All Rights Reserved.
     * @license http://www.gnu.org/licenses/ GNU General Public License, version 3
     * @package Hippiemonkeys_ShippingTrackCronjob
     */

    declare(strict_types=1);

    namespace Hippiemonkeys\ShippingTrackCronjob\Cron;

    use Hippiemonkeys\Core\Api\Helper\ConfigInterface,
        Hippiemonkeys\ShippingTrack\Api\Data\StatusInterface,
        Hippiemonkeys\ShippingTrack\Api\StatusRepositoryInterface,
        Hippiemonkeys\ShippingTrack\Api\ShipmentTrackManagementInterface,
        Hippiemonkeys\ShippingTrack\Exception\NoSuchEntityException;

    class UpdateShipmentTracks
    {
        protected const
            CONF_PATH_LIMIT = 'cronjob_processshipment_limit',
            CONF_PATH_STATUS_IDS = 'cronjob_processshipment_status_ids';

        /**
         * Constructor
         *
         * @access public
         *
         * @param \Hippiemonkeys\Core\Api\Helper\ConfigInterface $config
         * @param \Hippiemonkeys\ShippingTrack\Api\StatusRepositoryInterface $statusRepository
         * @param \Hippiemonkeys\ShippingTrack\Api\ShipmentTrackManagementInterface $shipmentTrackManagement
         */
        public function __construct(
            ConfigInterface $config,
            StatusRepositoryInterface $statusRepository,
            ShipmentTrackManagementInterface $shipmentTrackManagement
        )
        {
            $this->_config = $config;
            $this->_statusRepository = $statusRepository;
            $this->_shipmentTrackManagement = $shipmentTrackManagement;
        }

        /**
         * @inheritdoc
         */
        public function execute(): void
        {
            if($this->getIsActive())
            {
                $limit = $this->getLimit();
                $shipmentManagement = $this->getShipmentTrackManagement();
                foreach($this->getStatuses() as $status)
                {
                    $shipmentManagement->updateShipmentTracksWithStatusAndLimit($status, $limit);
                }
            }
        }

        /**
         * Gets Statuses
         *
         * @access protected
         *
         * @return \Hippiemonkeys\ShippingTrack\Api\Data\StatusInterface[]
         */
        protected function getStatuses(): array
        {
            $statusRepository = $this->getStatusRepository();
            return \array_filter(
                \array_map(
                    function(string $id) use ($statusRepository): ?StatusInterface
                    {
                        $status = null;

                        try
                        {
                            $status = $statusRepository->getById($id);
                        }
                        catch (NoSuchEntityException)
                        {

                        }

                        return $status;
                    },
                    \explode(',', $this->getConfig()->getData(static::CONF_PATH_STATUS_IDS))
                ),
                function(?StatusInterface $status): bool
                {
                    return $status !== null;
                },
                0
            );
        }

        /**
         * Gets limit
         *
         * @access protected
         *
         * @return int
         */
        protected function getLimit(): int
        {
            return (int) $this->getConfig()->getData(static::CONF_PATH_LIMIT);
        }

        /**
         * Status Repository property
         *
         * @access private
         *
         * @var \Hippiemonkeys\ShippingTrack\Api\StatusRepositoryInterface $_statusRepository
         */
        private $_statusRepository;

        /**
         * Gets Status Repository
         *
         * @access protected
         *
         * @return \Hippiemonkeys\ShippingTrack\Api\StatusRepositoryInterface
         */
        protected function getStatusRepository(): StatusRepositoryInterface
        {
            return $this->_statusRepository;
        }

        /**
         * Shipment Track Management property
         *
         * @access private
         *
         * @var \Hippiemonkeys\ShippingTrack\Api\ShipmentTrackManagementInterface $_shipmentTrackManagement
         */
        private $_shipmentTrackManagement;

        /**
         * Gets Update Histories
         *
         * @access protected
         *
         * @return \Hippiemonkeys\ShippingTrack\Api\ShipmentTrackManagementInterface
         */
        protected function getShipmentTrackManagement(): ShipmentTrackManagementInterface
        {
            return $this->_shipmentTrackManagement;
        }

        /**
         * Gets Is Active Flag
         *
         * @access protected
         *
         * @return bool
         */
        protected function getIsActive(): bool
        {
            return $this->getConfig()->getIsActive();
        }

        /**
         * Config property
         *
         * @access private
         *
         * @var \Hippiemonkeys\Core\Api\Helper\ConfigInterface $_config
         */
        private $_config;

        /**
         * Gets Config
         *
         * @access protected
         *
         * @return \Hippiemonkeys\Core\Api\Helper\ConfigInterface
         */
        protected function getConfig(): ConfigInterface
        {
            return $this->_config;
        }
    }
?>