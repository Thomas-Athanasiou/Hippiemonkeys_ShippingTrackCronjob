<?php
    /**
     * @Thomas-Athanasiou
     *
     * @author Thomas Athanasiou {thomas@hippiemonkeys.com}
     * @link https://hippiemonkeys.com
     * @link https://github.com/Thomas-Athanasiou
     * @copyright Copyright (c) 2022 Hippiemonkeys Web Intelligence EE All Rights Reserved.
     * @license http://www.gnu.org/licenses/ GNU General Public License, version 3
     * @package Hippiemonkeys_ShippingTrackCronjob
     */

    declare(strict_types=1);

    namespace Hippiemonkeys\ShippingTrackCronjob\Cron;

    use Hippiemonkeys\Core\Api\Helper\ConfigInterface,
        Hippiemonkeys\ShippingTrack\Api\ShipmentTrackManagementInterface;

    class UpdateStatelessShipmentTracks
    {
        protected const
            CONF_PATH_LIMIT = 'cronjob_processshipment_limit';

        /**
         * Constructor
         *
         * @access public
         *
         * @param \Hippiemonkeys\Core\Api\Helper\ConfigInterface $config
         * @param \Hippiemonkeys\ShippingTrack\Api\ShipmentTrackManagementInterface $shipmentTrackManagement
         */
        public function __construct(
            ConfigInterface $config,
            ShipmentTrackManagementInterface $shipmentTrackManagement
        )
        {
            $this->_config = $config;
            $this->_shipmentTrackManagement = $shipmentTrackManagement;
        }

        /**
         * @inheritdoc
         */
        public function execute(): void
        {
            if($this->getIsActive())
            {
                $this->getShipmentTrackManagement()->updateStatelessShipmentTracksWithLimit($this->getLimit());
            }
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

        /**
         * Status property
         *
         * @access private
         *
         * @var int $_status
         */
        private $_status;

        /**
         * Gets Status
         *
         * @access protected
         *
         * @return int
         */
        protected function getStatus(): int
        {
            return $this->_status;
        }
    }
?>